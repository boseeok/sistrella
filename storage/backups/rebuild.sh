#!/usr/bin/env bash
# Rebuild corrupt Laragon MySQL from logical dumps. Run ONLY after all dumps verified.
set -u
BIN="/c/laragon/bin/mysql/mysql-5.7.33-winx64/bin"
DATADIR_WIN="C:/laragon/data/mysql"
DATADIR_NIX="/c/laragon/data/mysql"
OUT="/e/mysql_logical_backup_20260617"
CORRUPT_BAK="/e/mysql_corrupt_datadir_20260617"

echo "### 0. Pre-flight: verify dumps"
bad=0; n=0
while read -r db; do
  [ -z "$db" ] && continue; n=$((n+1))
  f="$OUT/${db}.sql"
  if ! { [ -f "$f" ] && tail -c 120 "$f" | grep -q "Dump completed"; }; then
    echo "  MISSING/INCOMPLETE: $db"; bad=$((bad+1))
  fi
done < "$OUT/_dblist.txt"
echo "  databases=$n incomplete=$bad"
if [ "$bad" -ne 0 ]; then echo "ABORT: not all dumps complete"; exit 1; fi

echo "### 1. Shutdown read-only mysqld"
"$BIN/mysqladmin" -u root --host=127.0.0.1 shutdown 2>/dev/null
for i in $(seq 1 20); do
  c=$(powershell.exe -NoProfile -Command "(Get-Process mysqld -ErrorAction SilentlyContinue|Measure-Object).Count" 2>/dev/null | tr -d '\r ')
  [ "$c" = "0" ] && break; sleep 1
done
powershell.exe -NoProfile -Command "Get-Process mysqld -ErrorAction SilentlyContinue | Stop-Process -Force" 2>/dev/null
sleep 2
echo "  mysqld stopped"

echo "### 2. Move corrupt data dir to E: (physical backup + frees C:)"
if [ -e "$CORRUPT_BAK" ]; then echo "ABORT: $CORRUPT_BAK already exists"; exit 1; fi
mv "$DATADIR_NIX" "$CORRUPT_BAK" || { echo "ABORT: move failed"; exit 1; }
mkdir -p "$DATADIR_NIX"
echo "  moved -> $CORRUPT_BAK"

echo "### 3. Initialize fresh data dir"
"$BIN/mysqld" --initialize-insecure --datadir="$DATADIR_WIN" --log-error="$DATADIR_WIN/init.log" 2>&1
echo "  init exit=$?"; tail -3 "$DATADIR_NIX/init.log" 2>/dev/null

echo "### 4. Start fresh mysqld (normal)"
"$BIN/mysqld" --datadir="$DATADIR_WIN" --log-error="$DATADIR_WIN/mysqld.log" &
for i in $(seq 1 30); do
  c=$(powershell.exe -NoProfile -Command "(Get-NetTCPConnection -State Listen -LocalPort 3306 -ErrorAction SilentlyContinue|Measure-Object).Count" 2>/dev/null | tr -d '\r ')
  [ "$c" = "1" ] && break; sleep 1
done
echo "  port3306 listeners=$(powershell.exe -NoProfile -Command "(Get-NetTCPConnection -State Listen -LocalPort 3306 -ErrorAction SilentlyContinue|Measure-Object).Count" 2>/dev/null | tr -d '\r ')"

echo "### 5. Ensure root@127.0.0.1 / @% (app connects via TCP, empty password)"
"$BIN/mysql" -u root --host=127.0.0.1 -e "
  CREATE USER IF NOT EXISTS 'root'@'127.0.0.1' IDENTIFIED BY '';
  CREATE USER IF NOT EXISTS 'root'@'%' IDENTIFIED BY '';
  GRANT ALL PRIVILEGES ON *.* TO 'root'@'127.0.0.1' WITH GRANT OPTION;
  GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' WITH GRANT OPTION;
  FLUSH PRIVILEGES;" 2>&1 || \
"$BIN/mysql" -u root --protocol=PIPE -e "
  CREATE USER IF NOT EXISTS 'root'@'127.0.0.1' IDENTIFIED BY '';
  GRANT ALL PRIVILEGES ON *.* TO 'root'@'127.0.0.1' WITH GRANT OPTION; FLUSH PRIVILEGES;" 2>&1

echo "### 6. Reimport all databases"
ok=0; fail=0
while read -r db; do
  [ -z "$db" ] && continue
  f="$OUT/${db}.sql"
  if "$BIN/mysql" -u root --host=127.0.0.1 < "$f" 2>"$OUT/${db}.import.err"; then
    ok=$((ok+1)); rm -f "$OUT/${db}.import.err"
  else
    fail=$((fail+1)); echo "  IMPORT FAIL: $db -> $(tail -1 "$OUT/${db}.import.err")"
  fi
done < "$OUT/_dblist.txt"
echo "  reimport ok=$ok fail=$fail"

echo "### 7. Apply captured grants (best-effort)"
"$BIN/mysql" -u root --host=127.0.0.1 < "$OUT/_grants.sql" 2>/dev/null && echo "  grants applied" || echo "  some grants skipped (ok)"

echo "### 8. Verify"
dbcount=$("$BIN/mysql" -u root --host=127.0.0.1 -N -e "SHOW DATABASES" 2>/dev/null | grep -viE "^(information_schema|performance_schema|sys|mysql)$" | wc -l)
echo "  user databases now: $dbcount (expected ~205)"
"$BIN/mysql" -u root --host=127.0.0.1 -N -e "SELECT COUNT(*) FROM crochet_store.products" 2>&1 | sed 's/^/  crochet_store.products count: /'
echo "REBUILD DONE"

#!/usr/bin/env bash
# Per-database logical dump of all user DBs under force_recovery=6
set -u
BIN="/c/laragon/bin/mysql/mysql-5.7.33-winx64/bin"
OUT="/e/mysql_logical_backup_20260617"
mkdir -p "$OUT"
MANIFEST="$OUT/_manifest.tsv"
echo -e "database\tstatus\tbytes" > "$MANIFEST"

# Grants first
"$BIN/mysql" -u root --host=127.0.0.1 -N -e "SELECT CONCAT(QUOTE(user),'@',QUOTE(host)) FROM mysql.user" 2>/dev/null > "$OUT/_users.txt"
: > "$OUT/_grants.sql"
while read -r u; do
  [ -z "$u" ] && continue
  "$BIN/mysql" -u root --host=127.0.0.1 -N -e "SHOW GRANTS FOR $u" 2>/dev/null | sed 's/$/;/' >> "$OUT/_grants.sql"
done < "$OUT/_users.txt"
echo "grants captured: $(wc -l < "$OUT/_grants.sql") lines"

# DB list (exclude system schemas)
"$BIN/mysql" -u root --host=127.0.0.1 -N -e "SHOW DATABASES" 2>/dev/null \
  | grep -viE "^(information_schema|performance_schema|sys|mysql)$" > "$OUT/_dblist.txt"
total=$(wc -l < "$OUT/_dblist.txt")
echo "databases to dump: $total"

n=0; ok=0; fail=0
while read -r db; do
  [ -z "$db" ] && continue
  n=$((n+1))
  f="$OUT/${db}.sql"
  if "$BIN/mysqldump" -u root --host=127.0.0.1 \
        --single-transaction --quick --skip-lock-tables \
        --routines --triggers --events --hex-blob \
        --no-tablespaces --set-gtid-purged=OFF \
        --databases "$db" > "$f" 2>"$OUT/${db}.err"; then
    sz=$(wc -c < "$f")
    if grep -q "Dump completed" "$f"; then
      echo -e "${db}\tOK\t${sz}" >> "$MANIFEST"; ok=$((ok+1)); rm -f "$OUT/${db}.err"
    else
      echo -e "${db}\tINCOMPLETE\t${sz}" >> "$MANIFEST"; fail=$((fail+1))
    fi
  else
    sz=$(wc -c < "$f" 2>/dev/null || echo 0)
    echo -e "${db}\tFAIL\t${sz}" >> "$MANIFEST"; fail=$((fail+1))
  fi
  [ $((n % 25)) -eq 0 ] && echo "  ...$n/$total done (ok=$ok fail=$fail)"
done < "$OUT/_dblist.txt"

echo "DONE: total=$n ok=$ok fail=$fail"
echo "Failures:"; awk -F'\t' '$2!="OK"{print "  "$1" -> "$2}' "$MANIFEST"

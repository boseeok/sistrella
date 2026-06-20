#!/usr/bin/env bash
# Parallel per-database dump; skips DBs already completed.
set -u
BIN="/c/laragon/bin/mysql/mysql-5.7.33-winx64/bin"
OUT="/e/mysql_logical_backup_20260617"
export BIN OUT

dump_one() {
  db="$1"
  f="$OUT/${db}.sql"
  # skip if already complete
  if [ -f "$f" ] && tail -c 200 "$f" 2>/dev/null | grep -q "Dump completed"; then
    echo "SKIP $db"; return 0
  fi
  if "$BIN/mysqldump" -u root --host=127.0.0.1 \
        --single-transaction --quick --skip-lock-tables \
        --routines --triggers --events --hex-blob \
        --no-tablespaces --set-gtid-purged=OFF \
        --databases "$db" > "$f" 2>"$OUT/${db}.err"; then
    if tail -c 200 "$f" | grep -q "Dump completed"; then
      rm -f "$OUT/${db}.err"; echo "OK   $db"
    else
      echo "INCOMPLETE $db"
    fi
  else
    echo "FAIL $db"
  fi
}
export -f dump_one

cat "$OUT/_dblist.txt" | xargs -P 8 -I{} bash -c 'dump_one "$@"' _ {} 2>&1

# Rebuild manifest from results
M="$OUT/_manifest.tsv"
echo -e "database\tstatus\tbytes" > "$M"
ok=0; bad=0
while read -r db; do
  [ -z "$db" ] && continue
  f="$OUT/${db}.sql"
  if [ -f "$f" ] && tail -c 200 "$f" | grep -q "Dump completed"; then
    echo -e "${db}\tOK\t$(wc -c < "$f")" >> "$M"; ok=$((ok+1))
  else
    echo -e "${db}\tFAIL\t$( [ -f "$f" ] && wc -c < "$f" || echo 0)" >> "$M"; bad=$((bad+1))
  fi
done < "$OUT/_dblist.txt"
echo "DONE: ok=$ok fail=$bad"
echo "Failures:"; awk -F'\t' '$2!="OK"{print "  "$1}' "$M"

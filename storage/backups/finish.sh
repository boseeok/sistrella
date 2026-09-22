#!/usr/bin/env bash
# Wait for dump to finish, then run the full rebuild.
set -u
ROOT="/c/Users/Nectar Digit/crochet-store/storage/backups"
# 1. wait for dump completion
for i in $(seq 1 600); do
  grep -q "^DONE:" "$ROOT/dump_parallel.log" 2>/dev/null && break
  sleep 3
done
echo ">>> dump finished: $(grep '^DONE:' "$ROOT/dump_parallel.log" 2>/dev/null)"
# 2. run rebuild
bash "$ROOT/rebuild.sh"

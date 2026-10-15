#!/bin/bash
# Run by cPanel "Git Version Control" -> Deploy (see .cpanel.yml). Works without SSH shell access.
# Copies the website from this repository into the live folders, then backs up and updates the database.
# Never touches private/config.php, private/storage/ or public_html/uploads/, and never deletes files.
set -e
SRC="$(cd "$(dirname "$0")/.." && pwd)"
HOME_DIR="${HOME:-$(cd ~ && pwd)}"
WEB="$HOME_DIR/public_html"

# Where does the live app live? Normally next to public_html; some installs keep it inside public_html.
if [ -f "$WEB/private/config.php" ]; then PRIV="$WEB/private"; else PRIV="$HOME_DIR/private"; fi
mkdir -p "$PRIV/storage/logs"
LOG="$PRIV/storage/logs/deploy.log"
exec > >(tee -a "$LOG") 2>&1
echo "=== Deploy $(date '+%Y-%m-%d %H:%M:%S') from $SRC ==="
echo "public_html: $WEB"
echo "private:     $PRIV"

# 1. Files (overwrite, never delete)
(cd "$SRC/private" && tar cf - --exclude=./config.php --exclude=./storage .) | (cd "$PRIV" && tar xf -)
(cd "$SRC/public_html" && tar cf - --exclude=./uploads .) | (cd "$WEB" && tar xf -)
echo "Files copied."

# 2. Database: backup + update every time (photo import skips photos already imported);
#    new text and contact numbers only once per version, so later admin edits are not overwritten
PHP="$(command -v php || true)"
[ -x "$PHP" ] || PHP=/usr/local/bin/php
if [ ! -f "$PRIV/config.php" ]; then
  echo "No private/config.php yet: open the website to run the installer, then deploy again."
  exit 0
fi
MARK="$PRIV/storage/.content-applied-2.10.0"
if [ -f "$MARK" ]; then
  "$PHP" "$PRIV/cli/apply-update.php" --backup --migrate --import-photos
else
  "$PHP" "$PRIV/cli/apply-update.php" --backup --migrate --refresh-text --import-photos \
    "--phone=+91 79756 89308" "--whatsapp=917975689308"
  date > "$MARK"
fi
echo "=== Deploy finished ==="

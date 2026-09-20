#!/usr/bin/env bash
# Company Pulse - launch the website (run after setup_env.sh has been run once).
# Starts MySQL (if not already running), starts PHP's built-in web server
# bound to all network interfaces, and prints the URL to open.
set -euo pipefail

PORT="${PORT:-8000}"

load_brew_shellenv() {
  for brew_bin in /opt/homebrew/bin/brew /usr/local/bin/brew; do
    if [ -x "$brew_bin" ]; then
      eval "$("$brew_bin" shellenv)"
      return 0
    fi
  done
  return 1
}
load_brew_shellenv || true

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
if [ -f "$SCRIPT_DIR/index.php" ] && [ -d "$SCRIPT_DIR/includes" ]; then
  PROJECT_DIR="$SCRIPT_DIR"
else
  PROJECT_DIR="$HOME/Company-Pulse"
fi

if [ ! -f "$PROJECT_DIR/index.php" ]; then
  echo "Could not find the project at $PROJECT_DIR. Run setup_env.sh first." >&2
  exit 1
fi

PHP_BIN="$(brew --prefix php)/bin/php"
MYSQL_BIN="$(brew --prefix mysql)/bin/mysql"

echo "==> Starting MySQL..."
brew services start mysql >/dev/null

for i in $(seq 1 30); do
  if "$MYSQL_BIN" -uroot -e "SELECT 1" >/dev/null 2>&1; then
    break
  fi
  sleep 1
done

get_lan_ip() {
  for iface in en0 en1 en2 en3 bridge0; do
    ip=$(ipconfig getifaddr "$iface" 2>/dev/null || true)
    if [ -n "$ip" ]; then
      echo "$ip"
      return 0
    fi
  done
  ifconfig 2>/dev/null | awk '/inet /{print $2}' | grep -v '^127\.' | head -n1
}

LAN_IP="$(get_lan_ip || true)"

echo ""
echo "==> Company Pulse is starting on port $PORT"
echo "    Local:   http://localhost:$PORT"
if [ -n "$LAN_IP" ]; then
  echo "    Network: http://$LAN_IP:$PORT"
else
  echo "    Network: (no LAN IP detected)"
fi
echo ""
echo "Press Ctrl+C to stop."
echo ""

cd "$PROJECT_DIR"
exec "$PHP_BIN" -S 0.0.0.0:"$PORT"

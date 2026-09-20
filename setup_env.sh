#!/usr/bin/env bash
# Company Pulse - one-time environment setup for a fresh macOS machine.
# Installs Homebrew, git, PHP and MySQL, fetches the project if needed,
# then creates the database and tables. Requires only an internet connection.
set -euo pipefail

REPO_URL="https://github.com/yyeh5549-alt/Company-Pulse.git"
DB_NAME="company_pulse"

# Make sure `brew` (and anything it installs) is on PATH even in a brand new shell.
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
echo "==> Project directory: $PROJECT_DIR"

if ! command -v brew >/dev/null 2>&1; then
  echo "==> Installing Homebrew..."
  NONINTERACTIVE=1 /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
  load_brew_shellenv
fi

if ! command -v git >/dev/null 2>&1; then
  echo "==> Installing git..."
  brew install git
fi

if [ "$PROJECT_DIR" != "$SCRIPT_DIR" ] && [ ! -d "$PROJECT_DIR/.git" ]; then
  echo "==> Cloning Company Pulse into $PROJECT_DIR..."
  git clone "$REPO_URL" "$PROJECT_DIR"
fi

# Check formula installation via `brew list`, not `command -v` -- other
# copies of php/mysql on PATH (Anaconda, MacPorts, XAMPP, ...) would
# otherwise be mistaken for a Homebrew install and confuse the rest of
# this script, which relies on Homebrew's paths and defaults.
brew_has() { brew list --formula --versions "$1" >/dev/null 2>&1; }

if ! brew_has php; then
  echo "==> Installing PHP..."
  brew install php
fi

if ! brew_has mysql; then
  echo "==> Installing MySQL..."
  brew install mysql
fi

PHP_BIN="$(brew --prefix php)/bin/php"
MYSQL_BIN="$(brew --prefix mysql)/bin/mysql"

echo "==> Starting MySQL service..."
brew services start mysql >/dev/null

echo "==> Waiting for MySQL to accept connections..."
ready=0
for i in $(seq 1 30); do
  if "$MYSQL_BIN" -uroot -e "SELECT 1" >/dev/null 2>&1; then
    ready=1
    break
  fi
  sleep 1
done
if [ "$ready" -ne 1 ]; then
  echo "MySQL did not become ready in time." >&2
  exit 1
fi

echo "==> Creating database '$DB_NAME' (if missing)..."
"$MYSQL_BIN" -uroot -e "CREATE DATABASE IF NOT EXISTS $DB_NAME;"

echo "==> Creating tables..."
(cd "$PROJECT_DIR/includes" && "$PHP_BIN" setup.php >/dev/null)

echo ""
echo "==> Setup complete."
echo "    Project: $PROJECT_DIR"
echo "    Next:    ./launch_website.sh"

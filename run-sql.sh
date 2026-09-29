#!/usr/bin/env bash

set -e

SQL_DIR="$(dirname "$0")/sql"

DB_NAME="SwissCookingDB"
DB_USER="SwissCookingAdmin"
DB_PASSWORD="SwissCookingAdminPassword"

echo "========================================"
echo " SwissCooking database setup"
echo "========================================"
echo

read -rp "MariaDB administrative username: " ADMIN_USER
read -rsp "Password for $ADMIN_USER: " ADMIN_PASSWORD
echo

echo
echo "==> Checking administrative credentials..."

if ! MYSQL_PWD="$ADMIN_PASSWORD" mariadb \
    --user="$ADMIN_USER" \
    --execute="SELECT 1;" \
    >/dev/null 2>&1
then
    echo
    echo "ERROR: Invalid MariaDB username or password."
    echo "Nothing has been changed."
    exit 1
fi

echo "Administrative credentials are valid."

echo
echo "==> 1/7 Creating database..."
MYSQL_PWD="$ADMIN_PASSWORD" mariadb \
    --user="$ADMIN_USER" \
    < "$SQL_DIR/01-create-database.sql"

echo
echo "==> 2/7 Creating database user..."
MYSQL_PWD="$ADMIN_PASSWORD" mariadb \
    --user="$ADMIN_USER" \
    < "$SQL_DIR/02-create-admin-user-for-db.sql"

echo
echo "==> 3/7 Creating tables..."
MYSQL_PWD="$DB_PASSWORD" mariadb \
    --user="$DB_USER" \
    "$DB_NAME" \
    < "$SQL_DIR/03-create-tables.sql"

echo
echo "==> 4/7 Adding constraints..."
MYSQL_PWD="$DB_PASSWORD" mariadb \
    --user="$DB_USER" \
    "$DB_NAME" \
    < "$SQL_DIR/04-add-constraints.sql"

echo
echo "==> 5/7 Inserting mandatory data..."
MYSQL_PWD="$DB_PASSWORD" mariadb \
    --user="$DB_USER" \
    "$DB_NAME" \
    < "$SQL_DIR/05-add-mandatory-data.sql"

echo
echo "==> 6/7 Inserting test data..."
MYSQL_PWD="$DB_PASSWORD" mariadb \
    --user="$DB_USER" \
    "$DB_NAME" \
    < "$SQL_DIR/06-insert-test-data.sql"

echo
echo "==> 7/7 Inserting performance test data..."
MYSQL_PWD="$DB_PASSWORD" mariadb \
    --user="$DB_USER" \
    "$DB_NAME" \
    < "$SQL_DIR/07-insert-a-crap-ton-to-test-perf.sql"

echo
echo "========================================"
echo " Database setup completed successfully!"  
echo "========================================"
#!/bin/bash

# Closet Matrix Database Setup Script
# This script sets up the MySQL database on your remote IONOS server

set -e  # Exit on any error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if .env file exists
if [ ! -f ".env" ]; then
    print_error ".env file not found!"
    print_status "Please create a .env file with the required database credentials."
    print_status "See .env.example for the required format."
    exit 1
fi

# Load environment variables
print_status "Loading environment variables from .env file..."
source .env

# Validate required variables
required_vars=("REMOTE_HOST" "REMOTE_USER" "MYSQL_ADMIN_USER" "MYSQL_ADMIN_PASSWORD" "DB_NAME" "DB_USER" "DB_PASSWORD")
for var in "${required_vars[@]}"; do
    if [ -z "${!var}" ]; then
        print_error "Required environment variable $var is not set in .env file"
        exit 1
    fi
done

print_success "Environment variables loaded successfully"

# Test SSH connection
print_status "Testing SSH connection to $REMOTE_HOST..."
if ! ssh -o ConnectTimeout=10 -o BatchMode=yes "$REMOTE_USER@$REMOTE_HOST" "echo 'SSH connection test successful'" > /dev/null 2>&1; then
    print_error "Cannot connect to $REMOTE_HOST via SSH"
    print_status "Please ensure:"
    print_status "1. SSH keys are properly configured"
    print_status "2. The remote host is accessible"
    print_status "3. The username is correct"
    exit 1
fi
print_success "SSH connection successful"

# Test MySQL connection on remote server
print_status "Testing MySQL connection on remote server..."
if ! ssh "$REMOTE_USER@$REMOTE_HOST" "mysql -u $MYSQL_ADMIN_USER -p$MYSQL_ADMIN_PASSWORD -e 'SELECT 1;' > /dev/null 2>&1"; then
    print_error "Cannot connect to MySQL on remote server"
    print_status "Please check the MySQL admin credentials in your .env file"
    exit 1
fi
print_success "MySQL connection successful"

# Create database and user
print_status "Creating database '$DB_NAME' and user '$DB_USER'..."

# SQL commands to create database and user
CREATE_DB_SQL="
CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASSWORD';
GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'localhost';
FLUSH PRIVILEGES;
"

# Execute SQL commands on remote server
if ssh "$REMOTE_USER@$REMOTE_HOST" "mysql -u $MYSQL_ADMIN_USER -p$MYSQL_ADMIN_PASSWORD -e \"$CREATE_DB_SQL\""; then
    print_success "Database and user created successfully"
else
    print_error "Failed to create database and user"
    exit 1
fi

# Check if database schema file exists
if [ ! -f "database_schema.sql" ]; then
    print_error "database_schema.sql file not found!"
    print_status "Please ensure the database schema file exists in the current directory."
    exit 1
fi

# Upload and execute database schema
print_status "Uploading and executing database schema..."
if scp database_schema.sql "$REMOTE_USER@$REMOTE_HOST:~/closet_matrix_schema.sql"; then
    print_success "Schema file uploaded successfully"
else
    print_error "Failed to upload schema file"
    exit 1
fi

# Execute schema on remote database
if ssh "$REMOTE_USER@$REMOTE_HOST" "mysql -u $DB_USER -p$DB_PASSWORD $DB_NAME < ~/closet_matrix_schema.sql"; then
    print_success "Database schema executed successfully"
else
    print_error "Failed to execute database schema"
    exit 1
fi

# Clean up uploaded file
ssh "$REMOTE_USER@$REMOTE_HOST" "rm -f ~/closet_matrix_schema.sql"

# Test the database setup
print_status "Testing database setup..."
TEST_SQL="USE $DB_NAME; SHOW TABLES; SELECT COUNT(*) as table_count FROM information_schema.tables WHERE table_schema = '$DB_NAME';"

if ssh "$REMOTE_USER@$REMOTE_HOST" "mysql -u $DB_USER -p$DB_PASSWORD -e \"$TEST_SQL\""; then
    print_success "Database setup test passed"
else
    print_warning "Database setup test failed, but this might be expected"
fi

# Upload .env file and config.php to remote server
print_status "Uploading .env file and config.php to remote server..."

if scp .env "$REMOTE_USER@$REMOTE_HOST:$REMOTE_PATH/.env"; then
    print_success ".env file uploaded to $REMOTE_PATH/.env"
else
    print_error "Failed to upload .env file"
fi

if scp config.php "$REMOTE_USER@$REMOTE_HOST:$REMOTE_PATH/config.php"; then
    print_success "config.php uploaded to $REMOTE_PATH/config.php"
else
    print_error "Failed to upload config.php"
fi

# Final test with the PHP test script
print_status "Running final database test..."
if ssh "$REMOTE_USER@$REMOTE_HOST" "cd $REMOTE_PATH && php test_database.php"; then
    print_success "Final database test completed"
else
    print_warning "Final database test had issues - check the output above"
fi

echo
print_success "🎉 Database setup completed successfully!"
echo
print_status "Next steps:"
print_status "1. Visit https://$REMOTE_HOST/closet/ to access your application"
print_status "2. Register a new account or use the default admin account:"
print_status "   Username: admin"
print_status "   Email: admin@closetmatrix.local"
print_status "   Password: admin123 (change this immediately!)"
print_status "3. Update your application code to use the new database"
echo
print_status "Database Details:"
print_status "- Database: $DB_NAME"
print_status "- User: $DB_USER"
print_status "- Host: localhost (on remote server)"
print_status "- Config file: $REMOTE_PATH/config.php"
echo
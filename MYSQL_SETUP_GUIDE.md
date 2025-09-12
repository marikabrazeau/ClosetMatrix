# MySQL Setup Guide for Closet Matrix

## 🚀 Quick Setup Instructions

### Step 1: Start MySQL Service

**On macOS:**
```bash
# If you installed MySQL via MySQL Installer:
sudo /usr/local/mysql/support-files/mysql.server start

# Or if using Homebrew:
brew services start mysql

# Or using system preferences (if you have MySQL in System Preferences)
# Go to System Preferences > MySQL > Start MySQL Server
```

**On Windows:**
```bash
# Start MySQL service
net start mysql

# Or use MySQL Workbench or Services panel
```

### Step 2: Connect to MySQL

```bash
# Connect as root (you'll be prompted for password)
mysql -u root -p

# Or if no password was set:
mysql -u root
```

### Step 3: Create the Database

Once connected to MySQL, run these commands:

```sql
-- Create the database
CREATE DATABASE closet_matrix_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create a user for the application (replace 'your_password' with a strong password)
CREATE USER 'closet_user'@'localhost' IDENTIFIED BY 'your_secure_password';

-- Grant permissions
GRANT ALL PRIVILEGES ON closet_matrix_db.* TO 'closet_user'@'localhost';

-- Apply changes
FLUSH PRIVILEGES;

-- Switch to the new database
USE closet_matrix_db;

-- Verify database is selected
SELECT DATABASE();
```

### Step 4: Run Database Schema

Now copy and paste the contents of `php/database_setup.sql` into your MySQL prompt, or run:

```sql
-- Copy the entire contents of php/database_setup.sql and paste here
-- This will create all the necessary tables
```

### Step 5: Verify Tables Were Created

```sql
-- Show all tables
SHOW TABLES;

-- Check users table structure
DESCRIBE users;

-- Check if sample data exists
SELECT COUNT(*) FROM users;
```

## 🔧 Configuration

### Update PHP Configuration

Edit `php/config.php` and update these values:

```php
// Database configuration
define('DB_HOST', 'localhost');  // Usually localhost for local development
define('DB_NAME', 'closet_matrix_db');
define('DB_USER', 'closet_user');  // The user you created above
define('DB_PASS', 'your_secure_password');  // The password you set above
```

## 🛠 Troubleshooting

### MySQL Not Starting?

**macOS:**
```bash
# Check if MySQL is running
ps aux | grep mysql

# If MySQL isn't in PATH, try:
export PATH=$PATH:/usr/local/mysql/bin

# Or add to your .zshrc or .bash_profile:
echo 'export PATH=$PATH:/usr/local/mysql/bin' >> ~/.zshrc
```

**Windows:**
- Check Services panel (services.msc) for MySQL service
- Use MySQL Workbench to connect
- Check if MySQL is installed in Program Files

### Can't Connect to MySQL?

1. **Check if MySQL is running:**
   ```bash
   # On macOS/Linux:
   sudo lsof -i :3306
   
   # On Windows:
   netstat -an | findstr 3306
   ```

2. **Reset root password if needed:**
   ```bash
   # Stop MySQL
   sudo /usr/local/mysql/support-files/mysql.server stop
   
   # Start in safe mode
   sudo mysqld_safe --skip-grant-tables &
   
   # Connect and reset password
   mysql -u root
   UPDATE mysql.user SET authentication_string=PASSWORD('new_password') WHERE User='root';
   FLUSH PRIVILEGES;
   ```

### Alternative: Use a GUI Tool

**MySQL Workbench** (Recommended):
1. Download from: https://dev.mysql.com/downloads/workbench/
2. Connect to your local MySQL instance
3. Create database and run scripts visually

**phpMyAdmin** (Web-based):
1. Download XAMPP or MAMP (includes phpMyAdmin)
2. Access via http://localhost/phpmyadmin
3. Create database and import SQL files

## 🧪 Testing the Setup

### Test Database Connection

Create a simple test file to verify everything works:

```php
<?php
// test_connection.php
require_once 'php/config.php';

try {
    $db = Database::getInstance()->getConnection();
    echo "✅ Database connection successful!\n";
    
    // Test query
    $stmt = $db->query("SELECT COUNT(*) as count FROM users");
    $result = $stmt->fetch();
    echo "👥 Users in database: " . $result['count'] . "\n";
    
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
}
?>
```

Run with: `php test_connection.php`

## 📋 Quick Checklist

- [ ] MySQL service is running
- [ ] Database `closet_matrix_db` is created  
- [ ] User `closet_user` has permissions
- [ ] All tables from `database_setup.sql` are created
- [ ] `php/config.php` has correct database credentials
- [ ] Test connection works

## 🔐 Security Notes

- Use a strong password for database user
- Don't use root user for application connections
- In production, use different credentials than development
- Never commit database passwords to git

## 📞 Next Steps

Once MySQL is set up:
1. Test your registration page (`register.html`)
2. Create a test account
3. Try logging in (`login.html`)
4. Visit your profile page (`myprofile.php`)
5. Test editing colors and sizes

---

**Need help?** Let me know what error messages you see and I can help troubleshoot!
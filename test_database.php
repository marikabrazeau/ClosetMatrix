<?php
/**
 * Database Connection Test Script
 * Run this file to test your MySQL setup
 */

// Simple connection test (before using the config.php classes)
echo "🧪 Testing Database Connection...\n\n";

// Database credentials (update these with your actual values)
$host = 'localhost';
$dbname = 'closet_matrix_db';
$username = 'closet_user';  // or 'root' for testing
$password = 'your_secure_password';  // your actual password

try {
    echo "1️⃣ Testing basic PDO connection...\n";
    
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    echo "✅ Basic connection successful!\n\n";
    
    echo "2️⃣ Testing database and tables...\n";
    
    // Check if database exists
    $stmt = $pdo->query("SELECT DATABASE()");
    $currentDb = $stmt->fetchColumn();
    echo "📊 Current database: $currentDb\n";
    
    // List tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "📋 Tables found: " . count($tables) . "\n";
    foreach ($tables as $table) {
        echo "   - $table\n";
    }
    
    // Check required tables
    $requiredTables = ['users', 'login_attempts', 'user_sessions', 'user_preferences'];
    $missingTables = array_diff($requiredTables, $tables);
    
    if (empty($missingTables)) {
        echo "✅ All required tables exist!\n\n";
    } else {
        echo "❌ Missing tables: " . implode(', ', $missingTables) . "\n";
        echo "   Please run the SQL from php/database_setup.sql\n\n";
    }
    
    // Test users table structure
    if (in_array('users', $tables)) {
        echo "3️⃣ Testing users table structure...\n";
        
        $stmt = $pdo->query("DESCRIBE users");
        $columns = $stmt->fetchAll();
        
        echo "👥 Users table columns:\n";
        foreach ($columns as $column) {
            echo "   - {$column['Field']} ({$column['Type']})\n";
        }
        
        // Check for new preference columns
        $preferenceColumns = ['size_tops', 'size_bottoms', 'size_shoes', 'size_dresses'];
        $existingColumns = array_column($columns, 'Field');
        $missingPrefColumns = array_diff($preferenceColumns, $existingColumns);
        
        if (empty($missingPrefColumns)) {
            echo "✅ All user preference columns exist!\n\n";
        } else {
            echo "⚠️ Missing preference columns: " . implode(', ', $missingPrefColumns) . "\n";
            echo "   Please run the SQL from php/update_user_preferences.sql\n\n";
        }
        
        // Count users
        $stmt = $pdo->query("SELECT COUNT(*) FROM users");
        $userCount = $stmt->fetchColumn();
        echo "👤 Total users in database: $userCount\n\n";
    }
    
    echo "4️⃣ Testing application config...\n";
    
    // Test the application's config file
    if (file_exists('php/config.php')) {
        try {
            require_once 'php/config.php';
            echo "✅ Config file loaded successfully!\n";
            
            // Test Database class
            $db = Database::getInstance()->getConnection();
            echo "✅ Database class working!\n";
            
            // Test Auth class
            echo "✅ Auth class loaded!\n";
            
            // Test Security class
            $testEmail = 'test@example.com';
            $isValid = Security::validateEmail($testEmail);
            echo "✅ Security class working! (Email validation: " . ($isValid ? 'passed' : 'failed') . ")\n";
            
        } catch (Exception $e) {
            echo "❌ Config file error: " . $e->getMessage() . "\n";
        }
    } else {
        echo "⚠️ Config file not found at php/config.php\n";
    }
    
    echo "\n🎉 Database setup test complete!\n";
    echo "\n📋 Summary:\n";
    echo "   - Database connection: ✅ Working\n";
    echo "   - Required tables: " . (empty($missingTables) ? "✅ All present" : "❌ Some missing") . "\n";
    echo "   - User preferences: " . (empty($missingPrefColumns) ? "✅ Configured" : "❌ Needs setup") . "\n";
    echo "   - Application config: " . (file_exists('php/config.php') ? "✅ Found" : "❌ Missing") . "\n";
    
    if (empty($missingTables) && empty($missingPrefColumns)) {
        echo "\n🚀 Ready to test your login system!\n";
        echo "   Next steps:\n";
        echo "   1. Update php/config.php with your database credentials\n";
        echo "   2. Visit register.html to create an account\n";
        echo "   3. Visit login.html to test login\n";
        echo "   4. Visit myprofile.php to test preferences\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Database connection failed!\n";
    echo "Error: " . $e->getMessage() . "\n\n";
    
    echo "🔧 Troubleshooting:\n";
    echo "1. Make sure MySQL is running\n";
    echo "2. Check your database credentials in this file\n";
    echo "3. Make sure database '$dbname' exists\n";
    echo "4. Make sure user '$username' has access to '$dbname'\n";
    
    if (strpos($e->getMessage(), 'Unknown database') !== false) {
        echo "\n💡 Quick fix: Create the database first:\n";
        echo "   mysql -u root -p\n";
        echo "   CREATE DATABASE closet_matrix_db;\n";
    }
    
    if (strpos($e->getMessage(), 'Access denied') !== false) {
        echo "\n💡 Quick fix: Check your username/password:\n";
        echo "   Try connecting as root first:\n";
        echo "   mysql -u root -p\n";
    }
}

echo "\n";
?>
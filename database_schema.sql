-- Closet Matrix Database Schema
-- Run this after creating the database and user

USE closet_matrix_db;

-- Users table for authentication and profile data
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    is_active BOOLEAN DEFAULT TRUE,
    
    -- User preferences for sizing
    size_tops VARCHAR(10) DEFAULT NULL,
    size_bottoms VARCHAR(10) DEFAULT NULL,
    size_shoes VARCHAR(10) DEFAULT NULL,
    size_dresses VARCHAR(10) DEFAULT NULL,
    
    -- Profile preferences
    preferred_colors TEXT DEFAULT NULL,
    style_preferences TEXT DEFAULT NULL
);

-- Login attempts table for security
CREATE TABLE IF NOT EXISTS login_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    attempted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    successful BOOLEAN DEFAULT FALSE,
    user_agent TEXT DEFAULT NULL
);

-- User sessions table
CREATE TABLE IF NOT EXISTS user_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    session_token VARCHAR(255) UNIQUE NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent TEXT DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Clothing items table
CREATE TABLE IF NOT EXISTS clothing_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    category ENUM('tops', 'bottoms', 'dresses', 'outerwear', 'shoes', 'accessories') NOT NULL,
    brand VARCHAR(50) DEFAULT NULL,
    color VARCHAR(50) DEFAULT NULL,
    size VARCHAR(10) DEFAULT NULL,
    purchase_price DECIMAL(8,2) DEFAULT NULL,
    purchase_date DATE DEFAULT NULL,
    notes TEXT DEFAULT NULL,
    image_url VARCHAR(255) DEFAULT NULL,
    times_worn INT DEFAULT 0,
    last_worn DATE DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Outfits table
CREATE TABLE IF NOT EXISTS outfits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    occasion VARCHAR(50) DEFAULT NULL,
    season ENUM('spring', 'summer', 'fall', 'winter', 'all') DEFAULT 'all',
    notes TEXT DEFAULT NULL,
    image_url VARCHAR(255) DEFAULT NULL,
    times_worn INT DEFAULT 0,
    last_worn DATE DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Outfit items junction table
CREATE TABLE IF NOT EXISTS outfit_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    outfit_id INT NOT NULL,
    clothing_item_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (outfit_id) REFERENCES outfits(id) ON DELETE CASCADE,
    FOREIGN KEY (clothing_item_id) REFERENCES clothing_items(id) ON DELETE CASCADE,
    UNIQUE KEY unique_outfit_item (outfit_id, clothing_item_id)
);

-- Calendar events table for outfit planning
CREATE TABLE IF NOT EXISTS calendar_events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    event_date DATE NOT NULL,
    event_name VARCHAR(100) NOT NULL,
    outfit_id INT DEFAULT NULL,
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (outfit_id) REFERENCES outfits(id) ON DELETE SET NULL
);

-- Indexes for better performance
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_username ON users(username);
CREATE INDEX idx_login_attempts_email ON login_attempts(email);
CREATE INDEX idx_login_attempts_attempted_at ON login_attempts(attempted_at);
CREATE INDEX idx_sessions_token ON user_sessions(session_token);
CREATE INDEX idx_sessions_expires ON user_sessions(expires_at);
CREATE INDEX idx_clothing_user_category ON clothing_items(user_id, category);
CREATE INDEX idx_outfits_user ON outfits(user_id);
CREATE INDEX idx_calendar_user_date ON calendar_events(user_id, event_date);

-- Insert a sample admin user (password: 'admin123' hashed with PHP's password_hash())
-- You should change this password immediately after setup
INSERT IGNORE INTO users (username, email, password_hash, first_name, last_name) 
VALUES (
    'admin', 
    'admin@closetmatrix.local', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
    'Admin', 
    'User'
);

-- Show tables created
SHOW TABLES;

-- Show sample data
SELECT COUNT(*) as total_users FROM users;
SELECT COUNT(*) as total_tables FROM information_schema.tables WHERE table_schema = 'closet_matrix_db';

DELIMITER //
CREATE PROCEDURE GetDatabaseInfo()
BEGIN
    SELECT 'Database setup completed successfully!' as message;
    SELECT DATABASE() as current_database;
    SELECT COUNT(*) as total_tables FROM information_schema.tables WHERE table_schema = DATABASE();
    SELECT COUNT(*) as total_users FROM users;
END //
DELIMITER ;

CALL GetDatabaseInfo();
DROP PROCEDURE GetDatabaseInfo;
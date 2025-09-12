<?php
/**
 * Protected Main Application Page
 * Requires user authentication
 */

// Check if user is authenticated
require_once 'php/check-auth.php';

// Get current user information
$currentUser = Auth::getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Closet Matrix - Organize and manage your personal wardrobe with style">
    <title>Closet Matrix - Personal Wardrobe Organizer</title>
    
    <!-- Link to external CSS file -->
    <link rel="stylesheet" href="aritziastyle.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="nav-container">
            <a href="#home" class="logo">Closet Matrix</a>
            
            <ul class="nav-menu">
                <li><a href="#wardrobe">Wardrobe</a></li>
                <li><a href="#outfits">Outfits</a></li>
                <li><a href="calendar.html">Calendar</a></li>
                <li><a href="#stats">Stats</a></li>
            </ul>
            
            <div class="nav-icons">
                <a href="#" aria-label="Search">🔍</a>
                <a href="#" aria-label="Add Item">➕</a>
                <a href="myprofile.html" aria-label="Profile">👤</a>
                <a href="php/logout.php" aria-label="Logout" title="Logout">🚪</a>
            </div>
        </nav>
    </header>

    <!-- Welcome Message for Logged In User -->
    <div class="user-welcome">
        <p>Welcome back, <?php echo htmlspecialchars($currentUser['first_name']); ?>! 👋</p>
    </div>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-content">
            <h1>Your Digital Closet</h1>
            <p class="subtitle">Organize your wardrobe with elegance and simplicity. Keep track of what you own, plan outfits, and make the most of your style.</p>
            <a href="#wardrobe" class="cta-button">View My Wardrobe</a>
        </div>
    </section>

    <!-- Main Content -->
    <main>
        <!-- Categories Section -->
        <section id="wardrobe" class="section">
            <h2 class="section-title">Browse Categories</h2>
            <p class="section-subtitle">Organize your clothing by category to easily find what you're looking for</p>
            
            <div class="category-grid">
            <a href="dresses.html" style="text-decoration: none; color: inherit;">
                <div class="category-card">
                     <span class="category-icon">
                        <img src="dresses.png" alt="Dresses" class="category-icon-img">
                    </span>
                    <div class="category-name">Dresses</div>
                    <div class="category-count">12 items</div>
                </div>
            </a>

            <a href="sweaters.html" style="text-decoration: none; color: inherit;">
                <div class="category-card">
                    <span class="category-icon">
                        <img src="sweaters.png" alt="sweaters" class="category-icon-img">
                    </span>
                    <div class="category-name">Sweaters</div>
                    <div class="category-count">8 items</div>
                </div>
            </a>

            <a href="tops.html" style="text-decoration: none; color: inherit;">
                <div class="category-card">
                    <span class="category-icon">👕</span>
                    <div class="category-name">Tops</div>
                    <div class="category-count">24 items</div>
                </div>
            </a>

            <a href="bottoms.html" style="text-decoration: none; color: inherit;">
                <div class="category-card">
                    <span class="category-icon">👖</span>
                    <div class="category-name">Bottoms</div>
                    <div class="category-count">16 items</div>
                </div>
            </a>

            <a href="outerwear.html" style="text-decoration: none; color: inherit;">
                <div class="category-card">
                    <span class="category-icon">🧥</span>
                    <div class="category-name">Outerwear</div>
                    <div class="category-count">7 items</div>
                </div>
            </a>

            <a href="shoes.html" style="text-decoration: none; color: inherit;">
                <div class="category-card">
                    <span class="category-icon">👠</span>
                    <div class="category-name">Shoes</div>
                    <div class="category-count">18 items</div>
                </div>
            </a>
            </div>
        </section>

        <!-- Stats Section -->
        <section id="stats" class="stats-section">
            <h2 class="section-title">Your Wardrobe Statistics</h2>
            <div class="stats-grid">
                <div class="stat-item">
                    <h3>85</h3>
                    <p>Total Items</p>
                </div>
                <div class="stat-item">
                    <h3>23</h3>
                    <p>Outfits Created</p>
                </div>
                <div class="stat-item">
                    <h3>$2,340</h3>
                    <p>Total Value</p>
                </div>
                <div class="stat-item">
                    <h3>15</h3>
                    <p>Most Worn</p>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="features-section">
            <h2 class="section-title">Smart Wardrobe Management</h2>
            <div class="features-grid">
                <div class="feature-item">
                    <span class="feature-icon">📱</span>
                    <h3>Digital Inventory</h3>
                    <p>Keep a digital record of all your clothing items with photos, brands, colors, and purchase details.</p>
                </div>

                <div class="feature-item">
                    <span class="feature-icon">✨</span>
                    <h3>Outfit Planning</h3>
                    <p>Create and save outfit combinations for different occasions. Never run out of style inspiration.</p>
                </div>

                <div class="feature-item">
                    <span class="feature-icon">📊</span>
                    <h3>Wear Analytics</h3>
                    <p>Track which items you wear most often and discover patterns in your style preferences.</p>
                </div>
            </div>
        </section>

        <!-- Action Section -->
        <section class="action-section">
            <h2>Ready to organize your wardrobe?</h2>
            <p>Start building your digital closet today and discover a new way to manage your style.</p>
            <div class="action-buttons">
                <a href="#" class="btn-primary">Add First Item</a>
                <a href="#" class="btn-secondary">Create Outfit</a>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 Closet Matrix. Organize your style with elegance.</p>
        <div class="footer-links">
            <a href="#">About</a>
            <a href="#">Help</a>
            <a href="#">Privacy</a>
            <a href="#">Terms</a>
            <a href="php/logout.php">Logout</a>
        </div>
    </footer>

    <!-- Simple JavaScript for smooth scrolling -->
    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.querySelector('.header');
            if (window.scrollY > 50) {
                header.style.background = 'rgba(254, 254, 254, 0.98)';
                header.style.boxShadow = '0 2px 15px rgba(0,0,0,0.08)';
            } else {
                header.style.background = 'rgba(254, 254, 254, 0.95)';
                header.style.boxShadow = 'none';
            }
        });
    </script>

    <style>
        /* Welcome message styling */
        .user-welcome {
            background: var(--cream);
            padding: 15px;
            text-align: center;
            margin-top: 70px;
            border-bottom: 1px solid var(--border-gray);
        }
        
        .user-welcome p {
            margin: 0;
            color: var(--text-primary);
            font-weight: 500;
        }
    </style>
</body>
</html>
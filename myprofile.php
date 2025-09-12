<?php
/**
 * User Profile Page
 * Requires authentication
 */

require_once 'php/check-auth.php';

// Get current user information
$currentUser = Auth::getCurrentUser();
$userInitials = strtoupper(substr($currentUser['first_name'], 0, 1) . substr($currentUser['last_name'], 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="My Profile - Digital Closet Management">
    <title>My Profile - Closet Matrix</title>
    
    <!-- Link to external CSS file -->
    <link rel="stylesheet" href="aritziastyle.css">
    
    <!-- Favicon -->
    <!-- Favicon removed to fix HTTPS security warning -->
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="nav-container">
            <a href="index.php" class="logo">Closet Matrix</a>
            
            <ul class="nav-menu">
                <li><a href="index.php#wardrobe">Wardrobe</a></li>
                <li><a href="#outfits">Outfits</a></li>
                <li><a href="calendar.html">Calendar</a></li>
                <li><a href="index.php#stats">Stats</a></li>
            </ul>
            
            <div class="nav-icons">
                <a href="#" aria-label="Search">🔍</a>
                <a href="#" aria-label="Add Item">➕</a>
                <a href="myprofile.php" aria-label="Profile">👤</a>
                <a href="php/logout.php" aria-label="Logout">🚪</a>
            </div>
        </nav>
    </header>

    <!-- Profile Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <div class="profile-avatar">
                <div class="avatar-placeholder">
                    <span class="avatar-initials"><?php echo $userInitials; ?></span>
                </div>
            </div>
            <h1><?php echo htmlspecialchars($currentUser['first_name'] . ' ' . $currentUser['last_name']); ?></h1>
            <p class="subtitle">Style enthusiast • Digital closet curator • Fashion planner</p>
            <a href="#edit-profile" class="cta-button">Edit Profile</a>
        </div>
    </section>

    <!-- Main Content -->
    <main>
        <!-- Profile Overview Section -->
        <section class="section">
            <div class="profile-overview">
                <div class="overview-grid">
                    <div class="overview-card">
                        <h3>Personal Style</h3>
                        <div class="style-tags" id="style-tags">
                            <!-- Will be populated by JavaScript -->
                        </div>
                    </div>
                    
                    <div class="overview-card">
                        <div class="card-header">
                            <h3>Favorite Colors</h3>
                            <button class="edit-btn" onclick="toggleColorEdit()">✏️</button>
                        </div>
                        <div class="color-palette" id="color-display">
                            <!-- Will be populated by JavaScript -->
                        </div>
                        <div class="color-editor" id="color-editor" style="display: none;">
                            <div class="color-grid">
                                <div class="color-option" style="background-color: #000000;" data-color="#000000" title="Black"></div>
                                <div class="color-option" style="background-color: #ffffff; border: 1px solid #ddd;" data-color="#ffffff" title="White"></div>
                                <div class="color-option" style="background-color: #8b4513;" data-color="#8b4513" title="Brown"></div>
                                <div class="color-option" style="background-color: #2e4b3a;" data-color="#2e4b3a" title="Forest Green"></div>
                                <div class="color-option" style="background-color: #4a5568;" data-color="#4a5568" title="Charcoal"></div>
                                <div class="color-option" style="background-color: #e53e3e;" data-color="#e53e3e" title="Red"></div>
                                <div class="color-option" style="background-color: #3182ce;" data-color="#3182ce" title="Blue"></div>
                                <div class="color-option" style="background-color: #38a169;" data-color="#38a169" title="Green"></div>
                                <div class="color-option" style="background-color: #d69e2e;" data-color="#d69e2e" title="Gold"></div>
                                <div class="color-option" style="background-color: #805ad5;" data-color="#805ad5" title="Purple"></div>
                                <div class="color-option" style="background-color: #e2e8f0;" data-color="#e2e8f0" title="Light Gray"></div>
                                <div class="color-option" style="background-color: #a0aec0;" data-color="#a0aec0" title="Gray"></div>
                                <div class="color-option" style="background-color: #ffd6cc;" data-color="#ffd6cc" title="Peach"></div>
                                <div class="color-option" style="background-color: #c6f6d5;" data-color="#c6f6d5" title="Mint"></div>
                                <div class="color-option" style="background-color: #bee3f8;" data-color="#bee3f8" title="Light Blue"></div>
                                <div class="color-option" style="background-color: #f7fafc;" data-color="#f7fafc" title="Off White"></div>
                            </div>
                            <div class="color-editor-actions">
                                <p class="helper-text">Select up to 5 favorite colors</p>
                                <button class="btn-small" onclick="saveColorPreferences()">Save Colors</button>
                                <button class="btn-small secondary" onclick="cancelColorEdit()">Cancel</button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="overview-card">
                        <div class="card-header">
                            <h3>Size Profile</h3>
                            <button class="edit-btn" onclick="toggleSizeEdit()">✏️</button>
                        </div>
                        <div class="size-info" id="size-display">
                            <!-- Will be populated by JavaScript -->
                        </div>
                        <div class="size-editor" id="size-editor" style="display: none;">
                            <div class="size-form">
                                <div class="size-input-group">
                                    <label>Tops</label>
                                    <select class="size-select" data-size="tops">
                                        <option value="XS">XS</option>
                                        <option value="S">S</option>
                                        <option value="M">M</option>
                                        <option value="L">L</option>
                                        <option value="XL">XL</option>
                                        <option value="XXL">XXL</option>
                                    </select>
                                </div>
                                <div class="size-input-group">
                                    <label>Dresses</label>
                                    <select class="size-select" data-size="dresses">
                                        <option value="XS">XS</option>
                                        <option value="S">S</option>
                                        <option value="M">M</option>
                                        <option value="L">L</option>
                                        <option value="XL">XL</option>
                                        <option value="XXL">XXL</option>
                                    </select>
                                </div>
                                <div class="size-input-group">
                                    <label>Bottoms</label>
                                    <select class="size-select" data-size="bottoms">
                                        <option value="0">0</option>
                                        <option value="2">2</option>
                                        <option value="4">4</option>
                                        <option value="6">6</option>
                                        <option value="8">8</option>
                                        <option value="10">10</option>
                                        <option value="12">12</option>
                                        <option value="14">14</option>
                                        <option value="16">16</option>
                                    </select>
                                </div>
                                <div class="size-input-group">
                                    <label>Shoes</label>
                                    <select class="size-select" data-size="shoes">
                                        <option value="5">5</option>
                                        <option value="5.5">5.5</option>
                                        <option value="6">6</option>
                                        <option value="6.5">6.5</option>
                                        <option value="7">7</option>
                                        <option value="7.5">7.5</option>
                                        <option value="8">8</option>
                                        <option value="8.5">8.5</option>
                                        <option value="9">9</option>
                                        <option value="9.5">9.5</option>
                                        <option value="10">10</option>
                                        <option value="10.5">10.5</option>
                                        <option value="11">11</option>
                                    </select>
                                </div>
                            </div>
                            <div class="size-editor-actions">
                                <button class="btn-small" onclick="saveSizePreferences()">Save Sizes</button>
                                <button class="btn-small secondary" onclick="cancelSizeEdit()">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Personal Stats Section -->
        <section class="stats-section">
            <h2 class="section-title">My Closet Analytics</h2>
            <div class="stats-grid">
                <div class="stat-item">
                    <h3>85</h3>
                    <p>Total Items</p>
                </div>
                <div class="stat-item">
                    <h3>$2,340</h3>
                    <p>Total Investment</p>
                </div>
                <div class="stat-item">
                    <h3>23</h3>
                    <p>Outfit Combinations</p>
                </div>
                <div class="stat-item">
                    <h3>4.2</h3>
                    <p>Avg Weekly Wears</p>
                </div>
            </div>
        </section>

        <!-- Recent Activity Section -->
        <section class="section">
            <h2 class="section-title">Recent Activity</h2>
            <p class="section-subtitle">Your latest closet updates and outfit creations</p>
            
            <div class="activity-feed">
                <div class="activity-item">
                    <div class="activity-icon">➕</div>
                    <div class="activity-content">
                        <h4>Added new item</h4>
                        <p>Black wool blazer from Aritzia</p>
                        <span class="activity-time">2 hours ago</span>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon">✨</div>
                    <div class="activity-content">
                        <h4>Created outfit</h4>
                        <p>"Business Casual Monday"</p>
                        <span class="activity-time">1 day ago</span>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon">👗</div>
                    <div class="activity-content">
                        <h4>Wore outfit</h4>
                        <p>Navy midi dress with camel coat</p>
                        <span class="activity-time">3 days ago</span>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon">📊</div>
                    <div class="activity-content">
                        <h4>Generated report</h4>
                        <p>Monthly wardrobe analysis</p>
                        <span class="activity-time">1 week ago</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Favorite Brands Section -->
        <section class="section" style="background: var(--light-gray);">
            <h2 class="section-title">Favorite Brands</h2>
            <div class="brands-grid">
                <div class="brand-item">
                    <div class="brand-logo">A</div>
                    <span class="brand-name">Aritzia</span>
                    <span class="brand-count">12 items</span>
                </div>
                <div class="brand-item">
                    <div class="brand-logo">E</div>
                    <span class="brand-name">Everlane</span>
                    <span class="brand-count">8 items</span>
                </div>
                <div class="brand-item">
                    <div class="brand-logo">Z</div>
                    <span class="brand-name">Zara</span>
                    <span class="brand-count">15 items</span>
                </div>
                <div class="brand-item">
                    <div class="brand-logo">U</div>
                    <span class="brand-name">Uniqlo</span>
                    <span class="brand-count">6 items</span>
                </div>
            </div>
        </section>

        <!-- Goals Section -->
        <section class="section">
            <h2 class="section-title">Style Goals</h2>
            <div class="goals-grid">
                <div class="goal-item">
                    <div class="goal-progress">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 75%;"></div>
                        </div>
                    </div>
                    <h4>Build Capsule Wardrobe</h4>
                    <p>Focus on quality basics • 30/40 items</p>
                </div>
                
                <div class="goal-item">
                    <div class="goal-progress">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 45%;"></div>
                        </div>
                    </div>
                    <h4>Sustainable Shopping</h4>
                    <p>Buy less, choose better • 9/20 conscious purchases</p>
                </div>
                
                <div class="goal-item">
                    <div class="goal-progress">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 60%;"></div>
                        </div>
                    </div>
                    <h4>Outfit Variety</h4>
                    <p>Create 50 unique combinations • 30/50 outfits</p>
                </div>
            </div>
        </section>

        <!-- Settings Section -->
        <section class="section" id="edit-profile" style="background: var(--cream);">
            <h2 class="section-title">Profile Settings</h2>
            <div class="settings-grid">
                <div class="setting-group">
                    <h3>Personal Information</h3>
                    <div class="setting-item">
                        <label>Display Name</label>
                        <input type="text" value="<?php echo htmlspecialchars($currentUser['first_name'] . ' ' . $currentUser['last_name']); ?>" class="setting-input">
                    </div>
                    <div class="setting-item">
                        <label>Style Bio</label>
                        <textarea class="setting-textarea" rows="3">Style enthusiast • Digital closet curator • Fashion planner</textarea>
                    </div>
                </div>
                
                <div class="setting-group">
                    <h3>Preferences</h3>
                    <div class="setting-item">
                        <label>Currency</label>
                        <select class="setting-select">
                            <option value="CAD" selected>CAD ($)</option>
                            <option value="USD">USD ($)</option>
                            <option value="EUR">EUR (€)</option>
                        </select>
                    </div>
                    <div class="setting-item">
                        <label>Size System</label>
                        <select class="setting-select">
                            <option value="US" selected>US/Canada</option>
                            <option value="EU">European</option>
                            <option value="UK">UK</option>
                        </select>
                    </div>
                </div>
                
                <div class="setting-group">
                    <h3>Notifications</h3>
                    <div class="setting-toggle">
                        <label class="toggle-label">
                            <input type="checkbox" checked>
                            <span class="toggle-slider"></span>
                            Outfit reminders
                        </label>
                    </div>
                    <div class="setting-toggle">
                        <label class="toggle-label">
                            <input type="checkbox" checked>
                            <span class="toggle-slider"></span>
                            Weekly analytics
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="settings-actions">
                <button class="btn-primary">Save Changes</button>
                <button class="btn-secondary">Export Data</button>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 Closet Matrix. Organize your style with elegance.</p>
        <div class="footer-links">
            <a href="index.php">Home</a>
            <a href="#">About</a>
            <a href="#">Help</a>
            <a href="#">Privacy</a>
            <a href="#">Terms</a>
            <a href="php/logout.php">Logout</a>
        </div>
    </footer>

    <script>
        // Global variables
        let selectedColors = [];
        let isColorEditMode = false;
        let isSizeEditMode = false;
        let originalSizes = {};
        let userPreferences = {};

        // Load user preferences when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadUserPreferences();
            initializeEventListeners();
        });

        function loadUserPreferences() {
            fetch('php/get-preferences.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        userPreferences = data;
                        selectedColors = data.colors;
                        
                        // Update color display
                        updateColorDisplay();
                        
                        // Update size display
                        updateSizeDisplay(data.sizes);
                        
                        // Update style tags
                        updateStyleTags(data.style_preferences);
                    }
                })
                .catch(error => {
                    console.error('Failed to load preferences:', error);
                    showProfileMessage('Failed to load preferences', 'error');
                });
        }

        function updateColorDisplay() {
            const colorDisplay = document.getElementById('color-display');
            colorDisplay.innerHTML = '';
            
            selectedColors.forEach(color => {
                const swatch = document.createElement('div');
                swatch.className = 'color-swatch active';
                swatch.style.backgroundColor = color;
                swatch.setAttribute('data-color', color);
                if (color === '#ffffff' || color === '#f7fafc') {
                    swatch.style.border = '1px solid #e5e5e5';
                }
                colorDisplay.appendChild(swatch);
            });
        }

        function updateSizeDisplay(sizes) {
            const sizeDisplay = document.getElementById('size-display');
            sizeDisplay.innerHTML = `
                <div class="size-item">
                    <span class="size-label">Tops:</span>
                    <span class="size-value" data-size="tops">${sizes.tops}</span>
                </div>
                <div class="size-item">
                    <span class="size-label">Dresses:</span>
                    <span class="size-value" data-size="dresses">${sizes.dresses}</span>
                </div>
                <div class="size-item">
                    <span class="size-label">Bottoms:</span>
                    <span class="size-value" data-size="bottoms">${sizes.bottoms}</span>
                </div>
                <div class="size-item">
                    <span class="size-label">Shoes:</span>
                    <span class="size-value" data-size="shoes">${sizes.shoes}</span>
                </div>
            `;
        }

        function updateStyleTags(stylePrefArray) {
            const styleTags = document.getElementById('style-tags');
            styleTags.innerHTML = '';
            
            stylePrefArray.forEach(style => {
                const tag = document.createElement('span');
                tag.className = 'style-tag';
                tag.textContent = style;
                styleTags.appendChild(tag);
            });
        }

        function initializeEventListeners() {
            // Color option click handlers
            const colorOptions = document.querySelectorAll('.color-option');
            colorOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const color = this.getAttribute('data-color');
                    
                    if (selectedColors.includes(color)) {
                        selectedColors = selectedColors.filter(c => c !== color);
                        this.classList.remove('selected');
                    } else if (selectedColors.length < 5) {
                        selectedColors.push(color);
                        this.classList.add('selected');
                    } else {
                        alert('You can select up to 5 colors');
                    }
                });
            });
        }

        // Color Profile Editing Functions
        function toggleColorEdit() {
            const display = document.getElementById('color-display');
            const editor = document.getElementById('color-editor');
            
            if (isColorEditMode) {
                display.style.display = 'flex';
                editor.style.display = 'none';
                isColorEditMode = false;
            } else {
                display.style.display = 'none';
                editor.style.display = 'block';
                isColorEditMode = true;
                updateColorSelection();
            }
        }

        function updateColorSelection() {
            const colorOptions = document.querySelectorAll('.color-option');
            colorOptions.forEach(option => {
                const color = option.getAttribute('data-color');
                if (selectedColors.includes(color)) {
                    option.classList.add('selected');
                } else {
                    option.classList.remove('selected');
                }
            });
        }

        function saveColorPreferences() {
            fetch('php/update-preferences.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    type: 'colors',
                    colors: selectedColors
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateColorDisplay();
                    showProfileMessage('Colors updated successfully!', 'success');
                } else {
                    showProfileMessage('Failed to update colors', 'error');
                }
            })
            .catch(error => {
                showProfileMessage('Error updating colors', 'error');
            });

            toggleColorEdit();
        }

        function cancelColorEdit() {
            selectedColors = userPreferences.colors || [];
            toggleColorEdit();
        }

        // Size Profile Editing Functions
        function toggleSizeEdit() {
            const display = document.getElementById('size-display');
            const editor = document.getElementById('size-editor');
            
            if (isSizeEditMode) {
                display.style.display = 'block';
                editor.style.display = 'none';
                isSizeEditMode = false;
            } else {
                const sizeValues = document.querySelectorAll('.size-value');
                sizeValues.forEach(value => {
                    const sizeType = value.getAttribute('data-size');
                    originalSizes[sizeType] = value.textContent;
                });

                display.style.display = 'none';
                editor.style.display = 'block';
                isSizeEditMode = true;
                loadCurrentSizes();
            }
        }

        function loadCurrentSizes() {
            const sizeSelects = document.querySelectorAll('.size-select');
            sizeSelects.forEach(select => {
                const sizeType = select.getAttribute('data-size');
                const currentValue = originalSizes[sizeType];
                if (currentValue) {
                    select.value = currentValue;
                }
            });
        }

        function saveSizePreferences() {
            const sizeSelects = document.querySelectorAll('.size-select');
            const newSizes = {};
            
            sizeSelects.forEach(select => {
                const sizeType = select.getAttribute('data-size');
                newSizes[sizeType] = select.value;
            });

            fetch('php/update-preferences.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    type: 'sizes',
                    sizes: newSizes
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateSizeDisplay(newSizes);
                    showProfileMessage('Sizes updated successfully!', 'success');
                } else {
                    showProfileMessage('Failed to update sizes', 'error');
                }
            })
            .catch(error => {
                showProfileMessage('Error updating sizes', 'error');
            });

            toggleSizeEdit();
        }

        function cancelSizeEdit() {
            toggleSizeEdit();
        }

        // Show profile messages
        function showProfileMessage(message, type) {
            let messageContainer = document.getElementById('profile-message');
            if (!messageContainer) {
                messageContainer = document.createElement('div');
                messageContainer.id = 'profile-message';
                messageContainer.className = 'profile-message';
                document.querySelector('.profile-overview').appendChild(messageContainer);
            }

            messageContainer.textContent = message;
            messageContainer.className = `profile-message ${type}`;
            messageContainer.style.display = 'block';

            setTimeout(() => {
                messageContainer.style.display = 'none';
            }, 3000);
        }

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

        // Settings save functionality
        document.querySelector('.settings-actions .btn-primary').addEventListener('click', function() {
            this.textContent = 'Saved!';
            this.style.background = 'var(--medium-gray)';
            setTimeout(() => {
                this.textContent = 'Save Changes';
                this.style.background = 'var(--primary-black)';
            }, 2000);
        });
    </script>
</body>
</html>
// ===== ATHLETIQO PREMIUM JAVASCRIPT =====
// ===== THEME SYSTEM =====
function initializeTheme() {
    const savedTheme = localStorage.getItem('athletiqoTheme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);
    updateThemeIcon(savedTheme);
}

function toggleTheme() {
    const currentTheme = document.documentElement.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    document.documentElement.setAttribute('data-theme', newTheme);
    localStorage.setItem('athletiqoTheme', newTheme);
    updateThemeIcon(newTheme);
    
    showNotification(`Switched to ${newTheme} mode`, 'success');
}

function updateThemeIcon(theme) {
    const themeToggle = document.querySelector('.theme-toggle');
    if (!themeToggle) return;
    
    const icon = themeToggle.querySelector('svg');
    if (theme === 'dark') {
        icon.innerHTML = '<path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>';
    } else {
        icon.innerHTML = '<circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>';
    }
}
// ===== MOBILE MENU =====
function initializeMobileMenu() {
    const mobileToggle = document.querySelector('.mobile-menu-toggle');
    const navContainer = document.querySelector('.nav-container');
    
    if (mobileToggle && navContainer) {
        mobileToggle.addEventListener('click', () => {
            mobileToggle.classList.toggle('active');
            navContainer.classList.toggle('active');
        });
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!mobileToggle.contains(e.target) && !navContainer.contains(e.target)) {
                mobileToggle.classList.remove('active');
                navContainer.classList.remove('active');
            }
        });
    }
}

// ===== ENHANCED NOTIFICATION SYSTEM =====
function showNotification(message, type = 'info', duration = 3000) {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notification => notification.remove());
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <div style="font-size: 1.2rem;">
                ${type === 'success' ? '✓' : type === 'error' ? '✕' : type === 'warning' ? '!' : 'ℹ'}
            </div>
            <div>${message}</div>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after duration
    setTimeout(() => {
        if (notification.parentNode) {
            notification.style.animation = 'slideInRight 0.3s ease reverse';
            setTimeout(() => {
                if (notification.parentNode) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }
    }, duration);
}

// ===== PRODUCT DATABASE =====
const products = [
    {
        id: 1,
        name: "Basketball BT900 - Size 7",
        category: "equipment",
        subcategory: "basketball",
        price: 1999.00,
        image: "https://picsum.photos/seed/basketball/400/300.jpg",
        buyLink: "https://www.decathlon.in/p/8648080/basketball-bt900-size-7fiba-approved-for-boys-and-adults",
        keywords: ["basketball", "ball", "sports", "equipment"]
    },
    {
        id: 2,
        name: "Cult Men's Running Shoes-Off White",
        category: "wear",
        subcategory: "shoes",
        price: 2299.00,
        image: "https://picsum.photos/seed/running-shoes/400/300.jpg",
        buyLink: "https://cultstore.com/products/cult-men-s-traverse-running-shoes-off-white",
        keywords: ["shoes", "running", "footwear", "sports", "wear"]
    },
    {
        id: 3,
        name: "SS Ikon Kashmir Willow Cricket Bat",
        category: "equipment",
        subcategory: "cricket",
        price: 2654.00,
        image: "https://picsum.photos/seed/cricket-bat/400/300.jpg",
        buyLink: "https://www.sstoncricket.com/ss-ikon-kashmir-willow-cricket-bat-sh.html",
        keywords: ["cricket", "bat", "sports", "equipment"]
    },
    {
        id: 4,
        name: "Gym Weight Training Station Full Body Workout at Home",
        category: "equipment",
        subcategory: "gym",
        price: 35999.00,
        image: "https://picsum.photos/seed/gym-equipment/400/300.jpg",
        buyLink: "https://www.decathlon.in/p/8484134/gym-weight-training-station-full-body-workout-at-home-home-gym-900-black",
        keywords: ["gym", "workout", "training", "equipment", "fitness"]
    },
    {
        id: 5,
        name: "Boldfit Adjustable Hand Grip",
        category: "equipment",
        subcategory: "gym",
        price: 299.00,
        image: "https://picsum.photos/seed/hand-grip/400/300.jpg",
        buyLink: "https://amzn.in/d/04hoirIg",
        keywords: ["hand", "grip", "gym", "fitness", "equipment"]
    },
    {
        id: 6,
        name: "DOMYOS - Men Gym Trackpant Convertible",
        category: "wear",
        subcategory: "trackpant",
        price: 999.00,
        image: "https://picsum.photos/seed/trackpants/400/300.jpg",
        buyLink: "https://www.decathlon.in/p/8731703/men-gym-trackpant-convertible-jog-fit-quick-dry-zip-pockets-500-black",
        keywords: ["trackpant", "pants", "gym", "wear", "sports"]
    },
    {
        id: 7,
        name: "Reebok Unisex Ri Vector Knit Tracktop",
        category: "wear",
        subcategory: "tracktop",
        price: 1799.00,
        image: "https://picsum.photos/seed/tracktop/400/300.jpg",
        buyLink: "https://reebok.abfrl.in/p/reebok-unisex-ri-vector-knit-tracktop-957428.html?source=plp",
        keywords: ["tracktop", "jacket", "wear", "sports", "reebok"]
    },
    {
        id: 8,
        name: "Men's Full Sleeve Compression T-Shirt",
        category: "wear",
        subcategory: "tshirt",
        price: 899.00,
        image: "https://picsum.photos/seed/compression-shirt/400/300.jpg",
        buyLink: "https://www.amazon.in/FUAARK-Mens-Sleeve-Compression-T-Shirt/dp/B0C5SVCGM6?ref_=ast_sto_dp&th=1&psc=1",
        keywords: ["tshirt", "shirt", "compression", "wear", "sports"]
    }
];

// Shopping Cart
let cart = JSON.parse(localStorage.getItem('athletiqoCart')) || [];

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeTheme();
    initializeMobileMenu();
    initializeSearch();
    initializeCart();
    updateCartCount();
});

// Search Functionality
function initializeSearch() {
    const searchInput = document.getElementById('search-input');
    const searchButton = document.getElementById('search-button');
    
    if (searchInput && searchButton) {
        searchButton.addEventListener('click', performSearch);
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
    }
}

function performSearch() {
    const searchInput = document.getElementById('search-input');
    const query = searchInput.value.toLowerCase().trim();
    
    if (!query) {
        showAllProducts();
        return;
    }
    
    const results = products.filter(product => 
        product.name.toLowerCase().includes(query) ||
        product.keywords.some(keyword => keyword.toLowerCase().includes(query)) ||
        product.category.toLowerCase().includes(query) ||
        product.subcategory.toLowerCase().includes(query)
    );
    
    displaySearchResults(results, query);
}

function showAllProducts() {
    displaySearchResults(products, "All Products");
    // Update hero title to show we're displaying all products
    const heroTitle = document.querySelector('.hero h2');
    if (heroTitle) {
        heroTitle.textContent = 'All Products';
    }
}

function displaySearchResults(results, query) {
    // Check if we're on a page that has a products or category container
    let container = document.querySelector('.products');
    const sectionTitle = document.querySelector('.section-title');
    const heroTitle = document.querySelector('.hero h2');
    const mainSection = document.querySelector('section.container');
    
    // If no products container, check for category container (homepage)
    if (!container) {
        container = document.querySelector('.category');
    }
    
    // If still no container, create one on the current page
    if (!container && mainSection) {
        // Clear existing content and create products container
        mainSection.innerHTML = `
            <h2 class="section-title">Found ${results.length} result${results.length !== 1 ? 's' : ''} for "${query}"</h2>
            <div class="products"></div>
        `;
        container = document.querySelector('.products');
    }
    
    // Update section title
    const updatedSectionTitle = document.querySelector('.section-title');
    if (updatedSectionTitle) {
        updatedSectionTitle.textContent = `Found ${results.length} result${results.length !== 1 ? 's' : ''} for "${query}"`;
    }
    
    // Update hero title
    if (heroTitle) {
        heroTitle.textContent = 'Search Results';
    }
    
    // Get the container (products or category)
    if (!container) {
        showNotification('Please navigate to a product page to search');
        return;
    }
    
    // Change category div to products div for consistent styling
    if (container.classList.contains('category')) {
        container.classList.remove('category');
        container.classList.add('products');
        container.style.display = 'grid';
        container.style.gridTemplateColumns = 'repeat(auto-fit, minmax(250px, 1fr))';
        container.style.gap = '20px';
    }
    
    if (results.length === 0) {
        container.innerHTML = `
            <div style="grid-column: 1/-1; text-align: center; padding: 60px 20px; background: white; border-radius: 12px; box-shadow: 0 6px 20px rgba(0,0,0,0.08);">
                <div style="font-size: 60px; margin-bottom: 20px;">Search</div>
                <h3 style="color: #333; margin-bottom: 15px;">No products found</h3>
                <p style="color: #666; margin-bottom: 30px;">Try searching for different keywords or browse our categories</p>
                <button onclick="showAllProducts()" style="background: linear-gradient(135deg, #ff5722, #ff9800); padding: 12px 25px; border: none; border-radius: 25px; color: white; font-weight: 600; cursor: pointer;">
                    Show All Products
                </button>
            </div>
        `;
        return;
    }
    
    container.innerHTML = results.map(product => createProductCard(product)).join('');
}

function createProductCard(product) {
    const isInCart = cart.find(item => item.id === product.id);
    return `
        <div class="card">
            <img src="${product.image}" alt="${product.name}">
            <h3>${product.name}</h3>
            <div class="price">Rs ${product.price.toLocaleString('en-IN')}</div>
            <div style="display: flex; gap: 10px; justify-content: center; margin-top: 15px;">
                <button onclick="addToCart(${product.id})" style="background: linear-gradient(135deg, #4CAF50, #45a049); padding: 8px 16px; border: none; border-radius: 20px; color: white; font-weight: 600; cursor: pointer;">
                    ${isInCart ? 'In Cart' : 'Add to Cart'}
                </button>
                <a href="${product.buyLink}" target="_blank" style="text-decoration: none;">
                    <button style="background: linear-gradient(135deg, #111, #444); padding: 8px 16px; border: none; border-radius: 20px; color: white; font-weight: 600; cursor: pointer;">
                        Buy Now
                    </button>
                </a>
            </div>
        </div>
    `;
}

// Cart Functionality
function initializeCart() {
    // Add cart button click handlers
    const cartButton = document.querySelector('.cart-button');
    if (cartButton) {
        cartButton.addEventListener('click', showCart);
    }
}

function addToCart(productId) {
    const product = products.find(p => p.id === productId);
    if (!product) return;
    
    const existingItem = cart.find(item => item.id === productId);
    
    if (existingItem) {
        showNotification('Product already in cart!');
        return;
    }
    
    cart.push({
        ...product,
        quantity: 1
    });
    
    saveCart();
    updateCartCount();
    showNotification(`${product.name} added to cart!`);
    
    // Update the button if we're on a search results page
    const button = event.target;
    if (button && button.textContent === 'Add to Cart') {
        button.textContent = 'In Cart';
        button.style.background = 'linear-gradient(135deg, #ff9800, #ff5722)';
    }
}

function removeFromCart(productId) {
    cart = cart.filter(item => item.id !== productId);
    saveCart();
    updateCartCount();
    showCart(); // Refresh cart display
    showNotification('Product removed from cart');
}

function updateCartCount() {
    const cartButton = document.querySelector('.cart-button');
    if (cartButton) {
        const count = cart.reduce((total, item) => total + item.quantity, 0);
        cartButton.innerHTML = `Cart (${count})`;
    }
}

function saveCart() {
    localStorage.setItem('athletiqoCart', JSON.stringify(cart));
}

function showCart() {
    // Check if we're on a page that can display cart
    let container = document.querySelector('.products');
    const sectionTitle = document.querySelector('.section-title');
    const heroTitle = document.querySelector('.hero h2');
    const mainSection = document.querySelector('section.container');
    
    // If no products container, check for category container (homepage)
    if (!container) {
        container = document.querySelector('.category');
    }
    
    // If still no container, create one on the current page
    if (!container && mainSection) {
        // Clear existing content and create products container
        mainSection.innerHTML = `
            <h2 class="section-title">Your Shopping Cart</h2>
            <div class="products"></div>
        `;
        container = document.querySelector('.products');
    }
    
    // Update hero title
    if (heroTitle) {
        heroTitle.textContent = 'Shopping Cart';
    }
    
    // Update section title
    const updatedSectionTitle = document.querySelector('.section-title');
    if (updatedSectionTitle) {
        updatedSectionTitle.textContent = 'Your Shopping Cart';
    }
    
    // Get the container (products or category)
    if (!container) {
        showNotification('Please navigate to a product page to view cart');
        return;
    }
    
    // Change category div to products div for consistent styling
    if (container.classList.contains('category')) {
        container.classList.remove('category');
        container.classList.add('products');
        container.style.display = 'grid';
        container.style.gridTemplateColumns = 'repeat(auto-fit, minmax(250px, 1fr))';
        container.style.gap = '20px';
    }
    
    if (cart.length === 0) {
        container.innerHTML = `
            <div style="grid-column: 1/-1; text-align: center; padding: 60px 20px; background: white; border-radius: 12px; box-shadow: 0 6px 20px rgba(0,0,0,0.08);">
                <div style="font-size: 60px; margin-bottom: 20px;">Cart</div>
                <h3 style="color: #333; margin-bottom: 15px;">Your cart is empty</h3>
                <p style="color: #666; margin-bottom: 30px;">Start shopping to add items to your cart!</p>
                <button onclick="showAllProducts()" style="background: linear-gradient(135deg, #ff5722, #ff9800); padding: 12px 25px; border: none; border-radius: 25px; color: white; font-weight: 600; cursor: pointer;">
                    Browse Products
                </button>
            </div>
        `;
        return;
    }
    
    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    
    container.innerHTML = `
        <div style="grid-column: 1/-1;">
            <div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 6px 20px rgba(0,0,0,0.08); margin-bottom: 30px;">
                <h3 style="margin-bottom: 20px; color: #333;">Cart Items (${cart.length})</h3>
                <div style="display: grid; gap: 20px;">
                    ${cart.map(item => `
                        <div style="display: flex; gap: 20px; align-items: center; padding: 20px; border: 1px solid #eee; border-radius: 8px;">
                            <img src="${item.image}" alt="${item.name}" style="width: 100px; height: 100px; object-fit: contain; border-radius: 8px;">
                            <div style="flex: 1;">
                                <h4 style="margin: 0 0 10px 0; color: #333;">${item.name}</h4>
                                <p style="margin: 0; color: #ff5722; font-weight: bold;">Rs ${item.price.toLocaleString('en-IN')}</p>
                            </div>
                            <div style="display: flex; gap: 10px; align-items: center;">
                                <button onclick="removeFromCart(${item.id})" style="background: #ff5722; color: white; border: none; padding: 8px 16px; border-radius: 20px; cursor: pointer;">
                                    Remove
                                </button>
                                <a href="${item.buyLink}" target="_blank" style="text-decoration: none;">
                                    <button style="background: #4CAF50; color: white; border: none; padding: 8px 16px; border-radius: 20px; cursor: pointer;">
                                        Buy Now
                                    </button>
                                </a>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
            
            <div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 6px 20px rgba(0,0,0,0.08);">
                <h3 style="margin-bottom: 20px; color: #333;">Cart Summary</h3>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-bottom: 1px solid #eee;">
                    <span style="color: #666;">Subtotal:</span>
                    <span style="font-weight: bold; color: #ff5722;">Rs ${total.toLocaleString('en-IN')}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-bottom: 1px solid #eee;">
                    <span style="color: #666;">Shipping:</span>
                    <span style="color: #666;">Calculated at checkout</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 20px 0; font-size: 18px; font-weight: bold;">
                    <span>Total:</span>
                    <span style="color: #ff5722;">Rs ${total.toLocaleString('en-IN')}</span>
                </div>
                <button onclick="proceedToCheckout()" style="width: 100%; padding: 15px; background: linear-gradient(135deg, #4CAF50, #45a049); border: none; border-radius: 25px; color: white; font-weight: 600; font-size: 16px; cursor: pointer; margin-top: 20px;">
                    Proceed to Checkout
                </button>
            </div>
        </div>
    `;
}

function showNotification(message) {
    // Create notification element
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: linear-gradient(135deg, #4CAF50, #45a049);
        color: white;
        padding: 15px 25px;
        border-radius: 25px;
        box-shadow: 0 6px 20px rgba(76, 175, 80, 0.3);
        z-index: 1000;
        font-weight: 600;
        animation: slideIn 0.3s ease;
    `;
    notification.textContent = message;
    
    // Add animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    `;
    document.head.appendChild(style);
    
    document.body.appendChild(notification);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.style.animation = 'slideIn 0.3s ease reverse';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

function clearCart() {
    if (cart.length === 0) {
        showNotification('Your cart is already empty!', 'warning');
        return;
    }
    
    if (confirm('Are you sure you want to clear your entire cart?')) {
        cart = [];
        saveCart();
        updateCartCount();
        updateCartDisplay();
        showNotification('Cart cleared successfully', 'success');
    }
}

function updateCartDisplay() {
    const cartContent = document.getElementById('cart-content');
    if (!cartContent) return;
    
    if (cart.length === 0) {
        cartContent.innerHTML = `
            <div class="cart-empty">
                <div style="text-align: center; padding: 60px 20px; background: var(--bg-card); border-radius: 16px; box-shadow: 0 8px 32px var(--shadow-light); border: 1px solid var(--border-color);">
                    <div style="font-size: 4rem; margin-bottom: 2rem;">🛒</div>
                    <h3 style="color: var(--text-primary); margin-bottom: 1rem; font-size: 1.5rem;">Your cart is empty</h3>
                    <p style="color: var(--text-secondary); margin-bottom: 2rem; font-size: 1.1rem;">Start shopping to add items to your cart!</p>
                    <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                        <a href="wear.html" class="btn btn-primary">
                            Browse Sports Wear
                        </a>
                        <a href="equipment.html" class="btn btn-secondary">
                            Browse Equipment
                        </a>
                    </div>
                </div>
            </div>
        `;
        return;
    }
    
    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    
    cartContent.innerHTML = `
        <div class="cart-items">
            <h3 style="margin-bottom: 2rem; color: var(--text-primary); font-size: 1.3rem;">Cart Items (${cart.length})</h3>
            <div style="display: grid; gap: 1.5rem;">
                ${cart.map((item, index) => `
                    <div style="display: flex; gap: 1.5rem; align-items: center; padding: 1.5rem; background: var(--bg-card); border-radius: 12px; box-shadow: 0 4px 16px var(--shadow-light); border: 1px solid var(--border-color);">
                        <img src="${item.image}" alt="${item.name}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;">
                        <div style="flex: 1;">
                            <h4 style="margin: 0 0 0.5rem 0; color: var(--text-primary); font-size: 1.1rem;">${item.name}</h4>
                            <p style="margin: 0 0 1rem 0; color: var(--accent-primary); font-weight: bold; font-size: 1.1rem;">₹${item.price.toLocaleString('en-IN')}</p>
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <div style="display: flex; align-items: center; gap: 0.5rem; background: var(--bg-secondary); border-radius: 8px; padding: 0.25rem;">
                                    <button onclick="updateQuantity(${index}, -1)" style="background: none; border: none; color: var(--text-primary); cursor: pointer; padding: 0.5rem; border-radius: 4px; font-size: 1.2rem;">−</button>
                                    <span style="min-width: 2rem; text-align: center; font-weight: 600;">${item.quantity}</span>
                                    <button onclick="updateQuantity(${index}, 1)" style="background: none; border: none; color: var(--text-primary); cursor: pointer; padding: 0.5rem; border-radius: 4px; font-size: 1.2rem;">+</button>
                                </div>
                                <button onclick="removeFromCart(${item.id})" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.9rem;">Remove</button>
                                <a href="${item.buyLink}" target="_blank" class="btn btn-success" style="padding: 0.5rem 1rem; font-size: 0.9rem;">Buy Now</a>
                            </div>
                        </div>
                    </div>
                `).join('')}
            </div>
        </div>
        
        <div class="cart-summary" style="margin-top: 40px; background: var(--bg-card); padding: 2rem; border-radius: 16px; box-shadow: 0 8px 32px var(--shadow-light); border: 1px solid var(--border-color);">
            <h3 style="margin-bottom: 1.5rem; color: var(--text-primary); font-size: 1.3rem;">Cart Summary</h3>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; border-bottom: 1px solid var(--border-color);">
                <span style="color: var(--text-secondary);">Subtotal:</span>
                <span style="font-weight: bold; color: var(--accent-primary); font-size: 1.1rem;">₹${total.toLocaleString('en-IN')}</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; border-bottom: 1px solid var(--border-color);">
                <span style="color: var(--text-secondary);">Shipping:</span>
                <span style="color: var(--text-secondary);">Calculated at checkout</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.5rem 0; font-size: 1.2rem; font-weight: bold; border-top: 2px solid var(--border-color); margin-top: 1rem;">
                <span>Total:</span>
                <span style="color: var(--accent-primary);">₹${total.toLocaleString('en-IN')}</span>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button onclick="clearCart()" class="btn btn-outline" style="flex: 1;">
                    Clear Cart
                </button>
                <button onclick="proceedToCheckout()" class="btn btn-success" style="flex: 2;">
                    Proceed to Checkout
                </button>
            </div>
        </div>
    `;
}

function updateQuantity(index, change) {
    const item = cart[index];
    const newQuantity = item.quantity + change;
    
    if (newQuantity < 1) {
        removeFromCart(item.id);
        return;
    }
    
    if (newQuantity > 10) {
        showNotification('Maximum quantity is 10 items per product', 'warning');
        return;
    }
    
    item.quantity = newQuantity;
    saveCart();
    updateCartCount();
    updateCartDisplay();
    showNotification('Cart updated', 'success');
}

function proceedToCheckout() {
    if (cart.length === 0) {
        showNotification('Your cart is empty!', 'warning');
        return;
    }
    
    showNotification('Redirecting to checkout...', 'info');
    // In a real application, this would redirect to a checkout page
    setTimeout(() => {
        alert('Checkout functionality would be implemented here. For now, please use the "Buy Now" buttons on individual products.');
    }, 1500);
}

// Quick search functions for category buttons
function searchCategory(category) {
    const results = products.filter(product => 
        product.subcategory.toLowerCase() === category.toLowerCase() ||
        product.category.toLowerCase() === category.toLowerCase()
    );
    displaySearchResults(results, category);
}

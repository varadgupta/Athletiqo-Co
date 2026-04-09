// Product Database
const products = [
    {
        id: 1,
        name: "Basketball BT900 - Size 7",
        category: "equipment",
        subcategory: "basketball",
        price: 1999,
        image: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT1voFYoBEsu09Wk_ZzJ0Vjfwp8TajnE3snCg&s",
        buyLink: "https://www.decathlon.in/p/8648080/basketball-bt900-size-7fiba-approved-for-boys-and-adults",
        keywords: ["basketball", "ball", "sports", "equipment"]
    },
    {
        id: 2,
        name: "Cult Men's Running Shoes-Off White",
        category: "wear",
        subcategory: "shoes",
        price: 2299,
        image: "https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcT-73mzz_opcEj925iNhtiTrMGr2Hw6zfmhMdm38YcIfBe0yrbOJi8fMW5iw48gWyvD7e3YfCnGXpthhxWP8kMxOFAUu5IsImYiY8ViHSc1kA2jscvitd3zTw&usqp=CAc",
        buyLink: "https://cultstore.com/products/cult-men-s-traverse-running-shoes-off-white",
        keywords: ["shoes", "running", "footwear", "sports", "wear"]
    },
    {
        id: 3,
        name: "SS Ikon Kashmir Willow Cricket Bat",
        category: "equipment",
        subcategory: "cricket",
        price: 2654,
        image: "https://www.sstoncricket.com/wp-content/uploads/2023/04/56A51367-scaled-370x493.jpg",
        buyLink: "https://www.sstoncricket.com/ss-ikon-kashmir-willow-cricket-bat-sh.html",
        keywords: ["cricket", "bat", "sports", "equipment"]
    },
    {
        id: 4,
        name: "Gym Weight Training Station Full Body Workout at Home",
        category: "equipment",
        subcategory: "gym",
        price: 35999,
        image: "https://contents.mediadecathlon.com/p2300404/5f3428e7ca71a6b8d65702dd8bffdcdc/p2300404.jpg",
        buyLink: "https://www.decathlon.in/p/8484134/gym-weight-training-station-full-body-workout-at-home-home-gym-900-black",
        keywords: ["gym", "workout", "training", "equipment", "fitness"]
    },
    {
        id: 5,
        name: "Boldfit Adjustable Hand Grip",
        category: "equipment",
        subcategory: "gym",
        price: 299,
        image: "https://m.media-amazon.com/images/I/61eYoqYP2TL._AC_UL480_FMwebp_QL65_.jpg",
        buyLink: "https://amzn.in/d/04hoirIg",
        keywords: ["hand", "grip", "gym", "fitness", "equipment"]
    },
    {
        id: 6,
        name: "DOMYOS - Men Gym Trackpant Convertible",
        category: "wear",
        subcategory: "trackpant",
        price: 999,
        image: "https://contents.mediadecathlon.com/p2273704/45979084bc75f77fb9b7c3a4efc12751/p2273704.jpg",
        buyLink: "https://www.decathlon.in/p/8731703/men-gym-trackpant-convertible-jog-fit-quick-dry-zip-pockets-500-black",
        keywords: ["trackpant", "pants", "gym", "wear", "sports"]
    },
    {
        id: 7,
        name: "Reebok Unisex Ri Vector Knit Tracktop",
        category: "wear",
        subcategory: "tracktop",
        price: 1799,
        image: "https://imagescdn.reebok.in/img/app/product/9/957428-12396402.jpg?auto=format&w=390",
        buyLink: "https://reebok.abfrl.in/p/reebok-unisex-ri-vector-knit-tracktop-957428.html?source=plp",
        keywords: ["tracktop", "jacket", "wear", "sports", "reebok"]
    },
    {
        id: 8,
        name: "Men's Full Sleeve Compression T-Shirt",
        category: "wear",
        subcategory: "tshirt",
        price: 899,
        image: "https://m.media-amazon.com/images/I/61J2-dVzIgL._SY879_.jpg",
        buyLink: "https://www.amazon.in/FUAARK-Mens-Sleeve-Compression-T-Shirt/dp/B0C5SVCGM6?ref_=ast_sto_dp&th=1&psc=1",
        keywords: ["tshirt", "shirt", "compression", "wear", "sports"]
    }
];

// Shopping Cart
let cart = JSON.parse(localStorage.getItem('athletiqoCart')) || [];

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
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
}

function displaySearchResults(results, query) {
    const container = document.querySelector('.products');
    const sectionTitle = document.querySelector('.section-title');
    
    if (!container || !sectionTitle) return;
    
    sectionTitle.textContent = `Found ${results.length} result${results.length !== 1 ? 's' : ''} for "${query}"`;
    
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
    const container = document.querySelector('.products');
    const sectionTitle = document.querySelector('.section-title');
    const heroTitle = document.querySelector('.hero h2');
    
    if (!container || !sectionTitle) return;
    
    if (heroTitle) {
        heroTitle.textContent = 'Shopping Cart';
    }
    
    sectionTitle.textContent = 'Your Shopping Cart';
    
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

function proceedToCheckout() {
    if (cart.length === 0) {
        showNotification('Your cart is empty!');
        return;
    }
    
    showNotification('Redirecting to checkout...');
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

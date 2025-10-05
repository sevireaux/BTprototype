// product.js - Enhanced Product page functionality

let currentQuantity = 1;
let currentProductId = null;
let allProducts = [];

// Load products from database
async function loadProductsFromDB() {
    try {
        const response = await fetch('api/get_products.php');
        const data = await response.json();
        
        if (data.success && data.products) {
            allProducts = data.products;
            displayProductsByCategory();
        } else {
            console.error('Failed to load products:', data.message);
        }
    } catch (error) {
        console.error('Error loading products:', error);
        showAllProductsError();
    }
}

// Display error message in all grids
function showAllProductsError() {
    const grids = ['all-products-grid', 'bestsellers-grid', 'drinks-grid', 'bread-grid', 'merchandise-grid'];
    grids.forEach(gridId => {
        const container = document.getElementById(gridId);
        if (container) {
            container.innerHTML = '<div class="col-span-full text-center py-8"><p class="text-red-600">Failed to load products. Please try again later.</p></div>';
        }
    });
}

// Display products by category
function displayProductsByCategory() {
    // All Products
    displayCategoryProducts('all-products-grid', null, 5);
    
    // Best Sellers
    displayCategoryProducts('bestsellers-grid', 'Best Sellers', 5);
    
    // Drinks
    displayCategoryProducts('drinks-grid', 'Drinks', 5);
    
    // Bread & Pastries
    displayCategoryProducts('bread-grid', 'Bread & Pastries', 5);
    
    // Merchandise
    displayCategoryProducts('merchandise-grid', 'Merchandise', 5);
}

function displayCategoryProducts(containerId, categoryName, limit = null) {
    const container = document.getElementById(containerId);
    if (!container) return;
    
    let filteredProducts = allProducts.filter(p => p.is_available == 1);
    
    if (categoryName) {
        filteredProducts = filteredProducts.filter(p => 
            p.categories && p.categories.includes(categoryName)
        );
    }
    
    // Apply limit if specified
    const displayProducts = limit ? filteredProducts.slice(0, limit) : filteredProducts;
    
    if (displayProducts.length === 0) {
        container.innerHTML = '<div class="col-span-full text-center py-8"><p class="text-stone-600">No products available in this category</p></div>';
        return;
    }
    
    container.innerHTML = displayProducts.map(product => createProductCard(product)).join('');
    
    // Add click events to product cards
    container.querySelectorAll('.product-card').forEach(card => {
        card.addEventListener('click', function(e) {
            // Don't open modal if button was clicked
            if (!e.target.closest('button')) {
                const productId = this.dataset.id;
                showProductDetail(productId);
            }
        });
    });
}

function createProductCard(product) {
    return `
        <div class="product-card bg-white rounded-lg overflow-hidden cursor-pointer hover:-translate-y-2 transition shadow-lg" data-id="${product.product_id}">
            <div class="h-44 bg-gray-100 flex items-center justify-center overflow-hidden">
                ${product.photo_url 
                    ? `<img src="${product.photo_url}" alt="${escapeHtml(product.product_name)}" class="w-full h-full object-cover">`
                    : '<span class="text-gray-400">No Image</span>'
                }
            </div>
            <div class="p-4 text-dark">
                <div class="font-semibold mb-2 text-stone-800 line-clamp-2">${escapeHtml(product.product_name)}</div>
                <div class="text-primary font-bold text-lg mb-3">₱${parseFloat(product.price).toFixed(2)}</div>
                <div class="text-xs text-stone-500 mb-3">Stock: ${product.stock_quantity}</div>
                <div class="flex gap-2">
                    <button class="flex-1 py-2 border-2 border-primary text-primary rounded hover:bg-primary hover:text-white transition font-semibold text-sm" onclick="event.stopPropagation(); addToCart(${product.product_id}, 1)">
                        Add to Cart
                    </button>
                    <button class="flex-1 py-2 bg-primary text-white rounded hover:bg-amber-800 transition font-semibold text-sm" onclick="event.stopPropagation(); showProductDetail(${product.product_id})">
                        View
                    </button>
                </div>
            </div>
        </div>
    `;
}

// Escape HTML to prevent XSS
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Add to cart function
async function addToCart(productId, quantity = 1) {
    // Check if user is logged in
    if (!isUserLoggedIn) {
        alert('Please log in to add items to your cart.');
        window.location.href = 'login.php';
        return;
    }
    
    const product = allProducts.find(p => p.product_id == productId);
    if (!product) {
        alert('Product not found.');
        return;
    }
    
    // Check stock
    if (product.stock_quantity < quantity) {
        alert('Not enough stock available.');
        return;
    }
    
    try {
        const response = await fetch('api/add_to_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert(`${product.product_name} added to cart!`);
            // Optionally update cart count in navbar
            updateCartCount();
        } else {
            alert('Failed to add to cart: ' + (data.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
        alert('Failed to add to cart. Please try again.');
    }
}

// Update cart count (if you have a cart icon in navbar)
async function updateCartCount() {
    try {
        const response = await fetch('api/get_cart_count.php');
        const data = await response.json();
        
        if (data.success) {
            const cartCountElement = document.getElementById('cartCount');
            if (cartCountElement) {
                cartCountElement.textContent = data.count;
            }
        }
    } catch (error) {
        console.error('Error updating cart count:', error);
    }
}

// Show product detail modal
function showProductDetail(productId) {
    const product = allProducts.find(p => p.product_id == productId);
    if (!product) return;
    
    currentProductId = productId;
    currentQuantity = 1;
    
    const modal = document.getElementById('productModal');
    const modalContent = document.getElementById('productModalContent');
    
    modalContent.innerHTML = `
        <div class="grid md:grid-cols-2 gap-6">
            <div class="h-80 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
                ${product.photo_url 
                    ? `<img src="${product.photo_url}" alt="${escapeHtml(product.product_name)}" class="w-full h-full object-cover">`
                    : '<span class="text-gray-400 text-lg">No Image Available</span>'
                }
            </div>
            
            <div>
                <h2 class="text-2xl font-bold text-stone-800 mb-2">${escapeHtml(product.product_name)}</h2>
                <p class="text-3xl font-bold text-amber-700 mb-4">₱${parseFloat(product.price).toFixed(2)}</p>
                
                ${product.description ? `<p class="text-stone-600 mb-4">${escapeHtml(product.description)}</p>` : '<p class="text-stone-400 mb-4 italic">No description available</p>'}
                
                <div class="mb-4">
                    <p class="text-sm ${product.stock_quantity > 0 ? 'text-green-600' : 'text-red-600'} font-semibold">
                        ${product.stock_quantity > 0 ? `In Stock: ${product.stock_quantity} available` : 'Out of Stock'}
                    </p>
                </div>
                
                ${product.categories && product.categories.length > 0 ? `
                    <div class="mb-4">
                        <p class="text-sm text-stone-600 mb-2">Categories:</p>
                        <div class="flex flex-wrap gap-2">
                            ${product.categories.map(cat => `
                                <span class="px-3 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-semibold">${escapeHtml(cat)}</span>
                            `).join('')}
                        </div>
                    </div>
                ` : ''}
                
                ${product.stock_quantity > 0 ? `
                    <div class="flex items-center gap-4 mb-6">
                        <label class="text-stone-700 font-semibold">Quantity:</label>
                        <div class="flex items-center gap-3">
                            <button onclick="decreaseQuantity()" class="w-8 h-8 bg-stone-200 rounded hover:bg-stone-300 font-bold">-</button>
                            <span id="modalQuantity" class="font-bold text-lg min-w-[30px] text-center">1</span>
                            <button onclick="increaseQuantity(${product.stock_quantity})" class="w-8 h-8 bg-stone-200 rounded hover:bg-stone-300 font-bold">+</button>
                        </div>
                    </div>
                    
                    <div class="flex gap-3">
                        <button onclick="addToCartFromModal()" class="flex-1 py-3 border-2 border-amber-700 text-amber-700 rounded-lg hover:bg-amber-700 hover:text-white transition font-semibold">
                            Add to Cart
                        </button>
                        <button onclick="orderNow(${product.product_id})" class="flex-1 py-3 bg-amber-700 text-white rounded-lg hover:bg-amber-800 transition font-semibold">
                            Order Now
                        </button>
                    </div>
                ` : `
                    <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-red-600 font-semibold">This product is currently out of stock.</p>
                    </div>
                `}
            </div>
        </div>
    `;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeProductModal() {
    const modal = document.getElementById('productModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    currentQuantity = 1;
    currentProductId = null;
}

function decreaseQuantity() {
    if (currentQuantity > 1) {
        currentQuantity--;
        document.getElementById('modalQuantity').textContent = currentQuantity;
    }
}

function increaseQuantity(maxStock) {
    if (currentQuantity < maxStock) {
        currentQuantity++;
        document.getElementById('modalQuantity').textContent = currentQuantity;
    } else {
        alert('Maximum stock quantity reached.');
    }
}

function addToCartFromModal() {
    if (currentProductId) {
        addToCart(currentProductId, currentQuantity);
    }
}

// Order now function
function orderNow(productId) {
    if (!isUserLoggedIn) {
        alert('Please log in to place an order.');
        window.location.href = 'login.php';
        return;
    }
    
    // Add to cart first, then redirect to checkout
    addToCart(productId, currentQuantity).then(() => {
        window.location.href = 'checkout.php';
    });
}

// Category button functionality
document.querySelectorAll('.category-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const category = this.dataset.category;
        
        // Update active button
        document.querySelectorAll('.category-btn').forEach(b => {
            b.classList.remove('bg-primary', 'text-white');
            b.classList.add('bg-white', 'text-dark');
        });
        this.classList.remove('bg-white', 'text-dark');
        this.classList.add('bg-primary', 'text-white');
        
        // Scroll to category section
        const sectionMap = {
            'all': 'all-products',
            'Best Sellers': 'best-sellers',
            'Drinks': 'drinks',
            'Bread & Pastries': 'bread-pastries',
            'Merchandise': 'merchandise'
        };
        
        const sectionId = sectionMap[category];
        if (sectionId) {
            const section = document.getElementById(sectionId);
            if (section) {
                section.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
    });
});

// View more functionality
document.querySelectorAll('.view-more').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const category = this.dataset.category;
        showViewMoreModal(category);
    });
});

function showViewMoreModal(category) {
    let filteredProducts = allProducts.filter(p => p.is_available == 1);
    
    let title = 'All Products';
    if (category && category !== 'all') {
        filteredProducts = filteredProducts.filter(p => 
            p.categories && p.categories.includes(category)
        );
        title = category;
    }
    
    const modal = document.getElementById('viewMoreModal');
    const titleElement = document.getElementById('viewMoreTitle');
    const contentElement = document.getElementById('viewMoreContent');
    
    titleElement.textContent = title;
    
    if (filteredProducts.length === 0) {
        contentElement.innerHTML = '<div class="col-span-full text-center py-8"><p class="text-stone-600">No products available in this category</p></div>';
    } else {
        contentElement.innerHTML = filteredProducts.map(product => createProductCard(product)).join('');
        
        // Add click events
        contentElement.querySelectorAll('.product-card').forEach(card => {
            card.addEventListener('click', function(e) {
                if (!e.target.closest('button')) {
                    const productId = this.dataset.id;
                    closeViewMoreModal();
                    showProductDetail(productId);
                }
            });
        });
    }
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeViewMoreModal() {
    const modal = document.getElementById('viewMoreModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Close modals when clicking outside
document.getElementById('productModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeProductModal();
    }
});

document.getElementById('viewMoreModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeViewMoreModal();
    }
});

// Initialize on page load
window.addEventListener('DOMContentLoaded', function() {
    loadProductsFromDB();
    
    // Update cart count if user is logged in
    if (isUserLoggedIn) {
        updateCartCount();
    }
});
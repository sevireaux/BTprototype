document.addEventListener('DOMContentLoaded', function() {
    loadCartItems();
    updateCartCount();
});

async function loadCartItems() {
    try {
        const response = await fetch('api/get_cart_items.php');
        const data = await response.json();
        
        if (data.success) {
            displayCartItems(data.items);
            updateOrderSummary(data.items);
        } else {
            document.getElementById('cart-items').innerHTML = `
                <div class="text-center py-8">
                    <p class="text-red-400">${data.message || 'Error loading cart items'}</p>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error loading cart items:', error);
        document.getElementById('cart-items').innerHTML = `
            <div class="text-center py-8">
                <p class="text-red-400">An error occurred while loading your cart</p>
            </div>
        `;
    }
}

function displayCartItems(items) {
    const container = document.getElementById('cart-items');
    
    if (items.length === 0) {
        container.innerHTML = `
            <div class="text-center py-8">
                <p class="text-gray-400">Your cart is empty</p>
                <a href="products.php" class="inline-block mt-4 px-6 py-3 bg-yellow-600 text-white rounded-lg font-semibold hover:bg-yellow-700 transition">
                    Browse Products
                </a>
            </div>
        `;
        return;
    }
    
    container.innerHTML = items.map(item => `
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="w-full md:w-24 h-24 bg-gray-700 rounded-lg overflow-hidden flex-shrink-0">
                    ${item.photo_url 
                        ? `<img src="${item.photo_url}" alt="${item.product_name}" class="w-full h-full object-cover">`
                        : '<div class="w-full h-full flex items-center justify-center text-gray-500">No Image</div>'
                    }
                </div>
                <div class="flex-grow">
                    <h3 class="text-xl font-semibold text-white mb-1">${item.product_name}</h3>
                    <p class="text-gray-400 mb-3">${item.description || 'No description available'}</p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <button onclick="updateQuantity(${item.cart_item_id}, ${item.quantity - 1})" class="w-8 h-8 bg-gray-700 text-white rounded-l-lg hover:bg-gray-600 transition">
                                -
                            </button>
                            <span class="w-12 h-8 bg-gray-700 text-white flex items-center justify-center">${item.quantity}</span>
                            <button onclick="updateQuantity(${item.cart_item_id}, ${item.quantity + 1})" class="w-8 h-8 bg-gray-700 text-white rounded-r-lg hover:bg-gray-600 transition">
                                +
                            </button>
                        </div>
                        <div class="text-right">
                            <p class="text-gray-400 text-sm">₱${parseFloat(item.price).toFixed(2)} each</p>
                            <p class="text-xl font-bold text-yellow-600">₱${parseFloat(item.subtotal).toFixed(2)}</p>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col justify-between">
                    <button onclick="removeFromCart(${item.cart_item_id})" class="text-red-400 hover:text-red-300 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

function updateOrderSummary(items) {
    let subtotal = 0;
    
    items.forEach(item => {
        subtotal += parseFloat(item.subtotal);
    });
    
    const tax = subtotal * 0.1;
    const total = subtotal + tax;
    
    document.getElementById('subtotal').textContent = `₱${subtotal.toFixed(2)}`;
    document.getElementById('tax').textContent = `₱${tax.toFixed(2)}`;
    document.getElementById('total').textContent = `₱${total.toFixed(2)}`;
}

async function updateQuantity(cartItemId, newQuantity) {
    if (newQuantity < 1) {
        removeFromCart(cartItemId);
        return;
    }
    
    try {
        const response = await fetch('api/update_cart_item.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                cart_item_id: cartItemId, 
                quantity: newQuantity 
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            loadCartItems();
            updateCartCount();
        } else {
            alert(data.message || 'Failed to update quantity');
        }
    } catch (error) {
        console.error('Error updating quantity:', error);
        alert('An error occurred while updating the quantity');
    }
}

async function removeFromCart(cartItemId) {
    if (!confirm('Are you sure you want to remove this item from your cart?')) return;
    
    try {
        const response = await fetch('api/remove_from_cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ cart_item_id: cartItemId })
        });
        
        const data = await response.json();
        
        if (data.success) {
            loadCartItems();
            updateCartCount();
        } else {
            alert(data.message || 'Failed to remove item');
        }
    } catch (error) {
        console.error('Error removing item:', error);
        alert('An error occurred while removing the item');
    }
}

async function placeOrder() {
    const orderNotes = document.getElementById('order-notes').value;
    
    try {
        const response = await fetch('api/place_order.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ notes: orderNotes })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Order placed successfully! Your order number is ' + data.order_number);
            window.location.href = 'order_confirmation.php?order_id=' + data.order_id;
        } else {
            alert(data.message || 'Failed to place order');
        }
    } catch (error) {
        console.error('Error placing order:', error);
        alert('An error occurred while placing your order');
    }
}

// Update cart count in header
async function updateCartCount() {
    try {
        const response = await fetch('api/get_cart_count.php');
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('cart-count').textContent = data.count;
        }
    } catch (error) {
        console.error('Error updating cart count:', error);
    }
}

// Place order button click handler
document.getElementById('place-order-btn').addEventListener('click', placeOrder);
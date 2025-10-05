// Tab switching function
function showTab(tabName) {
    const tabs = document.querySelectorAll('.tab-content');
    tabs.forEach(tab => tab.classList.add('hidden'));
    document.getElementById(tabName).classList.remove('hidden');
    
    const buttons = document.querySelectorAll('.tab-btn');
    buttons.forEach(btn => btn.classList.remove('bg-gray-700'));
    event.target.classList.add('bg-gray-700');
}

// ========== TESTIMONIAL FUNCTIONS ==========
function openTestimonialModal() {
    document.getElementById('testimonialModal').classList.add('active');
    loadUserMessages();
}

function closeTestimonialModal() {
    document.getElementById('testimonialModal').classList.remove('active');
}

async function loadUserMessages() {
    try {
        const response = await fetch('api/get_user_messages.php');
        const data = await response.json();
        
        if (data.success && data.messages) {
            const select = document.getElementById('userMessage');
            select.innerHTML = data.messages.map(msg => 
                `<option value="${msg.contact_id}">${msg.full_name} - "${msg.message.substring(0, 50)}..."</option>`
            ).join('');
        }
    } catch (error) {
        console.error('Error loading messages:', error);
    }
}

async function addTestimonial() {
    const contactId = document.getElementById('userMessage').value;
    
    try {
        const response = await fetch('api/add_testimonial.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ contact_id: contactId })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Testimonial added to homepage!');
            closeTestimonialModal();
            loadTestimonials();
        } else {
            alert(data.message || 'Failed to add testimonial');
        }
    } catch (error) {
        alert('An error occurred');
    }
}

async function deleteTestimonial(id) {
    if (!confirm('Are you sure you want to remove this testimonial from the homepage?')) return;
    
    try {
        const response = await fetch('api/delete_testimonial.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ testimonial_id: id })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Testimonial removed!');
            loadTestimonials();
        } else {
            alert(data.message || 'Failed to remove testimonial');
        }
    } catch (error) {
        alert('An error occurred');
    }
}

async function loadTestimonials() {
    try {
        const response = await fetch('api/get_testimonials.php');
        const data = await response.json();
        
        if (data.success && data.testimonials) {
            const grid = document.getElementById('testimonialsGrid');
            
            if (data.testimonials.length === 0) {
                grid.innerHTML = '<div class="col-span-full text-center py-8"><p class="text-gray-400">No testimonials yet</p></div>';
                return;
            }
            
            grid.innerHTML = data.testimonials.map(test => `
                <div class="bg-gray-800 rounded-xl p-6 card-hover border border-gray-700">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-semibold text-white">${test.full_name}</h3>
                            <p class="text-sm text-gray-400">${test.email || 'No email'}</p>
                        </div>
                        <button onclick="deleteTestimonial(${test.testimonial_id})" class="text-red-400 hover:text-red-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                    <p class="text-gray-300">"${test.message}"</p>
                    <div class="mt-4">
                        <span class="inline-block px-3 py-1 ${test.is_featured ? 'bg-green-900 text-green-300' : 'bg-gray-700 text-gray-400'} rounded-full text-xs">
                            ${test.is_featured ? 'Featured' : 'Not Featured'}
                        </span>
                    </div>
                </div>
            `).join('');
        }
    } catch (error) {
        console.error('Error loading testimonials:', error);
    }
}

// ========== PRODUCT FUNCTIONS ==========
function openProductModal() {
    document.getElementById('productModal').classList.add('active');
    document.getElementById('productForm').reset();
    document.getElementById('productId').value = '';
    document.getElementById('currentPhotoUrl').value = '';
}

function closeProductModal() {
    document.getElementById('productModal').classList.remove('active');
}

async function saveProduct() {
    const productId = document.getElementById('productId').value;
    const formData = new FormData();
    
    formData.append('product_id', productId);
    formData.append('product_name', document.getElementById('productName').value);
    formData.append('price', document.getElementById('productPrice').value);
    formData.append('description', document.getElementById('productDesc').value);
    formData.append('stock_quantity', document.getElementById('productStock').value || 0);
    
    const photoFile = document.getElementById('productPhoto').files[0];
    if (photoFile) {
        formData.append('photo', photoFile);
    } else if (productId) {
        formData.append('current_photo_url', document.getElementById('currentPhotoUrl').value);
    }
    
    // Get selected categories
    const categories = [];
    document.querySelectorAll('input[name="categories"]:checked').forEach(cb => {
        categories.push(cb.value);
    });
    formData.append('categories', JSON.stringify(categories));
    
    try {
        const response = await fetch('api/save_product.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert(productId ? 'Product updated successfully!' : 'Product added successfully!');
            closeProductModal();
            loadProducts();
        } else {
            alert(data.message || 'Failed to save product');
        }
    } catch (error) {
        alert('An error occurred: ' + error.message);
    }
}

async function editProduct(id) {
    try {
        const response = await fetch(`api/get_product.php?product_id=${id}`);
        const data = await response.json();
        
        if (data.success && data.product) {
            const product = data.product;
            
            document.getElementById('productId').value = product.product_id;
            document.getElementById('productName').value = product.product_name;
            document.getElementById('productPrice').value = product.price;
            document.getElementById('productDesc').value = product.description || '';
            document.getElementById('productStock').value = product.stock_quantity || 0;
            document.getElementById('currentPhotoUrl').value = product.photo_url || '';
            
            // Check categories
            document.querySelectorAll('input[name="categories"]').forEach(cb => {
                cb.checked = product.categories.includes(cb.value);
            });
            
            openProductModal();
        }
    } catch (error) {
        alert('Error loading product: ' + error.message);
    }
}

async function deleteProduct(id) {
    if (!confirm('Are you sure you want to delete this product?')) return;
    
    try {
        const response = await fetch('api/delete_product.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ product_id: id })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Product deleted!');
            loadProducts();
        } else {
            alert(data.message || 'Failed to delete product');
        }
    } catch (error) {
        alert('An error occurred');
    }
}

async function loadProducts() {
    try {
        const response = await fetch('api/get_products.php');
        const data = await response.json();
        
        if (data.success && data.products) {
            const grid = document.getElementById('productsGrid');
            
            if (data.products.length === 0) {
                grid.innerHTML = '<div class="col-span-full text-center py-8"><p class="text-gray-400">No products yet</p></div>';
                return;
            }
            
            grid.innerHTML = data.products.map(product => `
                <div class="bg-gray-800 rounded-xl overflow-hidden card-hover border border-gray-700">
                    <div class="h-48 bg-gray-700 flex items-center justify-center overflow-hidden">
                        ${product.photo_url 
                            ? `<img src="${product.photo_url}" alt="${product.product_name}" class="w-full h-full object-cover">`
                            : '<span class="text-gray-500">No Image</span>'
                        }
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-white mb-2">${product.product_name}</h3>
                        <p class="text-2xl font-bold text-yellow-600 mb-2">₱${parseFloat(product.price).toFixed(2)}</p>
                        <p class="text-sm text-gray-400 mb-4">Stock: ${product.stock_quantity}</p>
                        <div class="flex flex-wrap gap-2 mb-4">
                            ${product.categories.map(cat => 
                                `<span class="px-2 py-1 bg-gray-700 text-gray-300 rounded text-xs">${cat}</span>`
                            ).join('')}
                        </div>
                        <div class="flex gap-2">
                            <button onclick="editProduct(${product.product_id})" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Edit
                            </button>
                            <button onclick="deleteProduct(${product.product_id})" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }
    } catch (error) {
        console.error('Error loading products:', error);
    }
}

// ========== ORDER FUNCTIONS ==========
async function updateOrderStatus(orderId, status) {
    try {
        const response = await fetch('api/update_order_status.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ order_id: orderId, status: status })
        });
        
        const data = await response.json();
        
        if (data.success) {
            const statusMessages = {
                'preparing': 'Order accepted and being prepared!',
                'completed': 'Order marked as completed!',
                'cancelled': 'Order cancelled!'
            };
            alert(statusMessages[status]);
            loadOrders();
            loadOrderHistory();
        } else {
            alert(data.message || 'Failed to update order');
        }
    } catch (error) {
        alert('An error occurred');
    }
}

async function loadOrders() {
    try {
        const response = await fetch('api/get_orders.php?status=pending,preparing');
        const data = await response.json();
        
        if (data.success && data.orders) {
            const queue = document.getElementById('ordersQueue');
            
            if (data.orders.length === 0) {
                queue.innerHTML = '<div class="text-center py-8"><p class="text-gray-400">No pending orders</p></div>';
                return;
            }
            
            queue.innerHTML = data.orders.map(order => `
                <div class="bg-gray-800 rounded-xl p-6 card-hover border border-gray-700">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-semibold text-white">${order.order_number}</h3>
                            <p class="text-sm text-gray-400">Customer: ${order.customer_name}</p>
                            <p class="text-sm text-gray-400">Time: ${new Date(order.order_date).toLocaleTimeString()}</p>
                        </div>
                        <span class="status-badge status-${order.status}">${order.status}</span>
                    </div>
                    
                    <div class="bg-gray-700 rounded-lg p-4 mb-4">
                        <div class="space-y-2">
                            ${order.items.map(item => `
                                <div class="flex justify-between">
                                    <span>${item.product_name} x${item.quantity}</span>
                                    <span>₱${parseFloat(item.subtotal).toFixed(2)}</span>
                                </div>
                            `).join('')}
                        </div>
                        <div class="border-t border-gray-600 mt-3 pt-3 flex justify-between font-bold text-yellow-600">
                            <span>Total</span>
                            <span>₱${parseFloat(order.total_amount).toFixed(2)}</span>
                        </div>
                    </div>
                    
                    <div class="flex gap-2">
                        ${order.status === 'pending' ? `
                            <button onclick="updateOrderStatus(${order.order_id}, 'preparing')" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Accept Order
                            </button>
                        ` : ''}
                        <button onclick="updateOrderStatus(${order.order_id}, 'completed')" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            Complete
                        </button>
                        <button onclick="updateOrderStatus(${order.order_id}, 'cancelled')" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                            Cancel
                        </button>
                    </div>
                </div>
            `).join('');
        }
    } catch (error) {
        console.error('Error loading orders:', error);
    }
}

async function loadOrderHistory() {
    try {
        const response = await fetch('api/get_orders.php?status=completed,cancelled');
        const data = await response.json();
        
        if (data.success && data.orders) {
            const tbody = document.getElementById('historyTable');
            
            if (data.orders.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center py-8 text-gray-400">No order history</td></tr>';
                return;
            }
            
            tbody.innerHTML = data.orders.map(order => `
                <tr class="border-t border-gray-700 hover:bg-gray-750">
                    <td class="px-6 py-4 text-white">${order.order_number}</td>
                    <td class="px-6 py-4 text-gray-300">${order.customer_name}</td>
                    <td class="px-6 py-4 text-yellow-600 font-semibold">₱${parseFloat(order.total_amount).toFixed(2)}</td>
                    <td class="px-6 py-4"><span class="status-badge status-${order.status}">${order.status}</span></td>
                    <td class="px-6 py-4 text-gray-400">${new Date(order.order_date).toLocaleString()}</td>
                </tr>
            `).join('');
        }
    } catch (error) {
        console.error('Error loading order history:', error);
    }
}

// Close modals on outside click
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.classList.remove('active');
    }
}

// Initialize on page load
window.addEventListener('DOMContentLoaded', function() {
    loadTestimonials();
    loadProducts();
    loadOrders();
    loadOrderHistory();

});
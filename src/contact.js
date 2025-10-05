document.getElementById('contactForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const errorMessage = document.getElementById('errorMessage');
    const successMessage = document.getElementById('successMessage');
    errorMessage.classList.add('hidden');
    successMessage.classList.add('hidden');

    // Get form data
    const formData = {
        first_name: document.getElementById('first_name').value.trim(),
        last_name: document.getElementById('last_name').value.trim(),
        email: document.getElementById('email').value.trim(),
        contact_number: document.getElementById('contact_number').value.trim(),
        subject: document.getElementById('subject').value.trim(),
        message: document.getElementById('message').value.trim()
    };

    // Basic validation
    if (!formData.first_name || !formData.last_name || !formData.email || !formData.message) {
        errorMessage.textContent = 'Please fill in all required fields.';
        errorMessage.classList.remove('hidden');
        return;
    }

    try {
        const response = await fetch('api/contact.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        });

        const data = await response.json();

        if (data.success) {
            successMessage.textContent = data.message;
            successMessage.classList.remove('hidden');
            
            // Clear form
            document.getElementById('contactForm').reset();
            
            // Scroll to success message
            successMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else {
            errorMessage.textContent = data.message;
            errorMessage.classList.remove('hidden');
        }
    } catch (error) {
        errorMessage.textContent = 'An error occurred. Please try again.';
        errorMessage.classList.remove('hidden');
    }
});
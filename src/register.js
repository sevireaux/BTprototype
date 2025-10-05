document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const errorMessage = document.getElementById('errorMessage');
            const successMessage = document.getElementById('successMessage');
            errorMessage.classList.add('hidden');
            successMessage.classList.add('hidden');

            // Get form data
            const formData = {
                username: document.getElementById('username').value,
                first_name: document.getElementById('first_name').value,
                last_name: document.getElementById('last_name').value,
                email: document.getElementById('email').value,
                contact_number: document.getElementById('contact_number').value,
                password: document.getElementById('password').value,
                confirm_password: document.getElementById('confirm_password').value
            };

            // Validate passwords match
            if (formData.password !== formData.confirm_password) {
                errorMessage.textContent = 'Passwords do not match!';
                errorMessage.classList.remove('hidden');
                return;
            }

            try {
                const response = await fetch('api/register.php', {
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
                    
                    // Redirect to login after 2 seconds
                    setTimeout(() => {
                        window.location.href = 'login.php';
                    }, 2000);
                } else {
                    errorMessage.textContent = data.message;
                    errorMessage.classList.remove('hidden');
                }
            } catch (error) {
                errorMessage.textContent = 'An error occurred. Please try again.';
                errorMessage.classList.remove('hidden');
            }
        });

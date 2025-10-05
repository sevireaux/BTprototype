// Login Form Handler
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const errorMessage = document.getElementById('errorMessage');
            const successMessage = document.getElementById('successMessage');
            errorMessage.classList.add('hidden');
            successMessage.classList.add('hidden');

            const formData = {
                username: document.getElementById('username').value,
                password: document.getElementById('password').value
            };

            try {
                const response = await fetch('api/login.php', {
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
                    
                    setTimeout(() => {
                        if (data.user.user_role === 'admin') {
                            window.location.href = 'admin-dashboard.php';
                        } else {
                            window.location.href = 'user-dashboard.php';
                        }
                    }, 1000);
                } else {
                    errorMessage.textContent = data.message;
                    errorMessage.classList.remove('hidden');
                }
            } catch (error) {
                errorMessage.textContent = 'An error occurred. Please try again.';
                errorMessage.classList.remove('hidden');
            }
        });
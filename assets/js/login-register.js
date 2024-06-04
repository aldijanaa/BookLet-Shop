$(document).on("registrationFormLoaded", function() {
    const registrationForm = document.getElementById('registrationForm');

    if (registrationForm) {
        console.log("Registration form found, adding event listener.");
        registrationForm.addEventListener('submit', function(event) {
            event.preventDefault();
           
            const userData = {
                first_name: document.getElementById('reg_first_name').value.trim(),
                last_name: document.getElementById('reg_last_name').value.trim(),
                email: document.getElementById('reg_email').value.trim(),
                password: document.getElementById('reg_password').value.trim(),
                confirm_password: document.getElementById('reg_confirm_password').value.trim()
            };

            fetch('backend/auth/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(userData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    alert('Registration successful!');
                    window.location.href = "#login";  // Redirect to login
                } else {
                    throw new Error('Registration failed: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert(error.message);
            });
        });
    } else {
        console.log("Registration form not found.");
    }
});



$(document).ready(function() {
    
    // Function to update the header
    function updateHeader() {
        const userToken = localStorage.getItem('jwt-token');
        const loginTextSpan = document.querySelector('.login-text');
        const dropdownContent = document.getElementById('dropdownContent');
        const userIcon = document.querySelector('.header-action-btn');  // Correctly selecting the user icon

        if (userToken) {
            loginTextSpan.style.display = 'none';  // Hide the "Login" text
            dropdownContent.style.display = 'block';  // Ensuring the dropdown is shown if logged in
            userIcon.classList.add('logged-in');      
        } else {
            loginTextSpan.style.display = 'block';  // Show the "Login" text
            loginTextSpan.textContent = 'Login';  // Ensure it says "Login"
            dropdownContent.style.display = 'none';  // Hide the dropdown menu
            userIcon.classList.remove('logged-in');  
        }
    }

    // Call updateHeader on page load to set the correct header state
    updateHeader();

    // Event Listener for Login Form
    $(document).on("loginFormLoaded", function() {
        const loginForm = document.getElementById('loginForm');

        if (loginForm) {
            console.log("Login form found, adding event listener.");
            loginForm.addEventListener('submit', function(event) {
                event.preventDefault();
                const email = document.getElementById('email').value.trim();
                const password = document.getElementById('password').value.trim();

                fetch('backend/auth/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ email, password })
                })
                .then(response => response.json())
                .then(data => {
                    if (data['jwt-token']) { 
                        localStorage.setItem('jwt-token', data['jwt-token']);
                        console.log('JWT Token stored:', localStorage.getItem('jwt-token'));

                        // Fetch user details
                        fetch(`backend/users/email/${email}`, {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                                'Authentication': ` ${data['jwt-token']}`
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! Status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(user => {
                            localStorage.setItem('user-id', user.id);  // Store user ID in local storage
                            console.log('User ID stored:', localStorage.getItem('user-id'));

                            alert('Login successful!');
                            updateHeader();  // Update header after login
                            window.location.href = "#home";
                        })
                        .catch(error => {
                            console.error('Error fetching user details:', error);
                            alert('Error fetching user details.');
                        });
                    } else if (data.error) {
                        throw new Error('Login failed: ' + data.error);
                    } else {
                        throw new Error('Login failed: Unknown error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert(error.message);
                });
            });
        }
    });

    // Dropdown behavior
    $('.user-icon-container').hover(function() {
        // Show the dropdown on hover if user is logged in
        if (localStorage.getItem('jwt-token')) {
            $('#dropdownContent').stop(true, true).slideDown();
        }
    }, function() {
        // Hide the dropdown when not hovering
        $('#dropdownContent').stop(true, true).slideUp();
    });

    function logout() {
        localStorage.removeItem('jwt-token'); // Remove token from local storage
        localStorage.removeItem('user-id'); // Remove user ID from local storage
        updateHeader(); // Update header after logout
        window.location.href = "#home"; // Optionally redirect to home
    }
});

$(document).on("registrationPageLoaded", function() {
    const registrationForm = document.getElementById('registrationForm');

    if (registrationForm) {
        console.log("Registration form found, adding event listener.");
        registrationForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const firstName = document.getElementById('reg_first_name').value.trim();
            const lastName = document.getElementById('reg_last_name').value.trim();
            const email = document.getElementById('reg_email').value.trim();
            const password = document.getElementById('reg_password').value.trim();
            const confirmPassword = document.getElementById('reg_confirm_password').value.trim();
            let formValid = true;

            // Field validations
            if (!firstName || !lastName || !email || !password || !confirmPassword) {
                alert('All fields must be filled out.');
                formValid = false;
            }

            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('Please enter a valid email address.');
                formValid = false;
            }

            // Password and confirm password match
            if (password !== confirmPassword) {
                alert('Passwords do not match.');
                formValid = false;
            }

            // Password strength (example: at least 6 characters)
            if (password.length < 6) {
                alert('Password should be at least 6 characters long.');
                formValid = false;
            }

            if (formValid) {
                const userData = {
                    first_name: firstName,
                    last_name: lastName,
                    email: email,
                    password: password,
                    confirm_password: confirmPassword
                };
                console.log("Submitting data:", userData);
                fetch('http://localhost/WEB_Projekat%20sa%20spappom/backend/scripts/user_scripts/register_user.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(userData)
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Response from server:', data);
                    if (data.message) {
                        alert('Registration successful!');
                        window.location.href = "#shop";   //redirecting gdje treba - kasnije
                    } else {
                        throw new Error('Registration failed: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert(error.message);
                });
            } else {
                console.error('Validation failed.');
            }
        });
    } else {
        console.log("Registration form not found.");
    }

    // Login Form 
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        console.log("Login form found, adding event listener.");
        loginForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();
            let formValid = true;

            // Validation for login form
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('Please enter a valid email address.');
                formValid = false;
            }

            if (password === '') {
                alert('Please enter your password.');
                formValid = false;
            }

            if (formValid) {
                const loginData = { email, password };
                console.log("Submitting login data:", loginData);
                fetch('http://localhost/WEB_Projekat%20sa%20spappom/backend/scripts/user_scripts/login_user.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(loginData)
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Response from server:', data);
                    if (data.message) {
                        alert('Login successful!');
                        window.location.href = "#home";  //redirecting gdje treba - kasnije
                    } else {
                        throw new Error('Login failed: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert(error.message);
                });
            } else {
                console.error('Validation failed.');
            }
        });
    } else {
        console.log("Login form not found.");
    }


});

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
                    localStorage.setItem('jwt-token', data['jwt-token']);  //saving jwt into the local storage  during the login
                    console.log('JWT Token stored:', localStorage.getItem('token'));

                    alert('Login successful!');
                    window.location.href = "#home";
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



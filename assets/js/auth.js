function logout() {
    fetch('backend/auth/logout', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('jwt-token')}`
        }
    }).then(response => response.json())
      .then(data => {
        console.log(data.message); // Log the response message

        // Clear the JWT from local storage or wherever it's stored
        localStorage.removeItem('jwt-token');
        
        // Redirect to login or home page
        alert('Logout successful! JWT Token deleted from local storage. Redirecting to login page...');
        window.location.href = '#login-register';
      })
      .catch(error => console.error('Error logging out:', error));
}

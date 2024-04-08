//Method: Check if both fields are filled in
 checkFields = () => {
    
    //get elements
    const emailInput = document.getElementById("email");  
    const loginButton = document.getElementById("loginButton");

    let isEmailFilled = emailInput.value.trim() !== "";  //if email field is not empty (not equal to whitespace) and store it in variale isEmailFilled
    if (isEmailFilled) {  //ako je email field popunjen
        loginButton.disabled = false; // Enable the login button
    } else {
        loginButton.disabled = true; // Disable the login button
    }
}


// Validate the form on submission
 validateForm = () =>{
    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("password");
    var validRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;

    // Check the email format
    if (!emailInput.value.match(validRegex)) {   //ako email nije u dobrom formatu
        alert("Invalid email format. Please enter a valid email address.");
        emailInput.focus();  //stavi fokus na email field
        return false; // Prevent form submission
    }

    // Check if the password field is filled in
    if (passwordInput.value.trim() === "") {  //if password field is empty
        alert("Invalid password. Please enter your password.");
        passwordInput.focus();   //sets focus on password field
        return false; // Prevent form submission
    }
    // Form is valid, allow submission
    return true;
}



//REGISTER LOGIC
checkFieldsRegister = () => {
    
    //Get elements
    const usernameInput = document.getElementById("username");
    const emailInput = document.getElementById("email2");  
    const passwordInput = document.getElementById("password2");
    const confirmPasswordInput = document.getElementById("confirm-password2");
    const signinButton = document.getElementById("signInButton");

    let isEmailFilled = emailInput.value.trim() !== "";  //if email field is not empty (not equal to whitespace) and store it in variale isEmailFilled
    let isPasswordFilled = passwordInput.value.trim() !== "";  //if password field is not empty (not equal to whitespace) and store it in variale isPasswordFilled
    let isConfirmPasswordFilled = confirmPasswordInput.value.trim() !== ""; 
    let isUsernameFilled = usernameInput.value.trim() !== "";

    //Disable sign in button if any of the fields are empty
    signinButton.disabled = !(isUsernameFilled && isEmailFilled && isPasswordFilled && isConfirmPasswordFilled);  //reverse logic, if any of the fields are empty, disable the button

}


validateFormRegister = () =>{
    let usernameInput = document.getElementById("username");
    let emailInput = document.getElementById("email2");
    let passwordInput = document.getElementById("password2");
    let confirmPasswordInput = document.getElementById("confirm-password2");
    let validRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
    let passwordStrengthRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/; // Example regex for password strength (1 lowercase, 1 uppercase, 1 digit, 1 special character, atleast 8 characters long)


     // Check if any field is filled in
     if (usernameInput.value.trim() === "" && emailInput.value.trim() === "" && passwordInput.value.trim() === "" && confirmPasswordInput.value.trim() === "") {
        alert("Please fill in the necessary fields.");
        usernameInput.focus();
        return false;
    }
     // Check if username field is filled in
     if (usernameInput.value.trim() === "") {
        alert("Please enter your username.");
        usernameInput.focus();
        usernameInput.select();
        return false;
    }
    
    // Check if the email field is filled in and in the correct format
    if (emailInput.value.trim() === "") {
        alert("Invalid email. Please enter your email address.");
        emailInput.focus();
        emailInput.select();
        return false;
    } else if (!emailInput.value.match(validRegex)) {
        alert("Invalid email format. Please enter a valid email address.");
        emailInput.focus();
        emailInput.select();
        return false;
    }
    // Check if the password field is filled in and meets strength requirements
    if (passwordInput.value.trim() === "") {
        alert("Invalid password. Please enter your password.");
        passwordInput.focus();
        passwordInput.select();
        return false;
    } else if (!passwordInput.value.match(passwordStrengthRegex)) {
        alert("Password does not meet the strength requirements. Must contain 1 lowercase, 1 uppercase, 1 digit, 1 special character, and be at least 8 characters long.");
        passwordInput.focus();
        passwordInput.select();
        return false; 
    }

    // Check if the confirm password field is filled in and matches the password
    if (confirmPasswordInput.value.trim() === "") {
        alert("Please confirm your password.");
        confirmPasswordInput.focus();
        confirmPasswordInput.select();
        return false;
    } else if (confirmPasswordInput.value !== passwordInput.value) {
        alert("Passwords do not match. Please confirm your password.");
        confirmPasswordInput.focus();
        confirmPasswordInput.select();
        return false;
    }


    // Form is valid, allow submission
    return true;
}

//Back button
document.getElementById("closeButton").addEventListener("click", function() {
    window.location.href = "index.html"; // Redirect to the homepage (for now)
});





'use strict';


/**
 * add event on elements
 */

const addEventOnElem = function (elem, type, callback) {
    if (elem.length > 1) {
      for (let i = 0; i < elem.length; i++) {
        elem[i].addEventListener(type, callback);
      }
    } else {
      elem.addEventListener(type, callback);
    }
  }
  
/*navbar toogle -- ovo da postane navbar aktivan*/
const navbar = document.querySelector("[data-navbar]");
const navTogglers = document.querySelectorAll("[data-nav-toggler]");
const overlay = document.querySelector("[data-overlay]");

const toggleNavbar = function () {
  navbar.classList.toggle("active");
  overlay.classList.toggle("active");
}

addEventOnElem(navTogglers, "click", toggleNavbar);

/**
 * active header & back top btn when window scroll down to 100px
 */

const header = document.querySelector("[data-header]");
const backTopBtn = document.querySelector("[data-back-top-btn]");

const activeElemOnScroll = function () {
  if (window.scrollY > 100) {
    header.classList.add("active");
    backTopBtn.classList.add("active");
  } else {
    header.classList.remove("active");
    backTopBtn.classList.remove("active");
  }
}

addEventOnElem(window, "scroll", activeElemOnScroll);



/* REMOVED CONTACT FROM HEADER NAV LINKS
when clicking on the Contact in navbar, scroll to the bottom of the document to view contact info*/
/*document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
     e.preventDefault();

     document.querySelector(this.getAttribute('href')).scrollIntoView({
        behavior: 'smooth'
     });
  });
});*/


//dropdown on user ikonici
function myFunction() {
  document.getElementById("myDropdown").classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}
function updateUserIcon() {
  $.ajax({
      url: 'backend/auth/status', // Endpoint to check if the user is logged in
      type: 'GET',
      success: function(response) {
          const container = document.getElementById('userIconContainer');
          if (response.logged_in) {
              container.innerHTML = `
                  <div class="dropdown">
                      <button onclick="myFunction()" class="dropbtn">Profile</button>
                      <div id="myDropdown" class="dropdown-content">
                          <a href="#profile">My Profile</a>
                          <a href="#logout" onclick="logout()">Logout</a>
                      </div>
                  </div>`;
          } else {
              container.innerHTML = `
                  <a href="#login" class="header-action-btn" title="Login">
                      <ion-icon name="person-outline"></ion-icon>
                      <span>Login</span>
                  </a>`;
          }
      },
      error: function() {
          console.log('Error checking login status');
      }
  });
}

document.addEventListener('DOMContentLoaded', function() {
  updateUserIcon();
});


function logout() {
  $.ajax({
      url: 'backend/auth/logout', // Assuming you have a logout endpoint that clears the session
      type: 'POST',
      success: function() {
          localStorage.removeItem('jwtToken'); // Clear JWT token if you stored it in local storage
          window.location.href = '#login'; // Redirect to login page or home
          updateUserIcon(); // Update UI elements
      },
      error: function() {
          console.log('Error logging out');
      }
  });
}


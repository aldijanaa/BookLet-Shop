alert("Script execution started.");


// Listen for the custom event that indicates the page has loaded the necessary section
$(document).on("popularProductsPageLoaded", function () {
  alert("Event 'popularProductsPageLoaded' has been triggered.");
  waitForElement(".product-list.has-scrollbar", loadProducts);
});

// Function to load products based on the filter
function loadProducts(filter = 'all') { 
  const fileName = `./data/index_${filter}_products.json`;
  fetch(fileName)
      .then(response => {
          if (!response.ok) {
              throw new Error('Network response was not ok ' + response.statusText);
          }
          return response.json();
      })
      .then(data => {
          const productList = document.querySelector('.product-list.has-scrollbar');
          if (!productList) {
              console.error("Product list element not found, retrying...");
              return;
          }
          console.log('Product data loaded:', data);
          renderProducts(data, productList);
      })
      .catch(error => {
          console.error('Error loading product data:', error);
          alert("Error rendering products: " + error.message);

      });
}


// Function to render products
function renderProducts(data, productList) {
  try {
    const productsHTML = data.map(product => {
        // Ensure product has a stars array before trying to map it
        const starsHTML = product.stars ? product.stars.map(star => `<ion-icon name="${star ? 'star' : 'star-outline'}" aria-hidden="true"></ion-icon>`).join('') : '';
        return `
            <li class="scrollbar-item">
                <div class="product-card">
                    <div class="card-banner img-holder" style="--width: 384; --height: 480;">
                        <img src="${product.image}" width="384" height="480" loading="lazy" alt="${product.title}" class="img-cover">
                    </div>
                    <div class="card-content">
                        <h3 class="h3">
                            <a href="#" class="card-title">${product.title}</a>
                        </h3>
                        <data class="card-price" value="${product.price}">${product.price} BAM</data>
                        <div class="rating-wrapper">
                            ${starsHTML}
                        </div>
                    </div>
                </div>
            </li>
        `;
    }).join('');

    productList.innerHTML = productsHTML;
    document.dispatchEvent(new CustomEvent('productsLoaded')); // Trigger the custom event after products are loaded
  } catch (error) {
    console.error('Error rendering products:', error);
  }
}

// Function to wait for an element to be available before executing a callback
function waitForElement(selector, callback) {
  const element = document.querySelector(selector);
  if (element) {
      callback();
  } else {
      setTimeout(() => waitForElement(selector, callback), 500);
  }
}
// Set up event delegation for filter buttons, assuming .filter-buttons is a permanent container for the buttons
$(document).ready(function() {
  document.dispatchEvent(new CustomEvent('popularProductsPageLoaded'));

  $('.filter-buttons').on('click', '.filter-button', function() {
    const filterValue = $(this).data('filter');
    loadProducts(filterValue);
  });
});
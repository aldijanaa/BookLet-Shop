$(document).on("cartPageLoaded", function () {
  alert("Event 'cartPageLoaded' has been triggered.");
  loadCartItems();
});

function loadCartItems() {
  const url = "./data/cart.json";
  $.ajax({
      url: url,
      method: 'GET',
      dataType: 'json',
      success: function(data) {
          renderCartItems(data);
          console.log('Cart data loaded:', data);
      },
      error: function(xhr, status, error) {
          console.error('Error loading cart data:', status, error);
          alert("Error loading cart items: " + error);
      }
  });
}

function renderCartItems(data) {
  const cartItemsContainer = $('.cart-items');
  if (!cartItemsContainer.length) {
      console.error("Cart items container not found, retrying...");
      return;
  }
  cartItemsContainer.empty(); // Clear existing items

  const itemsHTML = data.map(item => {
      return `
          <div class="cart-item">
              <div class="item-description">
                  <img src="${item.imgSrc}" alt="${item.title}">
                  <div class="text-description">
                      <p class="title">${item.title}</p>
                      <p class="author">${item.author}</p>
                  </div>
                  <p class="unit-price">${item.unitPrice} BAM</p>
              </div>
              <div class="quantity-selector">
                  <button class="decrease-quantity">-</button>
                  <input type="text" value="${item.quantity}" readonly>
                  <button class="increase-quantity">+</button>
              </div>
              <p class="final-price">${item.finalPrice} BAM</p>
              <button class="remove-item">X</button>
          </div>
      `;
  }).join('');

  cartItemsContainer.html(itemsHTML);
  $(document).trigger('cartItemsRendered'); // Trigger custom event after rendering cart items
}

$(document).ready(function() {
  if (window.location.hash === '#cart') {
      $(document).trigger("cartPageLoaded");
  }
});

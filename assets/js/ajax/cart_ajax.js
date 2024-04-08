document.addEventListener('DOMContentLoaded', function() {
    fetch('../../data/cart.json')
      .then(response => response.json())
      .then(data => {
        let cartItemsContainer = document.querySelector('.cart-items');
        cartItemsContainer.innerHTML = ''; // Clear existing items
        
        data.forEach(item => {
          cartItemsContainer.innerHTML += `
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
        });
      })
      .catch(error => console.error('Error fetching cart items:', error));
  });
  
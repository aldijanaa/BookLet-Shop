document.addEventListener('DOMContentLoaded', function() {
    const productList = document.querySelector('.featured .grid-list');

    function loadFeaturedBooks() {
        const fileName = `../../../data/index_featured_collection.json`;
        fetch(fileName)
            .then(response => response.json())
            .then(data => {
                console.log('Featured collections:', data);
                data.forEach(book => {
                    const liElement = document.createElement('li');
                    liElement.innerHTML = `
                        <div class="product-card" data-book-id="${book.id}">
                            ${book.new ? '<span class="card-badge">New</span>' : ''}
                            <div class="card-banner img-holder" style="--width: 384; --height: 480;">
                                <img src="${book.image}" width="384" height="480" loading="lazy" alt="${book.name}" class="img-cover">
                                <div class="card-action">
                                    <button class="action-btn" aria-label="quick view" title="Quick View">
                                        <ion-icon name="eye-outline" aria-hidden="true"></ion-icon>
                                    </button>
                                    <button class="action-btn add-to-wishlist" aria-label="add to wishlist" title="Add to Wishlist">
                                        <ion-icon name="heart-outline" aria-hidden="true"></ion-icon>
                                    </button>
                                    <button class="action-btn add-to-cart" aria-label="add to cart" title="Add to Cart">
                                        <ion-icon name="bag-handle-outline" aria-hidden="true"></ion-icon>
                                    </button>
                                </div> 
                            </div>
                            <div class="card-content">
                                <h3 class="h3">
                                    <a href="#" class="card-title">${book.name}</a>
                                </h3>
                                <data class="card-price" value="${parseFloat(book.price)}">${book.price}</data>
                                <div class="rating-wrapper">
                                    <ion-icon name="star-outline" aria-hidden="true"></ion-icon>
                                    <ion-icon name="star-outline" aria-hidden="true"></ion-icon>
                                    <ion-icon name="star-outline" aria-hidden="true"></ion-icon>
                                    <ion-icon name="star-outline" aria-hidden="true"></ion-icon>
                                    <ion-icon name="star-outline" aria-hidden="true"></ion-icon>
                                </div>
                            </div>
                        </div>
                    `;
                    productList.appendChild(liElement);
                    // Attach event listeners to buttons
                    attachEventListeners(liElement, book.id);
                });
            })
            .catch(error => {
                console.error('Error fetching featured collections:', error);
            });
    }

    function attachEventListeners(bookElement, bookId) {
        const addToWishlistBtn = bookElement.querySelector('.add-to-wishlist');
        const addToCartBtn = bookElement.querySelector('.add-to-cart');
        
        addToWishlistBtn.addEventListener('click', () => addToWishlist(bookId));
        addToCartBtn.addEventListener('click', () => addToCart(bookId));
    }

    function addToWishlist(bookId) {
        ajaxPost('/add-to-wishlist', { bookId: bookId })
            .catch(error => console.log(`Error adding book ID ${bookId} to favorites:`, error));
    }

    function addToCart(bookId) {
        ajaxPost('/add-to-cart', { bookId: bookId })
            .catch(error => console.log(`Error adding book ID ${bookId} to cart:`, error));   //the route doesn't exist currently and therefore, the error will be caught
    }

    function ajaxPost(url, data) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', url);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.onload = () => {
                if (xhr.status >= 200 && xhr.status < 300) {
                    resolve(xhr.response);
                } else {
                    reject(xhr.statusText);
                }
            };
            xhr.onerror = () => reject(xhr.statusText);
            xhr.send(JSON.stringify(data));
        });
    }

    loadFeaturedBooks();
});

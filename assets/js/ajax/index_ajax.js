document.addEventListener('DOMContentLoaded', function() {
    const productList = document.querySelector('.product-list');

    // Function to load products based on the filter. If no filter is specified, all products are loaded.
    function loadProducts(filter = 'all') {
        // Adjust the file path based on the filter. Default is 'all' for loading all products.
        const fileName = `../../../data/index_${filter}_products.json`;
        fetch(fileName)
            .then(response => response.json())
            .then(data => {
                // Map data to HTML and display it
                const productsHTML = data.map(product => {
                    return `
                        <li class="scrollbar-item">
                            <div class="product-card">
                                <div class="card-banner img-holder" style="--width: 384; --height: 480;">
                                    <img src="${product.image}" width="384" height="480" loading="lazy" alt="${product.title}" class="img-cover">
                                    <div class="card-action">
                                        <button class="action-btn" aria-label="quick view" title="Quick View">
                                            <ion-icon name="eye-outline" aria-hidden="true"></ion-icon>
                                        </button>
                                        <button class="action-btn" aria-label="add to wishlist" title="Add to Wishlist">
                                            <ion-icon name="heart-outline" aria-hidden="true"></ion-icon>
                                        </button>
                                        <button class="action-btn" aria-label="add to cart" title="Add to Cart">
                                            <ion-icon name="bag-handle-outline" aria-hidden="true"></ion-icon>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-content">
                                    <h3 class="h3">
                                        <a href="#" class="card-title">${product.title}</a>
                                    </h3>
                                    <data class="card-price" value="${product.price}">${product.price} BAM</data>
                                    <div class="rating-wrapper">
                                        ${product.stars.map(star => `<ion-icon name="${star ? 'star' : 'star-outline'}" aria-hidden="true"></ion-icon>`).join('')}
                                    </div>
                                </div>
                            </div>
                        </li>
                    `;
                }).join('');
                productList.innerHTML = productsHTML;
            })
            .catch(error => {
                console.error('Error loading product data:', error);
                console.log(`Failed to load products for filter: ${filter}`);
            });
    }

    // Attach click event listeners to filter buttons
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter-btn');
            loadProducts(filter);
        });
    });

    // Load all products by default
    loadProducts('all'); // Assuming 'all' leads to a JSON file that contains every product.
});

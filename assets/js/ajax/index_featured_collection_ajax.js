// Wait for the custom event before fetching books
$(document).on("booksPageLoaded", function () {
	waitForElement(".featured .grid-list", loadFeaturedBooks);
});

// Function to load featured books and append them to the productList
function loadFeaturedBooks() {
    fetch('./data/index_featured_collection.json')  //mijenjati rutu u backendu!!!!
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            const productList = document.querySelector('.featured .grid-list');
            if (!productList) {
                console.error("Couldn't find the product list element.");
                return;
            }
            console.log('Featured collections:', data);
            productList.innerHTML = data.map(book => `
                <li>
                    <div class="product-card" data-book-id="${book.id}">
                        ${book.new ? '<span class="card-badge">New</span>' : ''}
                        <div class="card-banner img-holder" style="--width: 384; --height: 480;">
                            <img src="${book.image}" width="384" height="480" loading="lazy" alt="${book.name}" class="img-cover">
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
                                <ion-icon name="star-outline" aria-hidden="false"></ion-icon>
                                <ion-icon name="star-outline" aria-hidden="true"></ion-icon>

                                <!-- other stars... -->
                                
                            </div>
                        </div>
                    </div>
                </li>
            `).join('');
        })
        .catch(error => {
            console.error('Error fetching featured collections:', error);
        });
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


$(document).on("shopPageLoaded", function() {
    loadBooks();     // Load initial set of books

    setupInfiniteScrolling();  // Setup infinite scrolling to load more books

    setupInteractions();      // Setup interactions for modals and other UI elements
});

let currentBookIndex = 0;
const booksPerPage = 11;
let data;  // This will store the fetched books data

function loadBooks() {
    if (!data) {
        fetch('backend/books/all?offset=' + currentBookIndex + '&limit=' + booksPerPage)  //works
        .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.statusText);
                }      
                return response.json();
            })
            .then(fetchedData => {
                data = fetchedData;
                displayBooks();
            })
            .catch(error => console.error('Error loading book data:', error));
    } else {
        displayBooks();  
    }
}


function displayBooks() {
    const productList = $('.grid-list');
    const booksToLoad = data.slice(currentBookIndex, currentBookIndex + booksPerPage);
    booksToLoad.forEach(book => {
        const bookHTML = createBookHTML(book);
        productList.append(bookHTML);
    });
    currentBookIndex += booksPerPage;
}


function createBookHTML(book) {
    return `
        <li>
            <div class="product-card">
                <div class="card-banner img-holder" style="--width: 384; --height: 480;">
                    <img src="${book.image}" width="384" height="480" loading="lazy" alt="${book.title}" class="img-cover">
                    <div class="card-action">
                        <button class="action-btn" aria-label="quick view" title="Quick View" data-book-id="${book.id}">
                            <ion-icon name="eye-outline" aria-hidden="true"></ion-icon>
                        </button>
                        <button class="action-btn" aria-label="add to wishlist" title="Add to Wishlist" data-book-id="${book.id}">
                            <ion-icon name="heart-outline" aria-hidden="true"></ion-icon>
                        </button>
                        <button class="action-btn" aria-label="add to cart" title="Add to Cart" data-book-id="${book.id}">
                            <ion-icon name="bag-handle-outline" aria-hidden="true"></ion-icon>
                        </button>
                    </div>
                </div>
                <div class="card-content">
                    <h3 class="h3">
                        <a href="#" class="card-title">${book.title}</a>
                    </h3>
                    <data class="card-price" value="${book.price}">${book.price} BAM</data>
                </div>
            </div>
        </li>
    `;
}

function setupInfiniteScrolling() {
    $(window).on('scroll', function() {
        if ($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
            loadBooks();
        }
    });
}


// At the top of the shop_ajax.js file
function updateCartIcon(count) {
    $('#cart-icon-count').text(count);
}

function fetchCartCount() {
    $.ajax({
        url: 'backend/user/40/cart/count', // Adjust the user ID as needed
        method: 'GET',
        success: function(response) {
            updateCartIcon(response.count);
        },
        error: function() {
            console.error('Failed to fetch cart count.');
        }
    });
}
// Update the setupInteractions() function
/*function setupInteractions() {
    // Interactions for each product card, delegate the event from the static parent
    $(document).on('click', '.grid-list .add-to-cart-btn', function(event) {
        const bookId = $(this).data('book-id');
        addToCart(bookId, 1); // Assuming quantity is always 1 for now
    });
}*/

// Setup interactions for the product cards
/*function setupInteractions() {
    const modal = $("#bookDetailModal");
    
    // Interactions for each product card, delegate the event from the static parent
    $(document).on('click', '.grid-list .action-btn', function(event) {
        const bookId = $(this).data('book-id');
        const action = $(this).data('action');


        const book = data.find(b => b.id === parseInt(bookId, 10)); // Ensures both are of type number

        if (book) {
            if ($(this).attr('aria-label') === "quick view") {
                showModal(book);
            } else if ($(this).attr('aria-label') === "add to wishlist") {
                addToWishlist(book);
            }
            // Add more interactions as needed
        }


        // Handle the 'add to cart' action  
        if (action === 'add-to-cart') {
            addToCart(bookId, 1); 
        }

    });

    // Close the modal when the 'x' button is clicked
    modal.find(".close").on('click', function() {
        modal.hide();
    });

    // Close the modal when clicking outside of it
    $(window).on('click', function(event) {
        if ($(event.target).is(modal)) {
            modal.hide();
        }
    });

    // Interactions for each product card
    $(".grid-list").on('click', '.action-btn', function(event) {
        const bookId = $(this).data('book-id');
        const book = data.books.find(b => b.id === bookId);

        if (book) {
            if ($(this).attr('aria-label') === "quick view") {
                showModal(book);
            } else if ($(this).attr('aria-label') === "add to wishlist") {
                addToWishlist(book);
            }else if ($(this).attr('aria-label') === "add to cart") {
                addToCart(bookId, 1);
            }
        }
    });
}*/
// In the document ready block
$(document).ready(function () {
    fetchCartCount(); // Initialize the cart count when the page is ready
});

// Setup interactions for the product cards
function setupInteractions() {
    $(document).on('click', '.product-card .action-btn', function() {
        const action = $(this).attr('aria-label');
        const bookId = $(this).data('book-id');

        if (action === "add to cart") {
            addToCart(bookId, 42); // Assumes quantity is always 1 for simplicity  - ZA SADA
        }
    });
}


//Add to cart function
function addToCart(bookId, quantity) {
    $.ajax({
        url: 'backend/add-to-cart',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ book_id: bookId, quantity: quantity, user_id: 40 }), // Change as per your user session management
        success: function(response) {
            alert('Item successfully added to cart!');
            fetchCartCount(); // Refresh the cart count
        },
        error: function() {
            alert('Failed to add item to cart.');
        }
    });
}


/*function showModal(book) { 
    const modal = $("#bookDetailModal");
    // Update modal content with book details
    modal.find(".modal-book-image").attr("src", book.image).attr("alt", book.title);
    modal.find(".modal-book-title").text(book.title);
    modal.find(".modal-book-author").text(book.author);
    modal.find(".modal-book-price").text(`${book.price} BAM`);
    modal.find(".modal-book-description").text(book.description);

    // Create stars HTML based on the book's integer star rating
    let starsHtml = '';
    for (let i = 0; i < book.stars; i++) {
        starsHtml += '<ion-icon name="star"></ion-icon>';
    }
    for (let i = book.stars; i < 5; i++) {
        starsHtml += '<ion-icon name="star-outline"></ion-icon>';
    }
    modal.find(".modal-book-stars").html(starsHtml);

    // Show the modal
    modal.show();
}

function addToWishlist(book) {
    const favoritePopup = $("#favoritePopup");
    favoritePopup.text(`Added ${book.title} to favorites`).show();
    setTimeout(() => {
        favoritePopup.hide();
    }, 2000);
    // Implement the logic to actually add the book to the wishlist
}

// Ensure the document is ready before loading the books
$(document).ready(function () {
    if (window.location.hash === '#shop') {
        $(document).trigger("shopPageLoaded");
    }
});*/

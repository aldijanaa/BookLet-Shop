$(document).on("shopPageLoaded", function() {
    // Load initial set of books
    loadBooks();

    // Setup infinite scrolling to load more books
    setupInfiniteScrolling();

    // Setup interactions for modals and other UI elements
    setupInteractions();
});

let currentBookIndex = 0;
const booksPerPage = 10;
let data;  // This will store the fetched books data

function loadBooks() {
    if (!data) {
        fetch('http://localhost/WEB_Projekat%20sa%20spappom/backend/scripts/get_all_books.php')
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
    // If data is an array, use it directly. Remove `data.books`.
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

function setupInteractions() {
    const modal = $("#bookDetailModal");
    
    // Interactions for each product card, delegate the event from the static parent
    $(document).on('click', '.grid-list .action-btn', function(event) {
        const bookId = $(this).data('book-id');
        // Since `data` variable is an array now, we access it directly.
        const book = data.find(b => b.id == bookId); // Ensure the type matches, == instead of ===

        if (book) {
            if ($(this).attr('aria-label') === "quick view") {
                showModal(book);
            } else if ($(this).attr('aria-label') === "add to wishlist") {
                addToWishlist(book);
            }
            // Add more interactions as needed
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
            }
            // Add more interactions as needed
        }
    });
}

function showModal(book) { 
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
});

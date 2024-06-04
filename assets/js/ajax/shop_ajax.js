$(document).on("shopPageLoaded", function() {
    loadBooks(); 
});

const booksPerPage = 50;
let booksData = [];  // This will store the books data

function loadBooks() {
    const url = `backend/books/all?limit=${booksPerPage}&order=id`;
    fetch(url, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Authentication': `${localStorage.getItem('jwt-token')}`  
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.statusText);
        }      
        return response.json();
    })
    .then(books => {
        booksData = books; // Store the fetched books in booksData
        displayBooks(books);
        setupInteractions();  // Ensure interactions are setup after books are displayed
    })
    .catch(error => console.error('Error loading book data:', error));
}

function displayBooks(books) {
    const productList = $('.grid-list');
    productList.empty(); // Clear previous books
    books.forEach(book => {
        const bookHTML = $(createBookHTML(book));
        productList.append(bookHTML);
    });
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

function setupInteractions() {
    // Interaction setup to use global booksData array
    $('.grid-list').on('click', '.action-btn[aria-label="quick view"]', function() {
        const bookId = $(this).data('book-id');
        const book = booksData.find(b => b.id === bookId);
        if (book) {
            showModal(book);
        }
    });

    // Add to favorites interaction
    $('.grid-list').on('click', '.action-btn[aria-label="add to wishlist"]', function() {
        const bookId = $(this).data('book-id');
        const userId = localStorage.getItem('user-id'); // Example: retrieving user ID from local storage
        addToFavorites(userId, bookId);
    });

    // Add to cart interaction
    $('.grid-list').on('click', '.action-btn[aria-label="add to cart"]', function() {
        const bookId = $(this).data('book-id');
        addToCart(bookId);
    });

    $('#bookDetailModal .close').on('click', function() {
        $('#bookDetailModal').hide();
    });

    $(document).on('click', function(event) {
        if ($(event.target).is('#bookDetailModal, #bookDetailModal *')) {
            $('#bookDetailModal').hide();
        }
    });
}

function addToFavorites(userId, bookId) {
    const url = 'backend/favorites/add-favorites';
    console.log("Adding to favorites", { user_id: userId, book_id: bookId }); // Check the values being sent

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authentication': `${localStorage.getItem('jwt-token')}` // Assuming you store JWT in localStorage
        },
        body: JSON.stringify({ user_id: userId, book_id: bookId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.id) {
            alert('Added to favorites successfully!'); // Success feedback
        } else {
            throw new Error('Failed to add to favorites');
        }
    })
    .catch(error => {
        console.error('Error adding to favorites:', error);
        alert('Error adding to favorites.'); // Error feedback
    });
}

function showModal(book) {
    const modal = $("#bookDetailModal");
    modal.find(".modal-book-image").attr("src", book.image);
    modal.find(".modal-book-title").text(book.title);
    modal.find(".modal-book-author").text(book.author);
    modal.find(".modal-book-price").text(`${book.price} BAM`);
    modal.find(".modal-book-description").text(book.description);
    modal.show();
}


function addToCart(bookId) {
    const url = 'backend/add-to-cart';
    const userId = localStorage.getItem('user-id'); // Retrieve user ID from local storage

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authentication': `${localStorage.getItem('jwt-token')}` // Assuming you store JWT in localStorage
        },
        body: JSON.stringify({ user_id: userId, book_id: bookId, quantity: 1 }) // Include user_id and quantity
    })
    .then(response => response.json())
    .then(data => {
        if (data.data) {
            alert('Added to cart successfully!'); // Success feedback
        } else {
            throw new Error('Failed to add to cart');
        }
    })
    .catch(error => {
        console.error('Error adding to cart:', error);
        alert('Error adding to cart.'); // Error feedback
    });
}
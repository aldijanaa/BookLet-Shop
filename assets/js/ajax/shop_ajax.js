document.addEventListener('DOMContentLoaded', function() {
    const productList = document.querySelector('.grid-list');
    let currentBookIndex = 0;
    const booksPerPage = 10; // Adjust this number as needed

    let data;  // Declare data outside the function
    function loadBooks() {
        fetch('../../../data/shop_data.json')
            .then(response => response.json())
            .then(fetchedData => {
                data = fetchedData;  // Assign fetched data to the global variable
                const books = data.books.slice(currentBookIndex, currentBookIndex + booksPerPage);
                books.forEach(book => {
                    const bookItem = `  
                        <li>
                            <div class="product-card">
                                <div class="card-banner img-holder" style="--width: 384; --height: 480;">
                                    <img src="${book.image}" width="384" height="480" loading="lazy" alt="${book.title}" class="img-cover">
                                    <div class="card-action">
                                        <button class="action-btn" aria-label="quick view" title="Quick View" data-book-id="${book.id}">
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
                                        <a href="#" class="card-title">${book.title}</a>
                                    </h3>
                                    <data class="card-price" value="${book.price}">${book.price} BAM</data>
                                </div> 
                            </div>
                        </li>
                    `;
                    productList.innerHTML += bookItem;
                });
                currentBookIndex += booksPerPage;
                console.log(`Loaded ${books.length} more books. Total loaded: ${currentBookIndex}`);  //ovo samo za provjeriti da li radi lazy scroll
                

            })
            .catch(error => console.error('Error loading book data:', error));
    }

    // Load initial set of books
    loadBooks();

    //scroll event listener to load more books when reaching the bottom
    window.addEventListener('scroll', () => {
        if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 500) {
            loadBooks();
        }
    });

    
    //MODAL --w3schools
    var modal = document.getElementById("bookDetailModal");  //get the modal

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];


    // Add event listener for the close button
    span.addEventListener('click', function() {
        modal.style.display = "none";
        document.querySelector('main').classList.remove('blur-background'); // Remove blur from background

    });

    // Add event listener for clicks outside of the modal
    window.addEventListener('click', function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
            document.querySelector('main').classList.remove('blur-background'); // Remove blur from background

        }
    });

    productList.addEventListener('click', function(event) {
        if (event.target.closest('.action-btn')) {
            const bookId = event.target.closest('.action-btn').getAttribute('data-book-id');
            const book = data.books.find(book => book.id == parseInt(bookId));
            console.log("Book:", book);
    
            if (book) {
                document.querySelector('.modal-book-image').src = book.image;
                document.querySelector('.modal-book-image').alt = book.title;
                document.querySelector('.modal-book-title').textContent = book.title;
                document.querySelector('.modal-book-author').textContent = book.author;
                document.querySelector('.modal-book-price').textContent = ` ${book.price} BAM`;
                
                // Update stars
                const starsContainer = document.querySelector('.modal-book-stars');
                starsContainer.innerHTML = '';  // Clear previous stars
                book.stars.forEach(star => {
                    const starIcon = document.createElement('ion-icon');
                    starIcon.name = star ? 'star' : 'star-outline';
                    starsContainer.appendChild(starIcon);
                });
                
                document.querySelector('.modal-book-description').textContent = book.description;  //update description of the book dynamically by 
            }
            
            // Check if the clicked button is the heart icon (favorites)
            if (event.target.closest('.action-btn').getAttribute('aria-label') === 'add to wishlist') {
                const favoritePopup = document.getElementById('favoritePopup');
                favoritePopup.style.display = 'block'; // Show the popup
                console.log('Added to favorites:', bookId);  //ovo za promjenu 
                setTimeout(() => {
                    favoritePopup.style.display = 'none'; // Hide the popup after 2 seconds
                }, 2000);
            }
            else if (event.target.closest('.action-btn').getAttribute('aria-label') === 'quick view') {
                // Only display the modal if the quick view button was clicked
                modal.style.display = "block";
                document.querySelector('main').classList.add('blur-background'); // Add blur to background
            }
        }
    });
    
    

});


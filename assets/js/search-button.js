document.addEventListener("DOMContentLoaded", function() {
    const searchButton = document.getElementById('searchButton');
    const searchInput = document.querySelector('input[name="search"]');  // Make sure this selector matches your input element

    if (!searchInput) {
        console.error('searchInput is not found in the document.');
        return;  // Exit if searchInput is not found
    }

    searchButton.addEventListener('click', function() {
        console.log("Button clicked");  // Confirm button press
        const searchTerm = searchInput.value;
        if (!searchTerm) {
            alert('Please enter a search term');
            return;
        }
        performSearch(searchTerm);

        console.log('URL being fetched:', 'http://localhost/WEB_Projekat%20sa%20spappom/backend/scripts/search_books.php?search=' + encodeURIComponent(searchTerm));
    });

    function performSearch(searchTerm) {
        fetch('http://localhost/WEB_Projekat%20sa%20spappom/backend/scripts/search_bar.php?search=' + encodeURIComponent(searchTerm))
        .then(response => response.json())
        .then(books => {
            console.log('Search results:', books);
            // You can now display these books on your page
            displayBooks(books);
        })
        .catch(error => {
            console.error('Search failed:', error);
            alert('Search failed, please try again later.');
        });
    }
});

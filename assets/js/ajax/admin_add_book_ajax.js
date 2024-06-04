document.getElementById('addBookForm').addEventListener('submit', function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    
    fetch('/backend/books/add-book', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert('Error adding book: ' + data.error);
        } else {
            alert('Book added successfully!');
            // Optionally, update the UI to show the new book
            const newBookHtml = `
                <div class="book-item">
                    <h4>${data.title} - ${data.price}</h4>
                    <img src="${data.image}" alt="${data.title}">
                </div>`;
            document.querySelector('.box-container').innerHTML += newBookHtml;
        }
    })
    .catch(error => alert('Failed to add book: ' + error.message));
});

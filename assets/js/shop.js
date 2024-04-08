document.addEventListener('DOMContentLoaded', function() {
  const customSelect = document.getElementById('customSelect');
  const customSelectValue = document.getElementById('customSelectValue');
  const customSelectOptions = document.getElementById('customSelectOptions');

  // Toggle the dropdown menu
  customSelect.addEventListener('click', function() {
    customSelectOptions.style.display = customSelectOptions.style.display === 'block' ? 'none' : 'block';
  });

  // Update the selected value and close the dropdown
  customSelectOptions.addEventListener('click', function(event) {
    if (event.target.classList.contains('custom-select-option')) {
      customSelectValue.textContent = event.target.textContent;
      customSelectOptions.style.display = 'none';
      // Call the sorting function with the new value
      sortBooks(event.target.getAttribute('data-value'));
    }
  });

  // Close the dropdown if clicked outside
  document.addEventListener('click', function(event) {
    if (!customSelect.contains(event.target)) {
      customSelectOptions.style.display = 'none';
    }
  });

  // Your existing JavaScript code for sorting and loading books...
});

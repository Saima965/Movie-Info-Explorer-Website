// -------------------- SELECT ELEMENTS --------------------
let searchForm = document.querySelector('.search-form'); // Header search form
let profileForm = document.querySelector('.profile');    // Profile dropdown form
let searchBtn = document.querySelector('#search-btn');   // Search icon button
let loginBtn = document.querySelector('#login-btn');     // Profile image button
let searchBox = document.querySelector('#search-box');   // Search input box

// -------------------- TOGGLE SEARCH AND PROFILE --------------------

// Click search icon to toggle search form
searchBtn.onclick = () => {
    searchForm.classList.toggle('active');      // Show/hide search form
    profileForm.classList.remove('active');     // Hide profile dropdown if open
    searchBox.focus();                           // Focus the input automatically
}

// Click profile image to toggle profile dropdown
loginBtn.onclick = () => {
    profileForm.classList.toggle('active');     // Show/hide profile dropdown
    searchForm.classList.remove('active');      // Hide search form if open
}

// -------------------- CLOSE FORMS WHEN CLICKING OUTSIDE --------------------
document.addEventListener('click', (e) => {
    // Close search form if click outside search form and search button
    if (!searchForm.contains(e.target) && !searchBtn.contains(e.target)) 
        searchForm.classList.remove('active');

    // Close profile form if click outside profile form and profile button
    if (!profileForm.contains(e.target) && !loginBtn.contains(e.target)) 
        profileForm.classList.remove('active');
});

// -------------------- CLEAR SEARCH INPUT AFTER SUBMISSION --------------------
searchForm.addEventListener('submit', function(e){
    // Small delay to allow form submission
    setTimeout(() => {
        searchBox.value = '';  // Clear input after submit
    }, 50);
});

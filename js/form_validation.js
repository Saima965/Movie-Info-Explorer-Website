// Wait until DOM loads
document.addEventListener('DOMContentLoaded', () => {

    // ---------------- Registration Validation ----------------
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.onsubmit = function() {
            const username = document.querySelector('#registerForm input[name="username"]').value.trim();
            const email = document.querySelector('#registerForm input[name="email"]').value.trim();
            const password = document.querySelector('#registerForm input[name="password"]').value.trim();

            // Empty check
            if (!username || !email || !password) { alert("❌ All fields are required!"); return false; }

            // Username
            const usernameRegex = /^[A-Za-z][A-Za-z0-9]{2,}$/;
            if (!usernameRegex.test(username)) { 
                alert("❌ Username must start with a letter and be at least 3 characters (letters and numbers only)."); 
                return false; 
            }

            // Email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) { alert("❌ Please enter a valid email address."); return false; }

            // Password
            if (password.length < 4) { alert("❌ Password must be at least 4 characters."); return false; }
            const specialCharRegex = /[!@#$%^&*(),.?":{}|<>]/;
            if (!specialCharRegex.test(password)) { alert("❌ Password must contain at least one special character."); return false; }

            return true; // allow submit
        };
    }

    // ---------------- Login Validation ----------------
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.onsubmit = function() {
            const usernameOrEmail = document.querySelector('#loginForm input[name="username_or_email"]').value.trim();
            const password = document.querySelector('#loginForm input[name="password"]').value.trim();

            if (!usernameOrEmail || !password) { alert("❌ All fields are required!"); return false; }
            if (password.length < 4) { alert("❌ Password must be at least 4 characters."); return false; }

            return true; // allow submit
        };
    }

});

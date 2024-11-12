// auth.js - JavaScript for login and registration
document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    const logoutButton = document.getElementById('logout-button');

    if (loginForm) {
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const username = document.getElementById('name').value;
            const password = document.getElementById('password').value;

            const response = await fetch('http://localhost:8000/public/login.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ username, password })
            });
            if (response.ok) {
                const data = await response.json();
                sessionStorage.setItem('userId', data.id);
                window.location.href = '../pages/dashboard.html';
            } else {
                alert('Login failed. Please try again.');
            }
        });
    }

    if (registerForm) {
        registerForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const username = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            if(password.length<8){
                alert('Password must be at least 8 characters long.');
                return;
            }
            const alphaNumericRegex = /^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d\W_]+$/;

            if (!alphaNumericRegex.test(password)) {
                alert('Password must be alphanumeric (contain both letters and numbers).');
                return;
            }

            const response = await fetch('http://localhost:8000/public/register.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ username, email, password })
            });
            console.log(response.json())
            if (response.ok) {
                window.location.href = 'login.html';
            } else {
                alert('Registration failed. Please try again.');
            }
        });
    }

    if (logoutButton) {
        logoutButton.addEventListener('click', () => {
            // Remove user data from sessionStorage
            sessionStorage.removeItem('userId');

            // Redirect to login page
            window.location.href = '../index.html';
        });
    }
});

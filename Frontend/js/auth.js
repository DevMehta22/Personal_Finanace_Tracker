// auth.js - JavaScript for login and registration
document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');

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
});

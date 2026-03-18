       function switchTab(type, el) {
            // Update tabs
            document.querySelectorAll('.btn-tab').forEach(btn => btn.classList.remove('active'));
            el.classList.add('active');

            // Switch Forms
            const loginForm = document.getElementById('login-form');
            const signupForm = document.getElementById('signup-form');

            if (type === 'signup') {
                loginForm.style.display = 'none';
                signupForm.style.display = 'block';
            } else {
                loginForm.style.display = 'block';
                signupForm.style.display = 'none';
            }
        }
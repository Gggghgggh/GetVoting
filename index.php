<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GetVoting - Landing Page</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Ensure body takes up full viewport height */
        }

        /* Navbar Styling */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #2c3e50;
            color: #fff;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
        }

        .navbar h1 {
            font-size: 2rem;
        }

        .navbar ul {
            list-style: none;
            display: flex;
        }

        .navbar ul li {
            margin-left: 2rem;
        }

        .navbar ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 1.1rem;
            transition: color 0.3s;
        }

        .navbar ul li a:hover {
            color: #e74c3c;
        }

        /* Hero Section */
        .hero {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex: 1; /* Allow hero section to grow and fill available space */
            background: linear-gradient(to right, #3498db, #8e44ad);
            color: #fff;
            text-align: center;
            padding: 0 2rem;
        }

        .hero h1 {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.5rem;
            margin-bottom: 2rem;
        }

        .hero .cta-btn {
            background: #e74c3c;
            color: #fff;
            padding: 1rem 2rem;
            text-decoration: none;
            font-size: 1.2rem;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .hero .cta-btn:hover {
            background: #c0392b;
        }

        /* Footer */
        footer {
            background: #2c3e50;
            color: #fff;
            text-align: center;
            padding: 1rem;
            margin-top: auto; /* Push footer to the bottom */
        }

        /* Dropdown Cards */
        .dropdown-card {
            display: none;
            position: absolute;
            top: 80px;
            right: 50px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            z-index: 1001;
            width: 200px;
        }

        .dropdown-card h3 {
            margin-bottom: 10px;
        }

        .dropdown-card a {
            display: block;
            padding: 10px;
            background-color: #3498db;
            color: #fff;
            text-align: center;
            border-radius: 4px;
            margin-bottom: 10px;
            text-decoration: none;
        }

        .dropdown-card a:hover {
            background-color: #2980b9;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            background-color: #e74c3c;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            text-align: center;
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <h1>GetVoting</h1>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="#" id="login-btn">Login</a></li>
            <li><a href="#" id="register-btn">Register</a></li>
        </ul>
    </nav>

    <!-- Dropdown Card for Login -->
    <div class="dropdown-card" id="login-card">
        <button class="close-btn" id="close-login-card">x</button>
        <h3>Login as:</h3>
        <a href="adminLogin.php">Admin</a>
        <a href="voterLogin.php">User</a>
    </div>

    <!-- Dropdown Card for Register -->
    <div class="dropdown-card" id="register-card">
        <button class="close-btn" id="close-register-card">x</button>
        <h3>Register as:</h3>
        <a href="registerVoter.php">User</a>
        <a href="registerVoter.php">Candidate</a>
    </div>

    <section class="hero">
        <h1>Welcome to GetVoting</h1>
        <p>Your reliable platform for transparent and secure voting.</p>
        <a href="about.php" class="cta-btn">Learn More</a>
    </section>

    <footer id="contact">
        <p>Powered by GetVoting &copy; 2024</p>
    </footer>

    <script>
        const loginBtn = document.getElementById('login-btn');
        const loginCard = document.getElementById('login-card');
        const closeLoginCard = document.getElementById('close-login-card');

        const registerBtn = document.getElementById('register-btn');
        const registerCard = document.getElementById('register-card');
        const closeRegisterCard = document.getElementById('close-register-card');

        // Toggle the login dropdown card visibility when login button is clicked
        loginBtn.addEventListener('click', function() {
            loginCard.style.display = loginCard.style.display === 'block' ? 'none' : 'block';
            registerCard.style.display = 'none'; // Hide register card if it's open
        });

        // Close the login dropdown card when the 'x' button is clicked
        closeLoginCard.addEventListener('click', function() {
            loginCard.style.display = 'none';
        });

        // Toggle the register dropdown card visibility when register button is clicked
        registerBtn.addEventListener('click', function() {
            registerCard.style.display = registerCard.style.display === 'block' ? 'none' : 'block';
            loginCard.style.display = 'none'; // Hide login card if it's open
        });

        // Close the register dropdown card when the 'x' button is clicked
        closeRegisterCard.addEventListener('click', function() {
            registerCard.style.display = 'none';
        });
    </script>
</body>
</html>

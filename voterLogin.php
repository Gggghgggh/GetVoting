<?php 
if(!isset($_SESSION)) 
{ 
  session_start(); 
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voter Login</title>
    <link href="https://fonts.googleapis.com/css?family=Secular+One" rel="stylesheet"> 
    <style>
        body {
            font-family: 'Secular One', sans-serif;
            background: linear-gradient(to right, #3498db, #8e44ad); /* Gradient background */
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            color: #fff; /* Ensure text color is readable on gradient */
            text-align: center;
        }

        nav {
            width: 100%;
            background-color: #2c3e50;
            color: #fff;
            padding: 1rem;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .logo span {
            color: #e74c3c;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
            color: #333; /* Text color inside the container */
            margin-top: 5rem; /* To ensure it does not overlap with the navbar */
        }

        h2 {
            color: #333;
        }

        input[type=text], input[type=password] {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ddd;
            box-sizing: border-box;
            border-radius: 4px;
            font-size: 14px;
        }

        input[type=text]:focus, input[type=password]:focus {
            outline: none;
            border-color: #3498db;
        }

        button {
            background-color: #3498db;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            cursor: pointer;
            width: 100%;
            border-radius: 4px;
            font-size: 16px;
        }

        button:hover {
            background-color: #2980b9;
        }

        .link {
            color: #3498db;
            text-decoration: none;
        }

        .link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <nav>
        <div class="logo">Get<span>Voting.</span></div>
    </nav>
    <div class="container">
        <h2>Voter Login</h2>
        <form action="loginSubmit.php" method="post">
            <label for="uname"><b>Username</b></label>
            <input type="text" placeholder="Enter StudentID" name="uname" required>
            <label for="psw"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="psw" required>
            <p><?php if(isset($_GET['error'])){ echo $_GET['error']; } ?></p>
            <button type="submit" name="submit">Login</button>
            <p>New user? <a href="index.php" class="link" target="_blank">Register Here</a></p>
        </form>
        <p>Go to <a href="index.php" class="link" target="_blank">Home</a></p>
    </div>
</body>
</html>

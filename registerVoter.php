<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GetVoting</title>
    <link href="https://fonts.googleapis.com/css?family=Secular+One" rel="stylesheet">
    <style>
        body {
            font-family: 'Secular One', sans-serif;
            background: linear-gradient(to right, #3498db, #8e44ad); /* Gradient background */
            margin: 0;
            color: #fff; /* Text color for readability */
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
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

        h3 {
            color: #333; /* Ensure heading is readable */
        }

        input[type=text], input[type=password] {
            width: calc(100% - 24px); /* Adjust for padding */
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

        hr {
            border: none;
            border-top: 1px solid #ddd;
            margin: 20px auto;
            width: 80%;
        }
    </style>
</head>
<body>
    <nav>
        <div class="logo">Get<span>Voting.</span></div>
    </nav>
    <div class="container">
        <h3>New User Registration</h3>
        <p>Go to <a href="index.php" class="link" target="_blank">Home</a></p>
        <hr>
        <h3>New Record Insertion</h3>
        <form action="registerVoterScript.php" method="post">
            <input type="text" placeholder="Name" name="name" required>
            <br>
            <input type="text" placeholder="Student Id" name="sid" required>
            <br>
            <input type="password" placeholder="Password" name="pass" required>
            <br>
            <input type="text" placeholder="Contact" name="contact" required>
            <br>
            <button type="submit" name="submit">Register</button>
        </form>
        <hr>
    </div>
</body>
</html>

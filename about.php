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
            position: relative; /* To position the back button absolutely */
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

        .content {
            max-width: 650px;
            background-color: #fff;
            color: #333; /* Text color inside the content container */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 5rem auto; /* Center content with margin at the top to ensure visibility */
            text-align: left;
            position: relative; /* Ensure proper positioning of the back button */
        }

        a.btn {
            display: inline-block;
            background-color: #3498db;
            color: #fff;
            padding: 14px 20px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        a.btn:hover {
            background-color: #2980b9;
        }

        /* Back button positioning */
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: #3498db;
            color: #fff;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s;
            z-index: 1000; /* Ensure the button stays above other elements */
        }

        .back-button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <nav>
        <div class="logo">Get<span>Voting.</span></div>
    </nav>
    <a href="index.php" class="back-button">Back</a>
    <div class="content">
        <h3>About GetVoting</h3>
        <p>GetVoting is an online election system, which can be used for normal class election processes. For example, election of a class Representative, Student Secretary, and other posts.</p>
        <p>When it comes to Voting by students, this platform will serve the better way. First, you have to have an admin account. There can only be one admin account, and it has superuser access to the database. Next, all the voters have to first register in the forum and then he/she can cast a vote. One can cast only one vote, identified by the user's unique ID/ student ID.</p>
    </div>
</body>
</html>

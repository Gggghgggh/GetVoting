<?php 
if(!isset($_SESSION['adminLoggedin'])) 
{ 
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stylish Login Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #3498db, #8e44ad); /* Gradient background */
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            color: #fff; /* Ensure text color is readable on gradient */
        }

        .login-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
            color: #333; /* Text color inside the container */
        }

        .login-container h2 {
            color: #333;
        }

        .login-form {
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-group input:focus {
            outline: none;
            border-color: #3498db;
        }

        .login-button {
            background-color: #3498db;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .login-button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Admin Login</h2>
        <form target="" method="post" class="login-form">
            <div class="form-group">
                <input type="text" name="username" placeholder="Username" value="">
            </div>
            <div class="form-group">
                <input type="password" name="pass" placeholder="Password" value="">
            </div>
            <input type="submit" name="loginbtn" value="Login" class="login-button">
        </form>
    </div>

</body>
</html>

<?php 
if(isset($_POST['loginbtn'])){
    if($_POST['username']=="admin" && $_POST['pass']=='admin'){
        echo "login Successful";
        echo "<script>alert('login Successful')</script>";
        $_SESSION['adminLoggedin']="ok";
        header("location: admindashboard.php");
    }else{
        echo "<script>alert('Invalid Credentials.')</script>";
    }
}
?>

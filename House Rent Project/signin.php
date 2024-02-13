<?php
include "include/main.php";
noLoginSession();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="styles.css">
    <title>Login Page</title>
    <style>
        /* CSS for the login page */

        body {
            background-color: #f2f2f2;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 400px;
            margin: 5rem auto;
            padding: 40px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            text-align: center;
        }

        h1 {
            margin-bottom: 30px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #1c1d1c;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #2d2e2d;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
    <div>
                <center><?php include './message.php'; ?></center>
            </div>
        <h1>Login</h1>
        <form method="post">
            <input type="text" name="email" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>
        <a href="index.php"><button type="submit" class="form-submit mt-5">back to home page</button></a>
    </div>
</body>

</html>
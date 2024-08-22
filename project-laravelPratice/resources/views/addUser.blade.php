<!-- resources/views/add_user.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
</head>
<body>
    <h1>Add New User</h1>
    <form action="" method="POST">
        @csrf
        <label for="f_name">First Name:</label>
        <input type="text" name="f_name" id="f_name" required><br><br>

        <label for="l_name">Last Name:</label>
        <input type="text" name="l_name" id="l_name" required><br><br>

        <label for="age">Age:</label>
        <input type="number" name="age" id="age" required><br><br>

        <label for="emailId">Email ID:</label>
        <input type="email" name="emailId" id="emailId" required><br><br>

        <label for="phone">Phone:</label>
        <input type="text" name="phone" id="phone" required><br><br>

        <label for="gender">Gender:</label>
        <input type="text" name="gender" id="gender" required><br><br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br><br>

        <label for="token">Token:</label>
        <input type="text" name="token" id="token"><br><br>

        <button type="submit">Add User</button>
    </form>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>Register Test</title>
</head>

<body>

<h2>GhumauneyNepal - Register Test</h2>

<form action="../auth/register.php" method="POST">

    <label>Name:</label><br>
    <input type="text" name="name" required>
    <br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required>
    <br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required>
    <br><br>

    <button type="submit">Register</button>

</form>

</body>
</html>
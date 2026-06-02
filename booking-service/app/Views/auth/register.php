<!DOCTYPE html>
<html>
<head>

    <title>Register</title>

</head>

<body>

    <h2>Register</h2>

    <form action="/register/process" method="post">

        <p>Nama</p>

        <input type="text" name="name" required>

        <p>Email</p>

        <input type="email" name="email" required>

        <p>Phone</p>

        <input type="text" name="phone">

        <p>Password</p>

        <input type="password" name="password" required>

        <br><br>

        <button type="submit">

            Register

        </button>

    </form>

    <br>

    <a href="/login">

        Login

    </a>

</body>
</html>
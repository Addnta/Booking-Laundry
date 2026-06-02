<!DOCTYPE html>
<html>
<head>
    <title>Login Laundry</title>

    <style>
        body {
            font-family: Arial;
            background: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            width: 300px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #4CAF50;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #45a049;
        }
    </style>

</head>
<body>

<div class="card">

    <h2>Login Laundry</h2>

    <form action="/login/process" method="post">

        <input type="text" name="nama" placeholder="Nama">
        <input type="password" name="password" placeholder="Password">

        <button type="submit">Login</button>

    </form>

</div>

</body>
</html>
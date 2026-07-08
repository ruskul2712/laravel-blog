<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<h1>Login</h1>

<form action="{{ route('login.store') }}" method="POST">

    @csrf

    <div>
        <label>Email</label><br>
        <input type="email" name="email">
    </div>

    <br>

    <div>
        <label>Password</label><br>
        <input type="password" name="password">
    </div>

    <br>

    <button type="submit">
        Login
    </button>

</form>
</body>
</html>

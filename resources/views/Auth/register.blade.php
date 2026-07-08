<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>

<h1>Register</h1>

<form action="{{ route('register.store') }}" method="POST">

    @csrf

    <div>
        <label>Name</label><br>
        <input type="text" name="name">
    </div>

    <br>

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
        Register
    </button>

</form>

</body>
</html>

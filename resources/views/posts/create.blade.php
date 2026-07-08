<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create Post</title>
</head>
<body>
<h1>Create Post</h1>

<form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div>
        <label>Title</label><br>
        <input type="text" name="title">
    </div>

    <br>

    <div>
        <label>Description</label><br>
        <textarea name="description"></textarea>
    </div>

    <br>

    <div>
        <label>Photo</label><br>
        <input type="file" name="image" accept="image/*">
    </div>

    <br>

    <button type="submit">Create</button>

</form>
</body>
</html>

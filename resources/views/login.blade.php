<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body class="bg-dark bg-gradient fs-5">
    <div class="container">
        <div class="position-absolute top-50 start-50 translate-middle bg-info bg-gradient rounded">
            <form method="POST" action="/login" class="p-3">
                @csrf
                <div class="text-center">
                    Login
                </div>
                <div class="col-12">
                    <label for="username">Username</label>
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1">@</span>
                        <input type="text" class="form-control" id="username" placeholder="Username" name="username">
                    </div>
                </div>
                <div class="form-group mt-3">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" placeholder="Password" name="password">
                </div>
                <button type="submit" class="btn btn-primary mt-3">Login</button>
            </form>
        </div>
    </div>
</body>

</html>
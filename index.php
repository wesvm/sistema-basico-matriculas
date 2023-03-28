<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="./src/assets/css/style.css">

</head>

<body>

    <div class="container text-center d-flex justify-content-center align-items-center vh-100" style="max-width: 500px;">
        <div class="flex-grow-1 card-login p-4">
            <form action="auth/authlogin.php" method="POST">
                <h1 class="h3 mb-5">Log In</h1>

                <div class="form-floating my-3">
                    <input type="text" id="coduni" name="username" class="form-control" id="floatingInput" required>
                    <label for="floatingInput">User</label>
                </div>
                <div class="form-floating my-3">
                    <input type="password" id="passuni" name="password" class="form-control" id="floatingPassword" required>
                    <label for="floatingPassword">Password</label>
                </div>

                <?php echo isset($_GET['ms']) ? "<p style='color: red;'>Usuario o contraseña incorrectos</p>" : "" ?>

                <button class="w-50 btn btn-lg btn-primary" type="submit">Log in</button>
                <p class="mt-5 mb-3 text-muted">&copy; by blue</p>
            </form>
        </div>
    </div>

</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
</script>

</html>
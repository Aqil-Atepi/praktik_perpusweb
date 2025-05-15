<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div id="head_con">
            <img src="assets/logo_bi.png" style="width: auto; height: 200px;">
        </div>
    </header>

    <nav>
        <div class="center-links">
            <a href="index.php">Home</a>
        </div>
        <div class="right-link">
            <a href="login.php">Login</a>
        </div>
    </nav>

    <section class="con">
        <h1>Login</h1>
        <form method="post" action="login_process.php">
            <label for="username">Username or Email:</label>
            <input type="text" name="useremail" required>
            
            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <input type="submit" name="login_btn" value="Login">
        </form>
    </section>
</body>
</html>
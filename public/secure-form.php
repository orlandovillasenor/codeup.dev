<html>
<head>
    <title>Login Page</title>
</head>
<body>

<?php

session_start();

if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    $_SESSION = array();
    session_destroy();
    header("Location: secure-form.php");
    exit(0);
}

$username = 'codeup';
$password = 'letmein';

if (!empty($_POST)) {
    if ($_POST['username'] == $username && $_POST['password'] == $password) {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $_POST['username'];
    } else {
        echo "<p>Invalid login, please try again.</p>";
    }
}

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    echo "<h1>Welcome!</h1>";
    echo "<p>You are logged in as {$_SESSION['username']}.</p>";
    echo "<p><a href='/secure-form.php?logout=true'>Click here to logout.</a></p>";
} else {

?>

    <h1>Login Form</h1>

    <form method="POST" action="/secure-form.php">
        <p>
            <label for="username">Username</label>
            <input id="username" name="username" type="text">
        </p>
        <p>
            <label for="password">Password</label>
            <input id="password" name="password" type="password">
        </p>
        <p>
            <input type="submit">
        </p>
    </form>

<?php } // close if/else ?>

</body>
</html>
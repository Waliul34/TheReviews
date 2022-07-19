<?php
include 'config.php';

session_start();
error_reporting(0);
if (isset($_SESSION['username']))
    header("Location: index.php");


function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (isset($_POST['submit'])) {
    $email = test_input($_POST['email']);
    $password = md5(test_input($_POST['password']));

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password';";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['username'] = $row['username'];
        $_SESSION['start'] = time();
        $_SESSION['expire'] = $_SESSION['start'] + (86400);    //in seconds, 1 day
        header("Location: index.php");
    } else {
        echo "<script>alert('Oops! Email or password is wrong.');</script>";
    }
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="img/thereviews_logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700&family=Sen:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/sign_in.css">
    <title>Sign In - The Reviews</title>
</head>

<body>
    <div class="container">
        <div class="logo-container">
            <a href="index.php" style="text-decoration: none; color: inherit;">
                <h1 class="logo">TheReviews</h1>
            </a>
        </div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="login-email">
            <p class="login-text">Sign In</p>
            <div class="input-group">
                <input type="email" placeholder="Email" name="email" value="<?php echo $email; ?>" required>
            </div>
            <div class="input-group">
                <input type="password" placeholder="Password" name="password" required>
            </div>
            <div class="input-group">
                <button name="submit" class="btn">Sign In</button>
            </div>
            <p class="login-register-text">Don't have an account? <a href="sign_up.php">Sign Up Here</a></p>
        </form>
    </div>
</body>

</html>
<?php

session_start();
include 'config.php';
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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $username = test_input($_POST['username']);
    $email = test_input($_POST['email']);
    $password = md5(test_input($_POST['password']));
    $cpassword = md5(test_input($_POST['cpassword']));

    $usernameErr = $emailErr = $cpasswordErr = "";



    if ($password == $cpassword) {
        $sql_u = "SELECT * FROM users WHERE username='$username';";
        $result_u = mysqli_query($conn, $sql_u);
        $sql = "SELECT * FROM users WHERE email='$email';";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result_u) > 0)
            $usernameErr = "* Username already exists";
        else if (mysqli_num_rows($result) > 0)
            $emailErr = "* An account exists for this email.";
        else {
            $date = date('Y-m-d');
            $sql = "INSERT INTO users(username, email, password, reg_date) VALUES('$username', '$email', '$password', '$date')";
            $result = mysqli_query($conn, $sql);
            if ($result)
                echo "<script>if(confirm('Account Successfully Created! Now Sign In.')){document.location.href='sign_in.php'};</script>";
            else
                echo "<script>alert('Oops! Something went wrong.');</script>";
        }
    } else
        $cpasswordErr = "* Cofirm Password does not match.";
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
    <title>Sign Up - The Reviews</title>
</head>

<body>
    <div class="container">
        <div class="logo-container">
            <a href="index.php" style="text-decoration: none; color: inherit;">
                <h1 class="logo">TheReviews</h1>
            </a>
        </div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="login-email">
            <p class="login-text">Sign Up</p>
            <div class="input-group">
                <input type="text" pattern="[a-zA-Z0-9'_''.']*" title="Should contain only letters, numbers, full stop and underscore" placeholder="Username" name="username" value="<?php echo $username; ?>" required>
                <span style="color: red;"><?php echo $usernameErr;?></span>
            </div>
            <div class="input-group">
                <input type="email" placeholder="Email" name="email" value="<?php echo $email; ?>" required>
                <span style="color: red;"><?php echo $emailErr;?></span>
            </div>
            <div class="input-group">
                <input type="password" placeholder="Password" name="password" required>
            </div>
            <div class="input-group">
                <input type="password" placeholder="Confirm Password" name="cpassword" required>
                <span style="color: red;"><?php echo $cpasswordErr;?></span>
            </div>
            <div class="input-group">
                <button name="submit" class="btn">Sign Up</button>
            </div>
            <p class="login-register-text">Have an account? <a href="sign_in.php">Sign In Here</a></p>
        </form>
    </div>
</body>

</html>
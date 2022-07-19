<?php

include_once('account_session.php');

if (!isset($_COOKIE["mode"]))
    setcookie("mode", "1", time() + 86400);

$mode = $_COOKIE['mode'];

if(!isset($_SESSION['username']))
    echo "<script>document.location.href='index.php';</script>";

$user = $_SESSION['username'];
$que = "SELECT * FROM admins WHERE username_admin = '$user';";
$res = mysqli_query($conn, $que);
$num = mysqli_num_rows($res);
if(!$num)
    echo "<script>document.location.href='index.php';</script>";

if(isset($_POST['upload']))
{
    $q1 = "SHOW TABLE STATUS WHERE Name = 'films';";
    $r1 = mysqli_query($conn, $q1);
    $d1 = mysqli_fetch_assoc($r1);
    $idx = $d1['Auto_increment'];
    

    $filename = $idx . "_" . $_FILES["uploadFile"]["name"];
    $tempname = $_FILES["uploadFile"]["tmp_name"];
    $folder = "./img/" . $filename;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($folder, PATHINFO_EXTENSION));

    $filmName = $_POST['filmName'];
    $releaseYear = $_POST['releaseYear'];
    $director = $_POST['director'];

    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "webp")
    {
        echo "<script>alert('Sorry, only JPG, JPEG, PNG, WEBP & GIF files are allowed.')</script>";
        $uploadOk = 0;
    }

    $q2 = "SELECT * FROM films WHERE f_name = '$filmName' AND director = '$director';";
    $r2 = mysqli_query($conn, $q2);
    $d2 = mysqli_num_rows($r2);
    if($d2)
    {
        echo "<script>alert('Film already exists in the Films list.');document.location.href='films.php';</script>";
        $uploadOk = 0;
    }

    if($uploadOk)
    {
        $sql = "INSERT INTO films (f_name, release_year, director, poster_name) VALUES ('$filmName', $releaseYear, '$director', '$filename');";
        mysqli_query($conn, $sql);
        move_uploaded_file($tempname, $folder);
        echo "<script>alert('Film Added Successfully.');document.location.href='films.php';</script>";
    }

}

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="img/thereviews_logo.png">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/films.css">
    <link rel="stylesheet" href="css/review.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700&family=Sen:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <title>Add A Film</title>
</head>

<body>
    <?php include_once('includes/navbar.php'); ?>
    <script>
        let page_active = document.querySelectorAll(".menu-list-item");
        page_active[4].classList.add("active");
        let usr = '<?php echo $user; ?>';
        let isAdmin = '<?php echo $isAdmin; ?>';
    </script>
    <script src="js/current_account.js"></script>

    <?php include_once('includes/sidebar.php'); ?>

    <div class="container">

        <div class="add-film-container">
            <div class="add-form">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="" enctype="multipart/form-data">
                    <h3 class="movie-list-title head-add-film">Add a Film in the Films List</h3>
                    <label class="custom-file-upload">
                        <input type="file" placeholder="Film Image" name="uploadFile" value="<?=$_FILES["uploadFile"]["name"]?>" >
                        Select a Poster for this Film
                    </label>
                    <div class="input-group">
                        <input type="text" placeholder="Film Name" name="filmName" value="<?=$_POST['filmName']?>" >
                    </div>
                    <div class="input-group">
                        <input type="number" placeholder="Release Year" name="releaseYear" value="<?=$_POST['releaseYear']?>" >
                    </div>
                    <div class="input-group">
                        <input type="text" placeholder="Director(s)" name="director" value="<?=$_POST['director']?>" >
                    </div>
                    <div class="input-group">
                        <button type="text" name="upload" class="btn">ADD</button>
                    </div>
                </form>
            </div>
        </div>

        <?php include_once('includes/footer.php'); ?>
    </div>
    <script src="js/dark_mode_toggle.js"></script>
    <script>
        const mode = document.cookie.match('(^|;)\\s*' + "mode" + '\\s*=\\s*([^;]+)')?.pop() || '';
        console.log(mode);
        if (mode == "1")
            init();
    </script>
</body>

</html>
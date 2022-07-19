<?php

include_once('account_session.php');

if (!isset($_COOKIE["mode"]))
    setcookie("mode", "1", time() + 86400);

$mode = $_COOKIE['mode'];

if(!isset($_SESSION['username']))
    echo "<script>alert('Please log in to review.');document.location.href='sign_in.php';</script>";

    
if(isset($_POST['review'])) 
{

    $rating = $_POST['rating'];
    $title = $_POST['title'];
    $desc = $_POST['desc'];
    $f_id = $_POST['f_id'];

    $user = $_SESSION['username'];

    $qu = "SELECT * FROM reviews WHERE f_id = '$f_id' AND username = '$user';";
    $re = mysqli_query($conn, $qu);
    $num = mysqli_num_rows($re);

    if($num)
    {
        echo "<script>alert('You already reviewed this film.');document.location.href='my_reviews.php';</script>";
    }
    else
    {
        $query = "INSERT INTO reviews(f_id, username, title, rating, review, published_at) VALUES($f_id, '$user', '$title', $rating, '$desc', now());";
        mysqli_query($conn, $query);
        echo "<script>alert('Review added successfully!');document.location.href='my_reviews.php';</script>";
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
    <link rel="stylesheet" href="css/make_review.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700&family=Sen:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <title>Make A Review</title>
</head>

<body>
    <?php include_once('includes/navbar.php'); ?>
    <script>
        let usr = '<?php echo $user; ?>';
        let isAdmin = '<?php echo $isAdmin; ?>';
    </script>
    <script src="js/current_account.js"></script>

    <?php include_once('includes/sidebar.php'); ?>

    <div class="container">
        <?php

        $f_id = $_GET['f_id'];
        $qu = "SELECT * FROM films WHERE f_id = $f_id;";
        $re = mysqli_query($conn, $qu);
        $film = mysqli_fetch_assoc($re);

        $q = "SELECT ROUND(AVG(rating), 1) avg_r, COUNT(rating) cnt_r FROM reviews WHERE f_id = $f_id GROUP BY f_id;";
        $r_q = mysqli_query($conn, $q);
        $d = mysqli_fetch_assoc($r_q);

        ?>
        <div class="add-film-container">
            <div class="review-film-list-item">
                <img src="img/<?= $film['poster_name'] ?>" alt="" class="review-film-list-item-img">
                <span class="review-film-list-item-title"><?= $film['f_name'] ?></span>
                <p class="review-film-list-item-year">Release Year: <?= $film['release_year'] ?></p>
                <p class="review-film-list-item-director">Directed By: <?= $film['director'] ?></p>
                <p class="review-film-list-item-average-rating">Average Rating: <?php if($d) echo $d['avg_r']; else echo 0;?></p>
                <p class="review-film-list-item-total-reviews">Total Reviews: <?php if($d) echo $d['cnt_r']; else echo 0;?></p>
            </div>
            <div class="add-form">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="">
                    <h2 class="movie-list-title head-add-film">Add a Review</h2>
                    <div class="rating-group">
                        <h3 class="movie-list-title">Your Rating: </h3>
                        <input type="range" name="rating" value="7" min="1" max="10" oninput="this.nextElementSibling.value = this.value" required>
                        <output class="movie-list-title">7</output>
                    </div>
                    <h3 class="movie-list-title">Your Review: </h3>
                    <div>
                        <input type="hidden" placeholder="" name="f_id" value="<?=$_GET['f_id']?>">
                    </div>
                    <div class="input-group">
                        <input type="text" placeholder="Write a title for your review here" name="title" value="" required>
                    </div>
                    <div class="textarea-group">
                        <textarea placeholder="Write your review here" name="desc" required></textarea>
                    </div>
                    <div class="input-group">
                        <button type="text" name="review" class="btn">Review</button>
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
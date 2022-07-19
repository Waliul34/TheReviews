<?php 

include_once('account_session.php');
if (!isset($_COOKIE["mode"]))
    setcookie("mode", "1", time() + 86400);

$mode = $_COOKIE['mode'];

if($_GET['p_id'] == "")
    echo "<script>document.location.href='all_reviews.php'</script>";

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="img/thereviews_logo.png">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/review.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700&family=Sen:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <title>"Review Details"</title>
</head>

<body>
    <?php include_once('includes/navbar.php'); ?>
    <script>
        let usr = '<?php echo $user; ?>';
        let isAdmin = '<?php echo $isAdmin; ?>';
    </script>
    <script src="js/current_account.js"></script>

    <?php include_once('includes/sidebar.php'); ?>

    <?php 
        $p_id = $_GET['p_id'];
        $postQuery = "SELECT * FROM reviews NATURAL JOIN films WHERE p_id = $p_id;";
        $runPQ = mysqli_query($conn, $postQuery);
        $post = mysqli_fetch_assoc($runPQ);
    ?>

    <div class="container">
        <div class="min-height">
            <div class="head-section">
                <h2 class="review-title movie-list-title"><?php echo $post['title'];?></h2>
                <img src="img/<?= $post['poster_name'] ?>" alt="" class="review-img">
                <p class="film-title movie-list-title">Film: <?= $post['f_name'] ?></p>
                <p class="director-name movie-list-title">Directed by: <?= $post['director'] ?></p>
                <p class="user-rating movie-list-title">User Rating: <?=$post['rating']?></p>
                <p class="publishing-date movie-list-title">Published at: <?=date('F jS, Y', strtotime($post['published_at']))?></p>
                <p class="user-name movie-list-title">Reviewed By: <?=$post['username']?></p>
            </div>
            <div class="body-section">
                <p class="review-desc movie-list-title"><?=$post['review']?></p>
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

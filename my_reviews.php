<?php

include_once('account_session.php');

if (!isset($_COOKIE["mode"]))
    setcookie("mode", "1", time() + 86400);

$mode = $_COOKIE['mode'];

if (isset($_GET['page']))
    $page = $_GET['page'];
else {
    $page = 1;
    $_GET['page'] = $page;
}
$post_per_page = 5;
$result = ($page - 1) * $post_per_page;

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="img/thereviews_logo.png">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/films.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700&family=Sen:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <title>My Reviews</title>
</head>

<body>
    <?php include_once('includes/navbar.php'); ?>
    <script>
        let page_active = document.querySelectorAll(".menu-list-item");
        page_active[3].classList.add("active");
        let usr = '<?php echo $user; ?>';
        let isAdmin = '<?php echo $isAdmin; ?>';
    </script>
    <script src="js/current_account.js"></script>

    <?php include_once('includes/sidebar.php'); ?>

    <div class="container">

        <div class="film-list">

            <?php
            $user = $_SESSION['username'];
            $postQuery = "SELECT * FROM reviews NATURAL JOIN films WHERE username='$user' ORDER BY p_id DESC;";
            $runPQ = mysqli_query($conn, $postQuery);

            $no_post = mysqli_num_rows($runPQ);
            if($no_post == 0)
                echo '<h2 class="movie-list-title">You never reviewed any film. Go to Films section and tap on a film to review it.</h2>';

            while ($post = mysqli_fetch_assoc($runPQ)) {
            ?>
                <a href="review.php?p_id=<?= $post['p_id'] ?>" class="no-decoration">

                <div class="review-list-item">
                        <img src="img/<?= $post['poster_name'] ?>" alt="" class="film-list-item-img">
                        <span class="film-list-item-title ">"<?php echo $post['title']; ?>"</span>
                        <h3 class="film-list-item-year" style="font-size: 18px;">Film: <?= $post['f_name'] ?> (<?= $post['release_year'] ?>)</h3>
                        <p class="film-list-item-director">Reviewed By: <?= $post['username'] ?></p>
                        <p class="film-list-item-average-rating">Rating: <?= $post['rating'] ?></p>
                        <p class="film-list-item-published-at">Published at: <?= date('F jS, Y, g:i a', strtotime($post['published_at'])) ?></p>
                        <?php
                        if($isAdmin || $user == $post['username']){ ?>
                            <a href="delete_review.php?p_id=<?=$post['p_id']?>" class="no-decoration" onclick="return confirm('Do you really want to delete this review? If yes, press OK.')"><i class="fa-solid fa-trash delete-btn"></i></a>
                        <?php }
                        ?>
                    </div>
                </a>
            <?php
            }
            ?>

            <?php

            $q = "SELECT * FROM reviews WHERE username='$user';";
            $r = mysqli_query($conn, $q);
            $total_posts = mysqli_num_rows($r);
            $total_pages = ceil($total_posts / $post_per_page);

            ?>

            <?php include_once('includes/pagination.php'); ?>

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
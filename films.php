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
    <title>Films</title>
</head>

<body>
    <?php include_once('includes/navbar.php'); ?>
    <script>
        let page_active = document.querySelectorAll(".menu-list-item");
        page_active[1].classList.add("active");
        let usr = '<?php echo $user; ?>';
        let isAdmin = '<?php echo $isAdmin; ?>';
    </script>
    <script src="js/current_account.js"></script>

    <?php include_once('includes/sidebar.php'); ?>

    <div class="container">

        <h3 class="head">Click a Film to Review It</h3>

        <div class="film-list">

            <?php
            $filmQuery = "SELECT *, ROUND(NVL(AVG(rating), 0), 1) avg_r, NVL(COUNT(rating), 0) cnt_r FROM films LEFT OUTER JOIN reviews USING(f_id) GROUP BY f_id ORDER BY NVL(AVG(rating), 0) DESC LIMIT $result, $post_per_page;";
            $runQ = mysqli_query($conn, $filmQuery);

            $no_post = mysqli_num_rows($runQ);
            if ($no_post == 0)
                echo '<h2 class="movie-list-title">Found 0 movies in the database.</h2>';

            while ($film = mysqli_fetch_assoc($runQ)) {
            ?>
                <a href="make_review.php?f_id=<?= $film['f_id'] ?>" class="no-decoration">
                    <div class="film-list-item">
                        <img src="img/<?= $film['poster_name'] ?>" alt="" class="film-list-item-img">
                        <span class="film-list-item-title movie-list-title"><?= $film['f_name'] ?></span>
                        <p class="film-list-item-year movie-list-title">Release Year: <?= $film['release_year'] ?></p>
                        <p class="film-list-item-director movie-list-title">Directed By: <?= $film['director'] ?></p>
                        <p class="film-list-item-average-rating movie-list-title">Average Rating: <?=$film['avg_r']?></p>
                        <p class="film-list-item-total-reviews movie-list-title">Total Reviews: <?=$film['cnt_r']?></p>
                        <?php
                        if($isAdmin){ ?>
                            <a href="delete_film.php?f_id=<?=$film['f_id']?>" class="no-decoration" onclick="return confirm('Do you really want to delete this film? If yes, press OK.')"><i class="fa-solid fa-trash delete-btn"></i></a>
                        <?php }
                        ?>
                    </div>
                </a>

            <?php
            }
            ?>

            <?php

            $q = "SELECT * FROM films;";
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
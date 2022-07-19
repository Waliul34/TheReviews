<?php

include_once('account_session.php');
$result = 0;
$post_per_page = 8;

if (!isset($_COOKIE["mode"]))
    setcookie("mode", "1", time() + 86400);

$mode = $_COOKIE['mode'];
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="img/thereviews_logo.png">
    <link rel="stylesheet" href="css/index.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700&family=Sen:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <title>The Reviews</title>
</head>

<body>
    <?php include_once('includes/navbar.php'); ?>
    <script>
        let page_active = document.querySelectorAll(".menu-list-item");
        page_active[0].classList.add("active");
        let usr = '<?php echo $user; ?>';
        let isAdmin = '<?php echo $isAdmin; ?>';
    </script>
    <script src="js/current_account.js"></script>

    <?php include_once('includes/sidebar.php'); ?>

    <div class="container">
        <div class="content-container">
            <div class="featured-content" style="background: linear-gradient(to bottom, rgba(0, 0, 0, 0), #151515), url('img/about-hero.gif');">
                <img class="featured-title" src="img/thereviews_logo.png" alt="">
                <p class="featured-desc">There are thousands and thousands of films in this world. One cannot watch a fraction of total films in his lifetime. So, which films should you watch? Here comes TheReviews to the rescue with its users.</p>
            </div>
        </div>
        <div class="movie-list-container">

            <?php

            $rQ = "SELECT * FROM films NATURAL JOIN reviews ORDER BY rating DESC LIMIT $result, $post_per_page;";
            $res_rQ = mysqli_query($conn, $rQ);

            $no_post = mysqli_num_rows($res_rQ);

            ?>

            <h1 class="movie-list-title">Top Rating Reviews</h1>
            <div class="movie-list-wrapper">
                <div class="movie-list">
                    <?php
                    if ($no_post == 0)
                        echo '<h2 class="movie-list-title">Found 0 reviews in the database.</h2>';
                    ?>

                    <?php

                    while ($reviews = mysqli_fetch_assoc($res_rQ)) { ?>

                        <div class="movie-list-item">
                            <img class="movie-list-item-img" src="img/<?= $reviews['poster_name'] ?>" alt="">
                            <span class="movie-list-item-title"><?= $reviews['f_name'] ?> (<?= $reviews['release_year'] ?>)</span>
                            <p class="movie-list-item-desc">"<?= $reviews['title'] ?>"</p>
                            <p class="movie-list-item-user">By : <?= $reviews['username'] ?></p>
                            <p class="movie-list-item-rating">Rating : <?= $reviews['rating'] ?></p>
                            <a href="review.php?p_id=<?= $reviews['p_id'] ?>"><button class="movie-list-item-button">View</button></a>

                        </div>

                    <?php }

                    ?>


                </div>
                <i class="fas fa-chevron-right arrow"></i>
            </div>
            <button class="see-all" onclick="location.href='all_reviews.php'">See All</button>
        </div>

        <div class="movie-list-container">

            <?php

            $rQ = "SELECT f_id, ROUND(AVG(rating), 1) avg_r, COUNT(rating) cnt_r FROM reviews GROUP BY f_id ORDER BY avg_r DESC LIMIT $result, $post_per_page;";
            $res_rQ = mysqli_query($conn, $rQ);

            $no_post = mysqli_num_rows($res_rQ);

            ?>

            <h1 class="movie-list-title">Top Rated Films</h1>
            <div class="movie-list-wrapper">
                <div class="movie-list">

                    <?php
                    if ($no_post == 0)
                        echo '<h2 class="movie-list-title">Found 0 reviews in the database.</h2>';
                    ?>

                    <?php

                    while ($film = mysqli_fetch_assoc($res_rQ)) {
                        $f_id = $film['f_id'];
                        $avg_r = $film['avg_r'];
                        $cnt_r = $film['cnt_r'];

                        $q = "SELECT * FROM films WHERE f_id = $f_id;";
                        $r_q = mysqli_query($conn, $q);
                        $d = mysqli_fetch_assoc($r_q);
                    ?>
                        <div class="movie-list-item">
                            <img class="movie-list-item-img" src="img/<?= $d['poster_name'] ?>" alt="">
                            <span class="movie-list-item-title"><?= $d['f_name'] ?></span>
                            <p class="movie-list-item-year">Release Year: <?= $d['release_year'] ?></p>
                            <p class="movie-list-item-director">Directed By: <?= $d['director'] ?></p>
                            <p class="movie-list-item-average-rating">Average Rating: <?= $avg_r ?></p>
                            <p class="movie-list-item-total-reviews">Total Reviews: <?= $cnt_r ?></p>
                        </div>

                    <?php }

                    ?>


                </div>
                <i class="fas fa-chevron-right arrow"></i>
            </div>
            <button class="see-all" onclick="location.href='films.php'">See All</button>
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
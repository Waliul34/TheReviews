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
$post_per_page = 3;
$result = ($page - 1) * $post_per_page;

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="img/thereviews_logo.png">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/search.css">
    <link rel="stylesheet" href="css/films.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700&family=Sen:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <title>Search</title>
</head>

<body>
    <?php include_once('includes/navbar.php'); ?>
    <script>
        let page_active = document.querySelectorAll(".menu-list-item");
        page_active[5].classList.add("active");
        let usr = '<?php echo $user; ?>';
        let isAdmin = '<?php echo $isAdmin; ?>';
    </script>
    <script src="js/current_account.js"></script>

    <?php include_once('includes/sidebar.php'); ?>

    <div class="container">

        <div class="search">
            <form class="searchBox">
                <input class="searchInput" type="text" name="search" placeholder="Search">
                <button class="searchButton" type="submit" href="">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
        </div>

        <div class="film-list">

            <?php
            if (isset($_GET['search']) && $_GET['search'] != "") {
            ?>
                <h2 class="movie-list-title">You searched for: <?= $_GET['search'] ?></h2>

                <?php

                $keyword = $_GET['search'];
                $filmQuery = "SELECT * FROM films WHERE concat(f_name, director) LIKE '%$keyword%' ORDER BY f_id DESC LIMIT $result, $post_per_page;";
                $reviewQuery = "SELECT * FROM reviews NATURAL JOIN films WHERE concat(title, f_name) LIKE '%$keyword%' ORDER BY p_id DESC LIMIT $result, $post_per_page;";

                $rQ_film = mysqli_query($conn, $filmQuery);
                $rQ_review = mysqli_query($conn, $reviewQuery);

                $found = mysqli_num_rows($rQ_film) + mysqli_num_rows($rQ_review);
                if ($found == 0) { ?>
                    <h2 class="movie-list-title">0 Results Found.</h2>
                    <?php }

                while (($film = mysqli_fetch_assoc($rQ_film)) || ($post = mysqli_fetch_assoc($rQ_review))) {

                    if (isset($film)) {
                        $f_id = $film['f_id'];
                        $q = "SELECT ROUND(AVG(rating), 1) avg_r, COUNT(rating) cnt_r FROM reviews WHERE f_id = $f_id GROUP BY f_id;";
                        $r_q = mysqli_query($conn, $q);
                        $d = mysqli_fetch_assoc($r_q);
                    ?>
                        <a href="make_review.php?f_id=<?= $film['f_id'] ?>" class="no-decoration">
                            <div class="film-list-item">
                                <img src="img/<?= $film['poster_name'] ?>" alt="" class="film-list-item-img">
                                <span class="film-list-item-title movie-list-title"><?= $film['f_name'] ?></span>
                                <p class="film-list-item-year movie-list-title">Release Year: <?= $film['release_year'] ?></p>
                                <p class="film-list-item-director movie-list-title">Directed By: <?= $film['director'] ?></p>
                                <p class="film-list-item-average-rating movie-list-title">Average Rating: <?php if ($d) echo $d['avg_r'];
                                                                                            else echo 0; ?></p>
                                <p class="film-list-item-total-reviews movie-list-title">Total Reviews: <?php if ($d) echo $d['cnt_r'];
                                                                                        else echo 0; ?></p>
                                <?php
                                if ($isAdmin) { ?>
                                    <a href="delete_film.php?f_id=<?= $f_id ?>" class="no-decoration" onclick="return confirm('Do you really want to delete this film? If yes, press OK.')"><i class="fa-solid fa-trash delete-btn"></i></a>
                                <?php }
                                ?>
                            </div>
                        </a>

                    <?php
                    }

                    if (isset($post)) {

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
                                if ($isAdmin || $user == $post['username']) { ?>
                                    <a href="delete_review.php?p_id=<?= $post['p_id'] ?>" class="no-decoration" onclick="return confirm('Do you really want to delete this review? If yes, press OK.')"><i class="fa-solid fa-trash delete-btn"></i></a>
                                <?php }
                                ?>
                            </div>
                        </a>
            <?php
                    }
                }
            }
            ?>

            <?php

            $total_pages = 0;

            if (isset($_GET['search']) && $_GET['search'] != "") {
                $keyword = $_GET['search'];
                $mQuery = "SELECT * FROM films WHERE concat(f_name, director) LIKE '%$keyword%' ORDER BY f_id;";
                $wQuery = "SELECT * FROM reviews NATURAL JOIN films WHERE concat(title, f_name) LIKE '%$keyword%' ORDER BY p_id;";
                $Q_film = mysqli_query($conn, $mQuery);
                $Q_review = mysqli_query($conn, $wQuery);
                $total_posts = mysqli_num_rows($Q_film) + mysqli_num_rows($Q_review);
                $total_pages = ceil($total_posts / ($post_per_page));
            }

            ?>

            <div class="center">
                <div class="pagination">
                    <?php

                    if ($page > 1)
                        $switch = "";
                    else
                        $switch = "disabled";

                    if ($page < $total_pages - 1)
                        $nswitch = "";
                    else
                        $nswitch = "disabled";

                    $curPage = $_GET['page'];

                    ?>

                    <a href="?<?php if (isset($_GET['search'])) {
                                    echo "search=$keyword&";
                                } ?>page=<?= $curPage - 1 ?>" class="<?= $switch ?>">&laquo;</a>
                    <!-- <a href="#" class="active">1</a> -->
                    <?php

                    for ($ipage = 1; $ipage <= $total_pages; $ipage++) { ?>
                        <a href="?<?php if (isset($_GET['search'])) {
                                        echo "search=$keyword&";
                                    } ?>page=<?= $ipage ?>" class="<?php if ($ipage == $curPage) echo "active"; ?>"><?= $ipage ?></a>
                    <?php
                    }

                    ?>
                    <a href="?<?php if (isset($_GET['search'])) {
                                    echo "search=$keyword&";
                                } ?>page=<?= $curPage + 1 ?>" class="<?= $nswitch ?>">&raquo;</a>
                </div>
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
<div class="center">
    <div class="pagination">
        <?php

        if ($page > 1)
            $switch = "";
        else
            $switch = "disabled";

        if ($page < $total_pages)
            $nswitch = "";
        else
            $nswitch = "disabled";

        $curPage = $_GET['page'];

        ?>
        <a href="?page=<?= $curPage - 1 ?>" class="<?= $switch ?>">&laquo;</a>
        <!-- <a href="#" class="active">1</a> -->
        <?php

        for ($ipage = 1; $ipage <= $total_pages; $ipage++) { ?>
            <a href="?page=<?= $ipage ?>" class="<?php if ($ipage == $curPage) echo "active"; ?>"><?= $ipage ?></a>
        <?php
        }

        ?>
        <a href="?page=<?= $curPage + 1 ?>" class="<?= $nswitch ?>">&raquo;</a>
    </div>
</div>
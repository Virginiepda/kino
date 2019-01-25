
<?php
include("start.php");

showTop("Kino | Detail");

showHeader();

//je vais chercher un film en fonction de l'id qui est dans l'url
//on met donc l'id dans une variable

$movieId = $_GET['id'];

$sql="SELECT *
          FROM movie
          WHERE id= :movieId";

$stmt = $dbh->prepare($sql);

$stmt->execute([
    ':movieId' => $movieId
]);

$item = $stmt->fetch();



$sqlReview = "SELECT *
                FROM review
                WHERE movie_id= :movieId";

$stmtReview = $dbh->prepare($sqlReview);

$stmtReview->execute([
    ':movieId' => $movieId
]);

$details = $stmtReview->fetchAll();





    ?>


<main class="detail">

    <div class="aside">

        <div class="poster"><img class="detail" src="img/<?= $item['image'] ?>"/></div>

        <?php if(isset($_SESSION['user'])){ ?>

        <div class="buttons">
            <div class="critic-button">
                <button><a href="add-review.php?id=<?= $item['id']?>">Add a review</a></button>
            </div>

            <div class="watch-list-button">
                <button><a href="add-to-watchlist.php?id=<?= $movieId ?>">Add to my watch list</a></button>
            </div>
        </div>

        <?php } ?>

    </div>
    <div class="main">

        <div class="infos">
            <h1><?= $item['title'] ?></h1>
            <h3>Note</h3>
            <p><?= $item['rating'] ?></p>
            <h3>Acteurs</h3>
            <p><?= $item['actors'] ?></p>
            <h3>Resume</h3>
            <p><?= $item['plot'] ?></p>



        <div class="reviews">

            <h3>Reviews already posted</h3>

            <?php

            foreach($details as $detail){

                $unix = strtotime($detail['date_created']);

            $dateFr = date('d-m-Y', $unix );

                ?>
                <div class="detail-review">
                    <p class="title"><?= $detail['title'] ?></p>
                    <p><?= $detail['content'] ?></p>
                    <p><?= $dateFr  ?></p>
                </div>


        </div>


            <?php }     ?>


        </div>

    </div>




</main>

<?php

showFooter();

?>


</body>
</html>
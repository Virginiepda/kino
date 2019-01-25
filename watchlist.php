<?php
include("start.php");

$user_id = $_SESSION['user']['id'];



$sql = "SELECT * FROM movie AS m
        INNER JOIN watchlist AS w ON m.id = w.movie_id
        WHERE w.user_id = :user_id
        ";




$stmt = $dbh->prepare($sql);

$stmt->execute([
        ':user_id'=> $user_id
]);

$movies = $stmt->fetchAll();



showTop("Kino | Watchlist");

showHeader();

?>



<main class="index watchlist">

<?php foreach($movies as $movie){

    //var_dump($image);die;

    ?>

    <div class="poster">
        <a href="detail.php?id=<?= $movie['id']?>">
            <img alt="<?= $movie['title'] ?>" src="img/<?= $movie['image'] ?>">
        </a></div>


<?php }  ?>



</main>

<?php  showFooter(); ?>

</body>
</html>



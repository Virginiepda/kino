<?php
include("start.php");




$sql = "SELECT id, title, image
        FROM movie
        ORDER BY RAND()   
        LIMIT 40";

//pour faire un random dans la table - ORDER BY RAND()

$stmt = $dbh->prepare($sql);

$stmt->execute();

$movies = $stmt->fetchAll();

//var_dump($images);die;


//url    http://www.domain.com/index.php?CLE=VAL&CLE2=VAL2

showTop("Kino | Home");

showHeader();

?>






<main class="index">

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

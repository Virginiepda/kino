<?php
include("start.php");

showTop("Kino | Detail");

showHeader();

//je récupère l'id du film que j'ai indiqué dans l'url

$movieId = $_GET['id'];

//je fais une requête sql pour récupérer le nom du film

$sql="SELECT title
          FROM movie
          WHERE id= :movieId";
$stmt = $dbh->prepare($sql);

$stmt->execute([
    ':movieId' => $movieId
]);
$result = $stmt->fetchColumn();



$title="";
$content="";
$errorTitle="";
$errorReview="";


if(!empty($_POST)){

    $title = strip_tags($_POST['review-title']);
    $content = strip_tags($_POST['review']);


    if(empty($content)){
        $errorReview="Please write a review";
    }

    if(empty($title)){
        $errorTitle="Please provide a title to your review";
    }


    if(empty($errorReview) && empty($errorTitle)){

       $sql = "INSERT INTO review (title, content, date_created, movie_id) VALUES (:title, :content, now(), :movieId) ";

        $stmt = $dbh->prepare($sql);

        //on lui demande ensuite de l'executer
        //on execute donc un tableau associatif dans lequel on fixe les paramètres donnés
        $stmt->execute([
            ":title"=> $title,
            ":content"=> $content,
            ":movieId" =>$movieId
        ]);

        header("Location: detail.php?id=".$movieId);
        die();

    }



}



?>

<div class="title"><h1><?= $result ?></h1></div>

<main class="add-review">


    <div class="formulaire">
        <form action="" method="post" novalidate>

           <div class="review-title">
               <label for="review-title">Title</label>
               <input type="text" id="review-title" name="review-title" value="<?= $title ?>">

               <div class="error">

                   <?php

                   if(!empty($errorTitle)){
                         echo "<p class='error'>$errorTitle</p>";
                                          }
                   ?>
               </div>

           </div>

            <div class="review">

            <label for="review">Your review</label>
                <input type="text" id="review" name="review" placeholder="djijdoidj hduhdiuhd hduhd" value="<?= $content ?>">

                <div class="error">

                    <?php

                    if(!empty($errorReview)){
                        echo "<p class='error'>$errorReview</p>";
                    }
                    ?>
                </div>
            </div>

            <div class="review-button">

            <button>Send</button>
            </div>

        </form>

    </div>


</main>

<?php  showFooter(); ?>

</body>
</html>


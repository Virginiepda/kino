<?php

include("start.php");

$movie_id = $_GET['id'];

$user_id = $_SESSION['user']['id'];

if(empty($_SESSION['user'])){
    header("Location: login.php");

} else {


    $sql = "INSERT INTO watchlist (user_id, movie_id, date_added) VALUES (:user_id,:movie_id, now() )";

    $stmt = $dbh->prepare($sql);


    $stmt->execute([
        ":user_id" => $user_id,
        ":movie_id" => $movie_id,
    ]);

   header("Location: watchlist.php");
    die();

}
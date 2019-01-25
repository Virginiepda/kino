<?php

//se connecte à la base de données en incluant ici le fichier .php dont on a besoin

include("start.php");


//variables contenant les données du form
$username = "";
$email = "";
$password = "";

//contient les éventuelles erreurs
$errors = [];

//nous pouvons vériofier si le format a été soumis en vérifiant si il y a des données dnas la variable globale qui
//est un tableau  $_POST



if (!empty($_POST)) {

    //on récupère les données dans nos variables/////////////////////////
    //pour éviter les hacks ssr (quelqu'un mettant des balises, des scripts), on va ajouter deux couches de protection
    //couche 1 - ajouter strip_tags , ça enlève automatiquement toutes les balises
    $username = strip_tags($_POST['username']);
    $email = strip_tags($_POST['email']);
    $password = $_POST['password'];


    //on valide les données//////////////////////////////////////////////////
    //pour trier les erros et savoir des quelles je parle, je vais modifier le tableau errors[] en tableau associatif

    //vérification du pseudo

    if (empty($username)) {
        $errors['username'][] = "Please provide a Username";
    } elseif (strlen($username) < 2) {
        $errors['username'][] = "Your username is too short, 2 chars minimum";
    } elseif (strlen($username) > 30) {
        $errors['username'][] = "Your username is too long, 30 chars maximum";
    }

    //est-ce que le username existe déjà?
    //on utilise select count plutôt que select car si il y en a 1, alors le username existe déjà. Si c'est 0, alors il n'existe
    //pas encore en base
    $sql = "SELECT COUNT(*) 
            FROM users
            WHERE username = :username";

    $stmt = $dbh->prepare($sql);

    $stmt->execute([
            ':username' => $username
    ]);
    //on doit ensuite récupérer le résultat pour faire des traitements dessus
    //pour être sûr que le résultat soit un entier, on peut le caster en ajoutant un (int)

    $result = (int)$stmt->fetchColumn();

    if($result >0){
        $errors['username'][]= 'Username is already taken!';
    }


    //vérification de l'email
    if (empty($email)) {
        $errors['email'][] = "Please provide an Email";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        //fonction native en PHP qui vérifie automatiquement si l'email est valide
        $errors['email'][] = "Your email is not valid !";
    }

    $sql = "SELECT COUNT(*) 
            FROM users
            WHERE email = :email";

    $stmt = $dbh->prepare($sql);

    $stmt->execute([
        ':email' => $email
    ]);

    $result = (int)$stmt->fetchColumn();

    if($result >0){
        $errors['email'][]= 'This email is already taken!';
    }


    //vérification du password
    if (empty($password)) {
        $errors['password'][] = "Please choose a password";
    } elseif (strlen($password) < 8) {
        //fonction native en PHP qui vérifie automatiquement si l'email est valide
        $errors['email'][] = "The password is too short !";
    }

//    avant de mettre le password en bdd, il faut le hacher (ne pas utiliser MD5 qui est peu fiable et permet à l'aide
//    d'une rainbow table de retoruver le password correspondant).
//    une bonne pratique est ajouter un autre code haché random (le salt) que nous concatenons avec le password.
//    on peut utiliser random_bytes
    //php a une fonction pour hacher le passowrd et générer le salt en même temps
    // exp:
    //$hash = password_hash("password", PASSWORD_ARGON2I);

    //on peut ensuite vérifier
    //password_verify

    //@todo: est-ce que l'email existe déjà



    //si tout est bon, on les insère dans la base de données
    //on va alors vérifier si tout est valide, donc si le tableau errors est vide

    if(empty($errors)){
        //on hashe notre password avant  // taille max du password haché est de 255 par php
        $hash = password_hash($password, PASSWORD_ARGON2I);

        //on insère en bdd

        //cette requete n'est pas bonne, car une personne malveillante pourrait mettre une requete sql dans un input et cette requête
        //pourrait ensuite être executée au moment de l'envoi du formulaire.
        //PAS DE VARIABLES dans une REQUETE SQL
        //$sql = "INSERT INTO users (username, email, password, creation_date) VALUES ('$username', '$email', '$hash', now())";

        //on va donc plutôt préférer cette méthode là (on va passer des paramats (paramètres nommés)

        $sql = "INSERT INTO users (username, email, password, creation_date) VALUES (:username, :email, :password, now())";

        //envoie la requête à Mysql mais sans l'executer
        $stmt = $dbh->prepare($sql);

        //on lui demande ensuite de l'executer
        //on execute donc un tableau associatif dans lequel on fixe les paramètres donnés
        $stmt->execute([
                ":username"=> $username,
                ":email"=> $email,
                ":password"=>$hash
        ]);


        //avant de rediriger, on va stocker un message flash en session pour que je puisse afficher le
        //message dans l'index, après redirection.

        //plutôt que de le faire sur la page de l'index, je vais aller le faire dans la fonction qui affiche le head

        $_SESSION['message-flash']= "Welcome at Kino, $username, you can now log in !!!!";

        //redirige vers la liste des utilisateurs
        //header ici veut dire entête http, et non pas header d'une page html
        header("Location: index.php");

        //pour éviter le bug, et éviter que une fois la redirection demandée, le code s'execute quand même,
        //on met un die() après.

        die();

    }


}

showTop("Kino | Register");
showHeader();


?>


<main class="register">

    <h1>Formulaire d'inscription</h1>

    <form method="post" novalidate>
        <!--    action si on veut que le formulaire nous amène à une autre page, si on en a pas besoin, il vaut mieux
        ne pas l'initialiser-->
<!--        côté serveur, la validation est aussi testée et si erreur, apparait un message que nous ne pouvons pas modifier-->
<!--        nous allons donc le désactiver en ajoutant novalidate dans la balise form-->

        <div class="input">
            <div class="item">
                <label for="username">Username</label>
                <input type="text" id="username" maxlength="30" name="username" placeholder="Johnny22" value="<?= $username; ?>"/>
            <!--               le name permet de récupérer les données de l'input, c'est essentiel
                                value sert à vraiment écrire des données, par exemple réécrire ce qui a déjà été tapé,
                                on fait donc un echo de la variable, qui soit est vide, soit contient la data qui a été rentrée
                                si on veut juste faire un echo, on peut supprimer le "php echo" et le remplacer par "="-->
            </div>
            <div class="error">

                <?php

                if(!empty($errors['username'])) {
                    foreach ($errors['username'] as $message) {
                        echo "<p>$message</p>";
                    }
                }
                ?>
            </div>

        </div>

        <div class="input">

            <div class="item">
                <label for="email">Email</label>
                <input type="email" name="email" placeholder="John@mail.com" value="<?= $email; ?>"/>
            </div>

            <div class="error">

                <?php
                if(!empty($errors['email'])) {
                    foreach ($errors['email'] as $message) {
                        echo "<p>$message</p>";
                    }
                }
                ?>
            </div>

        </div>

        <div class="input">

            <div class="item">
                <label for="password">Password</label>
                <input type="password" id="password" maxlength="100000" name="password" placeholder="min 8 characters"/>
            </div>

            <div class="error">

                <?php
                if(!empty($errors['password'])) {
                    foreach ($errors['password'] as $message) {
                        echo "<p>$message</p>";
                    }
                }
                ?>
            </div>


        </div>

        <button>Send</button>

    </form>
</main>

<?php  showFooter(); ?>

</body>
</html>



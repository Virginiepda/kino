<?php
include("start.php");

//variables contenant les données du form
$email = "";
$password = "";

//contient les éventuelles erreurs
$error = "";

//nous pouvons vériofier si le format a été soumis en vérifiant si il y a des données dnas la variable globale qui
//est un tableau  $_POST



if (!empty($_POST)) {

    //on récupère les données dans nos variables/////////////////////////
    //pour éviter les hacks ssr (quelqu'un mettant des balises, des scripts), on va ajouter deux couches de protection
    //couche 1 - ajouter strip_tags , ça enlève automatiquement toutes les balises

    $email = strip_tags($_POST['email']);
    $password = $_POST['password'];


    //on valide les données//////////////////////////////////////////////////
    //pour trier les erros et savoir des quelles je parle, je vais modifier le tableau errors[] en tableau associatif

    //vérification du pseudo


    $sql = "SELECT * 
            FROM users
            WHERE email = :email";

    $stmt = $dbh->prepare($sql);

    $stmt->execute([
        ':email' => $email
    ]);
    //on doit ensuite récupérer le résultat pour faire des traitements dessus
    //pour être sûr que le résultat soit un entier, on peut le caster en ajoutant un (int)

    $user = $stmt->fetch();


    if(!empty($user)){

        $verification = password_verify($password, $user['password']);

       if($verification) {

           $_SESSION['user'] = $user;

           $_SESSION['message-flash']= "Welcome at Kino,".$user['username']."!!!!";

           header("Location: index.php");
           die();
       }
    } else {

        $error = "Authentification failed !!";
    }


        //plutôt que de le faire sur la page de l'index, je vais aller le faire dans la fonction qui affiche le head


        //redirige vers la liste des utilisateurs
        //header ici veut dire entête http, et non pas header d'une page html


        //pour éviter le bug, et éviter que une fois la redirection demandée, le code s'execute quand même,
        //on met un die() après.



    }




showTop("Kino | Login");
showHeader();


?>


<main class="login">

    <h1>Login</h1>



    <form method="post" novalidate>
        <!--    action si on veut que le formulaire nous amène à une autre page, si on en a pas besoin, il vaut mieux
        ne pas l'initialiser-->
        <!--        côté serveur, la validation est aussi testée et si erreur, apparait un message que nous ne pouvons pas modifier-->
        <!--        nous allons donc le désactiver en ajoutant novalidate dans la balise form-->


        <div class="input">

            <div class="item">
                <label for="email">Email</label>
                <input type="email" name="email" placeholder="John@mail.com" value="<?= $email; ?>"/>
            </div>

        </div>

        <div class="input">

            <div class="item">
                <label for="password">Password</label>
                <input type="password" id="password" maxlength="100000" name="password" placeholder="min 8 characters"/>
            </div>

        </div>

        <div class="error">

            <?php
            if(!empty($error)) {

                echo "<p>$error</p>";
            }
            ?>
        </div>


        <button>Send</button>

    </form>
</main>

<?php  showFooter(); ?>

</body>
</html>
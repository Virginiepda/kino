




<?php

function showTop($title){
        ?>
        <!doctype html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport"
                  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <link rel="apple-touch-icon" sizes="57x57" href="/favicon/apple-icon-57x57.png">
            <link rel="apple-touch-icon" sizes="60x60" href="/favicon/apple-icon-60x60.png">
            <link rel="apple-touch-icon" sizes="72x72" href="/favicon/apple-icon-72x72.png">
            <link rel="apple-touch-icon" sizes="76x76" href="/favicon/apple-icon-76x76.png">
            <link rel="apple-touch-icon" sizes="114x114" href="/favicon/apple-icon-114x114.png">
            <link rel="apple-touch-icon" sizes="120x120" href="/favicon/apple-icon-120x120.png">
            <link rel="apple-touch-icon" sizes="144x144" href="/favicon/apple-icon-144x144.png">
            <link rel="apple-touch-icon" sizes="152x152" href="/favicon/apple-icon-152x152.png">
            <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-icon-180x180.png">
            <link rel="icon" type="image/png" sizes="192x192"  href="/favicon/android-icon-192x192.png">
            <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
            <link rel="icon" type="image/png" sizes="96x96" href="/favicon/favicon-96x96.png">
            <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
            <link rel="manifest" href="/favicon/manifest.jsonest.json">
            <meta name="msapplication-TileColor" content="#ffffff">
            <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
            <meta name="theme-color" content="#ffffff">
            <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
            <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
            <link rel="stylesheet" type="text/css" href="css/index.css" />
            <title><?= $title ?></title>
        </head>
        <body>

<?php

}

function showHeader(){
    ?>
<header>

    <div class="title">
        <h1>KINO</h1>
    </div>

    <div class="navigation">

        <div class="logo">
            <img class="logo" src="favicon/favicon-32x32.png"/>
        </div>

        <nav>
            <div class="menuleft">
            <a href="index.php">Home</a>
                <?php

                if(!isset($_SESSION['user'])) { ?>
                    <a href="register.php">Register</a>
                <?php } else { ?>
                    <a href="watchlist.php">My watchlist</a>
                <?php }  ?>

            <a href="">Reviews</a>
<!--             <a href=""><ul class="menu">-->
<!--                    <li>Categories-->
<!--                        <ul class="sous">-->
<!--                            <li>Western</li>-->
<!--                            <li>Thriller</li>-->
<!--                            <li>Drama</li>-->
<!--                            <li>Romance</li>-->
<!--                            <li>Western</li>-->
<!--                        </ul>-->
<!--                    </li>-->
<!--                 </ul></a>-->
            </div>

            <div class="menuright">

                <?php

                if(!isset($_SESSION['user'])) { ?>
                     <a href="login.php">Login</a>
                <?php } else { ?>
                    <p><?= $_SESSION['user']['username'] ?></p>
                    <a href="logout.php">Logout</a>
               <?php }  ?>


            </div>

        </nav>

    </div>

</header>

    <?php

    //si on a un message dans la sessin
    if(!empty($_SESSION['message-flash'])){
        echo '<div class="alert">';
        echo $_SESSION['message-flash'];
        echo '</div>';

        //ensuite on le supprime

        unset($_SESSION['message-flash']);
    }

    ?>

<?php
}

function showFooter(){ ?>

<footer>

    <div class="copyright">
        <p>&copy; 2019 kino.com<p>
    </div>
    <div class="legal">
        <a href="faq.php">FAQ</a>
        <a href="legal.php">Legal</a>
        <a href="contact.php">Contact us</a>
    </div>

</footer>



<?php
}

?>
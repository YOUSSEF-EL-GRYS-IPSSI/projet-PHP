<?php
session_start();

if (isset($_SESSION['connect'])){
    header('location:index.php');
}
require('src/connection.php');
if (!empty($_POST['email']) && !empty($_POST['password'])) {

    //VARIABLE
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $error    = 1;

    //CRYPTER LE PASSWORD
    $password = "Hack" . sha1($password . "78") . "78";


    $requete = $db->prepare("SELECT * FROM users WHERE email = ?");
    $requete->execute(array($email));

    while ($users = $requete->fetch()) {

        if ($password == $users['password']) {
            $error = 0;
            $_SESSION['connect'] = 1;
            $_SESSION['pseudo'] = $users['pseudo'];

            if (isset($_POST['connect'])){
                setcookie('log',$users['secret'],time() +365*24*3600 , '/',
                    null, false,true);

            }


            header('location:?succes=1');

        }

    }
    if ($error == 1) {
        header('location:?error=1');

    }


}


?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="design/feuille.css">
    <title>Document</title>
</head>

<body>
<header>
    <h1>Connexion</h1>
</header>
<div class="container">


    <p id="info"> bienvenue sur mon site, si vous n'etes pas inscrit,
        inscrivez-vous.Sinon, <a href="index.php"> Inscrivez-vous</a></p>
    <?php
    if (isset($_GET['error'])) {
        echo '<p id="error">Nous ne pouvons pas vous authentifier</p>';
    } else if (isset($_GET['succes'])) {
        echo '<p id="success"> Vous ête maintenant connecté</p>';
    }

    ?>

    <div id="form">
        <form method="post" action="connexion.php">
            <table>
                <tr>
                    <td>email</td>
                    <td><input type="email" name="email" placeholder="Example@google.com" required></td>
                </tr>
                <tr>
                    <td>mot de passe</td>
                    <td><input type="password" name="password" placeholder="Ex : *****" required></td>
                </tr>

            </table>
            <p><label><input type="checkbox" name="connect" required>Connexion automatique</label></p>

            <div id="button">
                <button>connexion</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
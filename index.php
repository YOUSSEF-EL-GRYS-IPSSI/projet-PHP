<?php
session_start();

require('src/connection.php');

if (!empty($_POST['pseudo']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password_confirm'])) {

    //VARIABLE
    $pseudo       = $_POST['pseudo'];
    $email        = $_POST['email'];
    $password     = $_POST['password'];
    $pass_confirm = $_POST['password_confirm'];

    //TEST SI PASSWORD = PASSWORD_CONFIRM
    if ($password != $pass_confirm) {
        header('location:?error=1&pass=1');
        $requete->execute(array($pass_confirm));
    }
    //TEST SI EMAIL UTILISE         SELECTIONNE TOUTE LES LIGNES DANS LA VARIABLE numbeEmail
    $requete = $db->prepare("SELECT  count(*) as numberEmail
             FROM users WHERE email = ?") or die(print_r($bdd->errorInfo()));
    $requete->execute(array($email));

    while ($email_verification = $requete->fetch()) {
        if ($email_verification['numberEmail'] != 0) {
            header('location:?error=1&email=1');

            exit();
        }

    }

    // Hash

    $secret = md5($email) . time();
    $secret = md5($secret) . time() . time();

    //CRYPTAGE DU PASSWORD
    $password = "Hack" . sha1($password . "78") . "78";

    // ENVOIE DE LA REQUETTE

    $requete = $db->prepare("INSERT INTO users(pseudo, email, password, secret) VALUES(?, ?, ?, ?)");
    $requete->execute(array($pseudo, $email, $password, $secret));

    header('location:?success=1');
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="design/feuille.css">
</head>

<body>
<?php
if(!isset($_SESSION['connect'])){ ?>

<header>
    <h1>Inscritption</h1>

</header>

<div class="container">




    <p id="info"> bienvenue sur mon site pour en voir plus,
        inscrivez-vous. Sinon <a href="connexion.php"> Connectez vous</a></p>

    <?php
    if (isset($_GET['error'])) {
        if (isset($_GET['pass'])) {
            echo '<p id="error"> les mots de passe ne sont pas identique</p>';
        } else if (isset($_GET['email'])) {
            echo '<p id="error">Cette adresse email est deja prise.</p>';
        }
    } else if (isset($_GET['success'])) {
        echo '<p id="success"> Inscription prise correctement en compte.</p>';
    }
    ?>


    <div id="form">
        <form method="post" action="index.php">
            <table>
                <tr>
                    <td>pseudo</td>
                    <td><input type="text" name="pseudo" placeholder="Ex: Max" required></td>
                </tr>
                <tr>
                    <td>email</td>
                    <td><input type="email" name="email" placeholder="Example@google.com" required></td>
                </tr>
                <tr>
                    <td>mot de passe</td>
                    <td><input type="password" name="password" placeholder="Ex : *****" required></td>
                </tr>
                <tr>
                    <td>veullez confirmé votre mot de passe</td>
                    <td><input type="password" name="password_confirm" placeholder="Ex: ****" required></td>
                </tr>

            </table>
            <div id="button">
                <button>inscription</button>
            </div>
        </form>
    </div>
    <?php } else { ?>
        <section>
            <div class="nav">
                <p id="pseudo">Bonjours <?= $_SESSION['pseudo'] ?></p>

                <p><a id="dcn" href="disconnections.php">Déconnexion</a></p>
            </div>
        </section>
        <div class="wrapper">
            <img src="glace.jpg">


        </div>



    <?php } ?>


</div>
</body>
</html>
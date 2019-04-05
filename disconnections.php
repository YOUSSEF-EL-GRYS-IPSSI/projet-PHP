<?php

session_start();// INITALISE LES SESSIONS
session_unset(); // DESACTIVER LA SESSION
session_destroy(); // DETRUIRE LA SESSION

setcookie('log', '', time()-3444, '/',
    null, false, true);



header('location:connexion.php');






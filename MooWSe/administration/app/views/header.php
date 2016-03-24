<?php
/* --------------------------------------------------
  Projet MOOWSE
  Fichier html
  Vue inclue dans chaque vue pour y incorporer les éléments communs

  Victor Enaud
  Ecole Centrale de Nantes
  -------------------------------------------------- */

// Déconnexion automatique au bout d'un certain laps de temps
if (isset($_SESSION['timestamp'])) { // si $_SESSION['timestamp'] existe
    if ($_SESSION['timestamp'] + 300 > time()) {
        $_SESSION['timestamp'] = time();
    } else {
        header("Location:../controllers/deconnexion.php"); // deconnexion au bout de 5 minutes d'inactivite
        exit();
    }
} else {
    $_SESSION['timestamp'] = time();
}
?>

<!DOCTYPE html>
<html lang="fr-fr">
    <head>
        <link href="../../public/css/accueil.css" type="text/css" rel="stylesheet" />
        <link rel="shortcut icon" href="../../public/img/favicon.ico" type="image/x-icon">
        <link rel="icon" href="../../public/img/favicon.ico" type="image/x-icon">
        <meta charset="UTF-8" />
        <script type="text/javascript" src="../../public/js/functions.js"></script>
        <title><?php echo($titre_web); ?></title>
    </head>
    <?php
    // Vérification de la présence d'une alerte
    if (isset($_SESSION['alert'])) {
        if ($_SESSION['alert'][0] == true) {
            // Le message est un message de confirmation
            ?>
            <div id="success_message">
                <?php print_r($_SESSION['alert'][1]) ?>
            </div>
            <?php
        } else if ($_SESSION['alert'][0] == false) {
            // Le message est un message d'erreur
            ?>
            <div id="error_message">
                <?php print_r($_SESSION['alert'][1]) ?>
            </div>
            <?php
        }
        // Suppression de la variable
        unset($_SESSION['alert']);
    }
    ?>
    <header class="header">
        <a href="accueil.php"><img src="../../public/img/elan.png" id="logo_moowse" title="MooWse - Accueil" alt="Accueil"></a>
        <h1><?php echo($titre_principal); ?></h1>
        <h2><?php echo($titre_section); ?></h2>
    </header>
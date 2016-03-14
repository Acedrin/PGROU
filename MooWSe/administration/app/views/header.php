<?php
/* --------------------------------------------------
  Projet MOOWSE
  Fichier html
  Vue inclue dans chaque vue pour y incorporer les éléments communs

  Victor Enaud
  Ecole Centrale de Nantes
  -------------------------------------------------- */
?>

<!DOCTYPE html>
<html lang="fr-fr">
    <head>
        <link href="../../public/css/accueil.css" type="text/css" rel="stylesheet" />
        <meta charset="UTF-8" />
        <title><?php echo($titre_web); ?></title>
    </head>
    <?php
    // Vérification de la présence d'une alerte
    if (isset($_SESSION['alert'])) {
        if ($_SESSION['alert'][0] == true) {
            // Le message est un message de confirmation
            ?>
            <div class="success_message">
                <?php print_r($_SESSION['alert'][1]) ?>
            </div>
            <?php
        } else {
            // Le message est un message d'erreur
            ?>
            <div class="error_message">
                <?php print_r($_SESSION['alert'][1]) ?>
            </div>
            <?php
        }
        // Suppression de la variable
        unset($_SESSION['alert']);
    }
    ?>
    <header class="header">
        <h1><?php echo($titre_principal); ?></h1>
        <h2><?php echo($titre_section); ?></h2>
    </header>
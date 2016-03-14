// Fonction pour valider le formulaire pour les Clients, vérifiant que les champs sont bien remplis
function validerFormulaireClient(type) {
    var alerte = "";
    if (!document.getElementsByName('client_name')[0].value.length > 0) {
        alerte += "Veuillez indiquer le nom du client !\n";
        document.getElementsByName('client_name')[0].style.backgroundColor = '#FF9900';
        document.getElementsByName('client_name')[0].style.color = '#000000';
    }
    if (!document.getElementsByName('client_ip')[0].value.length > 0) {
        alerte += "Veuillez indiquer l'ip du client !\n";
        document.getElementsByName('client_ip')[0].style.backgroundColor = '#FF9900';
        document.getElementsByName('client_ip')[0].style.color = '#000000';
    }
    /**On récupère l'élement select modality_id*/
    var selectElmt = document.getElementsByName('modality_id')[0];
    /**
     selectElmt.options correspond au tableau des balises <option> du select
     selectElmt.selectedIndex correspond à l'index du tableau options qui est actuellement sélectionné
     */
    if (selectElmt.options[selectElmt.selectedIndex].value == 0) {
        alerte += "Veuillez indiquer le mode de connexion du client !\n";
        document.getElementsByName('modality_id')[0].style.backgroundColor = '#FF9900';
        document.getElementsByName('modality_id')[0].style.color = '#000000';
    }
    if (type == 2) {
        if (document.getElementsByName('client_password')[0].value != document.getElementsByName('client_password_confirmation')[0].value) {
            alerte += "Veuillez renseigner deux fois le même mot de passe !\n";
            document.getElementsByName('client_password')[0].value = "";
            document.getElementsByName('client_password_confirmation')[0].value = "";

            document.getElementsByName('client_password')[0].style.backgroundColor = '#FF9900';
            document.getElementsByName('client_password')[0].style.color = '#000000';
            document.getElementsByName('client_password_confirmation')[0].style.backgroundColor = '#FF9900';
            document.getElementsByName('client_password_confirmation')[0].style.color = '#000000';
        }
    }

    if (alerte == "") {
        document.getElementsByName('formAdd')[0].submit();
    } else {
        alert(alerte);
    }
}

// Fonction pour valider le formulaire pour les Administrateurs, vérifiant que les champs sont bien remplis
function validerFormulaireUser() {
    var alerte = "";
    if (!document.getElementsByName('user_uid')[0].value.length > 0) {
        alerte += "Veuillez indiquer le login de l'administrateur !\n";
        document.getElementsByName('user_uid')[0].style.backgroundColor = '#FF9900';
        document.getElementsByName('user_uid')[0].style.color = '#000000';
    }

    // On récupère les élements select pour la date
    var selectJour = document.getElementsByName('jour')[0];
    var jour = selectJour.options[selectJour.selectedIndex].value;

    var selectMois = document.getElementsByName('mois')[0];
    var mois = selectMois.options[selectMois.selectedIndex].value;

    var selectAnnee = document.getElementsByName('annee')[0];
    var annee = selectAnnee.options[selectAnnee.selectedIndex].value;
    /**
     selectElmt.options correspond au tableau des balises <option> du select
     selectElmt.selectedIndex correspond à l'index du tableau options qui est actuellement sélectionné
     */
    if (jour == 0 || mois == 0 || annee == 0) {
        if (jour != 0) {
            alerte += "Vous ne pouvez pas laisser le jour à 0 si le mois et/ou l'année sont non nuls\n";
            document.getElementsByName('jour')[0].style.backgroundColor = '#FF9900';
            document.getElementsByName('jour')[0].style.color = '#000000';
        }
        if (mois != 0) {
            alerte += "Vous ne pouvez pas laisser le mois à 0 si le jour et/ou l'année sont non nuls\n";
            document.getElementsByName('mois')[0].style.backgroundColor = '#FF9900';
            document.getElementsByName('mois')[0].style.color = '#000000';
        }
        if (annee != 0) {
            alerte += "Vous ne pouvez pas laisser l'année à 0 si le jour et/ou le mois sont non nuls\n";
            document.getElementsByName('annee')[0].style.backgroundColor = '#FF9900';
            document.getElementsByName('annee')[0].style.color = '#000000';
        }
    }

    // On récupère la date actuelle
    var now = new Date();
    var anneeNow = now.getFullYear();
    var moisNow = now.getMonth() + 1;
    var jourNow = now.getDate();

    if (annee == anneeNow) {
        if (mois < moisNow) {
            alerte += "Vous ne pouvez pas mettre une date d'expiration inférieure à la date courante\n";
            document.getElementsByName('mois')[0].style.backgroundColor = '#FF9900';
            document.getElementsByName('mois')[0].style.color = '#000000';
        } else if (mois == moisNow) {
            if (jour < jourNow) {
                alerte += "Vous ne pouvez pas mettre une date d'expiration inférieure à la date courante\n";
                document.getElementsByName('jour')[0].style.backgroundColor = '#FF9900';
                document.getElementsByName('jour')[0].style.color = '#000000';
            }
        }
    }

    if (alerte == "") {
        document.getElementsByName('formAdd')[0].submit();
    } else {
        alert(alerte);
    }
}
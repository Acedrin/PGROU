// Fonction pour valider le formulaire pour les Clients, vérifiant que les champs sont bien remplis
function validerFormulaireClient(type) {
    var alerte = "";
    if ((type == 1) || (type == 3)) {
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
    }
    if ((type == 2) || (type == 3)) {
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

// Fonction pour valider le formulaire pour les serveurs, vérifiant que les champs sont bien remplis
function validerFormulaireServeur() {
    var alerte = "";
    if (!document.getElementsByName('server_name')[0].value.length > 0) {
        alerte += "Veuillez indiquer le nom du serveur !\n";
        document.getElementsByName('server_name')[0].style.backgroundColor = '#FF9900';
        document.getElementsByName('server_name')[0].style.color = '#000000';
    }
    if (!document.getElementsByName('server_soapadress')[0].value.length > 0) {
        alerte += "Veuillez indiquer l'adresse SOAP du serveur !\n";
        document.getElementsByName('server_soapadress')[0].style.backgroundColor = '#FF9900';
        document.getElementsByName('server_soapadress')[0].style.color = '#000000';
    }

    if (alerte == "") {
        document.getElementsByName('formAdd')[0].submit();
    } else {
        alert(alerte);
    }
}

// Fonction pour valider le formulaire pour les fonctions, vérifiant que les champs sont bien remplis
function validerFormulaireFonction(number) {
    var alerte = "";
    for (i = 0; i < number; i++) {
        if (!document.getElementById('function_name' + i).value.length > 0) {
            alerte += "Veuillez indiquer le nom de la fonction n°" + i + " !\n";
            document.getElementById('function_name' + i).style.backgroundColor = '#FF9900';
            document.getElementById('function_name' + i).style.color = '#000000';
        }

        /**On récupère l'élement select server_id*/
        var selectElmt = document.getElementById('server_id' + i);
        /**
         selectElmt.options correspond au tableau des balises <option> du select
         selectElmt.selectedIndex correspond à l'index du tableau options qui est actuellement sélectionné
         */
        if (selectElmt.options[selectElmt.selectedIndex].value == 0) {
            alerte += "Veuillez indiquer le serveur de la fonction n°" + i + " !\n";
            document.getElementById('server_id' + i).style.backgroundColor = '#FF9900';
            document.getElementById('server_id' + i).style.color = '#000000';
        }
        if (alerte != "") {
            alerte += "\n";
        }
    }
    if (alerte == "") {
        document.getElementsByName('formAdd')[0].submit();
    } else {
        alert(alerte);
    }
}

// Fonction pour valider le formulaire pour les types, vérifiant que les champs sont bien remplis
function validerFormulaireType() {
    var alerte = "";
    if (!document.getElementsByName('type_name')[0].value.length > 0) {
        alerte += "Veuillez indiquer le nom du type !\n";
        document.getElementsByName('type_name')[0].style.backgroundColor = '#FF9900';
        document.getElementsByName('type_name')[0].style.color = '#000000';
    }
    if (alerte == "") {
        document.getElementsByName('formAdd')[0].submit();
    } else {
        alert(alerte);
    }
}

// Fonction pour valider le formulaire pour les types complexes, vérifiant que les champs sont bien remplis
function validerFormulaireTypeComplex() {
    var alerte = "";

    /**On récupère l'élement select typecomplex_order*/
    var selectElmt = document.getElementsByName('typecomplex_order')['0'];
    /**
     selectElmt.options correspond au tableau des balises <option> du select
     selectElmt.selectedIndex correspond à l'index du tableau options qui est actuellement sélectionné
     */
    if (selectElmt.options[selectElmt.selectedIndex].value == 0) {
        alerte += "Veuillez indiquer l'ordre du sous-type !\n";
        document.getElementsByName('typecomplex_order')['0'].style.backgroundColor = '#FF9900';
        document.getElementsByName('typecomplex_order')['0'].style.color = '#000000';
    }
    if (alerte != "") {
        alerte += "\n";
    }

    /**On récupère l'élement select typecomplex_type*/
    var selectElmt = document.getElementsByName('typecomplex_type')['0'];
    /**
     selectElmt.options correspond au tableau des balises <option> du select
     selectElmt.selectedIndex correspond à l'index du tableau options qui est actuellement sélectionné
     */
    if (selectElmt.options[selectElmt.selectedIndex].value == 0) {
        alerte += "Veuillez indiquer le type du sous-type !\n";
        document.getElementsByName('typecomplex_type')['0'].style.backgroundColor = '#FF9900';
        document.getElementsByName('typecomplex_type')['0'].style.color = '#000000';
    }
    if (alerte == "") {
        document.getElementsByName('formAdd')[0].submit();
    } else {
        alert(alerte);
    }
}

// Fonction pour valider le formulaire pour les variables, vérifiant que les champs sont bien remplis
function validerFormulaireVariable() {
    var alerte = "";

    if (!document.getElementsByName('variable_name')[0].value.length > 0) {
        alerte += "Veuillez indiquer le nom de la variable !\n";
        document.getElementsByName('variable_name')[0].style.backgroundColor = '#FF9900';
        document.getElementsByName('variable_name')[0].style.color = '#000000';
    }

    if (!document.getElementsByName('variable_order')[0].value.length > 0) {
        alerte += "Veuillez indiquer l'ordre de la variable !\n";
        document.getElementsByName('variable_order')[0].style.backgroundColor = '#FF9900';
        document.getElementsByName('variable_order')[0].style.color = '#000000';
    }

    /**On récupère l'élément select type_id*/
    var selectElmt = document.getElementsByName('type_id')['0'];
    /**
     selectElmt.options correspond au tableau des balises <option> du select
     selectElmt.selectedIndex correspond à l'index du tableau options qui est actuellement sélectionné
     */
    if (selectElmt.options[selectElmt.selectedIndex].value == 0) {
        alerte += "Veuillez indiquer le type de la variable !\n";
        document.getElementsByName('type_id')['0'].style.backgroundColor = '#FF9900';
        document.getElementsByName('type_id')['0'].style.color = '#000000';
    }

    var checked = false;
    for (i = 0; i < document.getElementsByName('variable_input').length; i++) {
        if (document.getElementsByName('variable_input')[i].checked) {
            checked = true;
        }
    }

    if (!checked) {
        alerte += "Veuillez indiquer si la variable est en entr&eacute;e ou en sortie !\n";
        document.getElementsByName('variable_input')['0'].style.backgroundColor = '#FF9900';
        document.getElementsByName('variable_input')['0'].style.color = '#000000';
        document.getElementsByName('variable_input')['1'].style.backgroundColor = '#FF9900';
        document.getElementsByName('variable_input')['1'].style.color = '#000000';
    }

    if (alerte == "") {
        document.getElementsByName('formAdd')[0].submit();
    } else {
        alert(alerte);
    }
}

// Fonction pour faire disparaitre les messages d'alerte et de succès
window.setTimeout("closeDiv();", 5000);

function closeDiv() {
    var Temp = document.getElementById("success_message");
    if (Temp != null)
        Temp.style.display = "none";

    var Temp = document.getElementById("error_message");
    if (Temp != null)
        Temp.style.display = "none";
}

// Fonction pour définir le nombre de fonctions à ajouter
function nbFunctions() {
    var nb = prompt("Combien de fonctions voulez-vous ajouter ? (max 5)", 1);

    if (nb != null) {
        if (nb > 5) {
            nb = 5;
        }
        document.location.href = 'ajout_fonction.php?function_nb=' + nb;
    }
}

// Fonction permettant l'affichage d'un élément d'id "hide"
function show() {
    if (document.getElementById("hide").style.display == "none")
    {
        document.getElementById("hide").style.display = "";
    }
    else
    {
        document.getElementById("hide").style.display = "none";
    }
}
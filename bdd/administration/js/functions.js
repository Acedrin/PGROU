// Fonction pour valider le formulaire, vérifiant que les champs sont bien remplis
function validerFormulaire(type) {
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
            document.getElementsByName('client_password')[0].value="";
            document.getElementsByName('client_password_confirmation')[0].value="";

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
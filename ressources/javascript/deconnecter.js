// Listen for changes to the checkbox (when it's clicked)
document.getElementById('logOutToggle').addEventListener('change', function () {
    if (this.checked) {
        window.location.href = "controleurFrontal.php?controleur=connexion&action=deconnecter";
    }
});

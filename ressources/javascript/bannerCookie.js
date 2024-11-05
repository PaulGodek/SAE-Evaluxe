function closeBanner() {
    // Faire une requête pour définir le cookie
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "controleurFrontal.php?action=setCookie", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Une fois le cookie créé, masquer la bannière
            document.getElementById("cookie-banner").style.display = "none";
        }
    };
    xhr.send();
}
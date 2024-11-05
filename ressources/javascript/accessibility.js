function setCookie(name, value, days) {
    const expires = days ? new Date(Date.now() + days * 864e5).toUTCString() : 'Thu, 01 Jan 1970 00:00:00 UTC';
    document.cookie = name + '=' + encodeURIComponent(value) + '; expires=' + expires + '; path=/';
}

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return decodeURIComponent(parts.pop().split(';').shift());
}

function applyAccessibilitySettings() {
    if (getCookie("highContrast") === "1") {
        document.body.classList.add("high-contrast-mode");
        document.getElementById("highContrast").checked = true;
    }
    if (getCookie("largeFont") === "1") {
        document.body.classList.add("large-font-mode");
        document.getElementById("largeFont").checked = true;
    }
    if (getCookie("darkMode") === "1") {
        document.body.classList.add("dark-mode");
        document.getElementById("darkMode").checked = true;
    }
}

document.addEventListener("DOMContentLoaded", applyAccessibilitySettings);

document.getElementById("highContrast").addEventListener("change", function() {
    document.body.classList.toggle("high-contrast-mode", this.checked);
    setCookie("highContrast", this.checked ? "1" : "", this.checked ? 30 : 0);
});

document.getElementById("largeFont").addEventListener("change", function() {
    document.body.classList.toggle("large-font-mode", this.checked);
    setCookie("largeFont", this.checked ? "1" : "", this.checked ? 30 : 0);
});

document.getElementById("darkMode").addEventListener("change", function() {
    document.body.classList.toggle("dark-mode", this.checked);
    setCookie("darkMode", this.checked ? "1" : "", this.checked ? 30 : 0);
});

// accessibility.js

document.querySelectorAll("#accessibilityMenu input[type='checkbox']").forEach(function(option) {
    option.addEventListener("change", function() {
        // Khi bất kỳ checkbox nào được chọn, bỏ chọn toggle để ẩn menu
        document.getElementById("accessibilityToggle").checked = false;
    });
});

document.getElementById("highContrast").addEventListener("change", function() {
    document.body.classList.toggle("high-contrast-mode", this.checked);
});

document.getElementById("largeFont").addEventListener("change", function() {
    document.body.classList.toggle("large-font-mode", this.checked);
});

document.getElementById("darkMode").addEventListener("change", function() {
    document.body.classList.toggle("dark-mode", this.checked);
});

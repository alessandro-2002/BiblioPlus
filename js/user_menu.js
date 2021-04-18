function userMenuDrop() {
    document.getElementById("userMenu").classList.toggle("show");
}

// Chiude il dropdown quando si clicca altrove
window.onclick = function (event) {
    if (!event.target.matches('#menuButton')) {
        var dropdowns = document.getElementsByClassName("userMenu-content");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}
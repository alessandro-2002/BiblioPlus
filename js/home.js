/* redirect durante ricerca per isbn */
function searchISBN() {
    // div container delle copie
    var isbn = document.getElementById("isbn").value;

    //redirect in get
    window.location.href = "index.php?ISBN=" + isbn;
}
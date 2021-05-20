//aggiunta autore con bottone o da isbn (se da bottone name è vuoto)
function addAuthor(name) {
    // div container degli autori
    var container = document.getElementById("autori");

    //creo div principale
    var div = document.createElement("div");

    //assegno classe al div creato
    div.className = "form-group row autore";

    //aggiungo contenuto della riga nel form
    div.innerHTML = `<label for="" class="col-4 col-form-label">Autore:</label>
                    <div class="col-8">
                        <div class="input-group">
                            <input id="idCopy" name="authors[]" type="text" maxlength="45" class="form-control" value="` + name + `" required>
                            <button type="button" class="btn btn-default btn-xs remove" onclick="removeCopy(this)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>`;

    //append
    container.appendChild(div);
}

function removeCopy(e) {
    e.parentElement.parentElement.parentElement.remove();
}

function searchISBN() {

    // prende isbn dall'input
    var isbn = document.getElementById("ISBN").value;

    //pulisco la form
    document.getElementById("newBook").reset();

    //elimino tutti gli autori già inseriti
    $('.autore').remove();

    //rimetto l'ISBN
    document.getElementById("ISBN").value = isbn;

    //cerca json da ISBN
    $.ajax({
        dataType: 'json',
        url: 'https://www.googleapis.com/books/v1/volumes?q=isbn:' + isbn,
        success: handleResponse
    });

    function handleResponse(response) {
        //se non c'è risposta stampo errore
        if (response.totalItems != 1) {
            window.alert("Nessun titolo trovato con l'ISBN inserito.");
        } else {

            //altrimenti compilo i campi
            $.each(response.items, function (i, item) {

                //try catch per ogni elemento, se non viene trovato non assegno nulla
                try {
                    var title = item.volumeInfo.title;

                    if (title !== undefined)
                        document.getElementById("title").value = title;
                } finally {
                }

                try {
                    var cover = item.volumeInfo.imageLinks.thumbnail;

                    if (cover !== undefined)
                        document.getElementById("coverLink").value = cover;
                } finally {
                }

                try {
                    var subtitle = item.volumeInfo.subtitle;

                    if (subtitle !== undefined)
                        document.getElementById("subtitle").value = subtitle;
                } finally {
                }

                try {
                    var language = item.volumeInfo.language;

                    if (language !== undefined)
                        document.getElementById("language").value = language;
                } finally {
                }

                try {
                    var publisher = item.volumeInfo.publisher;

                    if (publisher !== undefined)
                        document.getElementById("publisher").value = publisher;
                } finally {
                }

                try {
                    var year = item.volumeInfo.publishedDate;

                    if (year !== undefined)
                        document.getElementById("year").value = year;
                } finally {
                }


                try {
                    var authors = item.volumeInfo.authors;

                    //aggiungo input con autore per ogni autore
                    $.each(authors, function (i, item) {
                        addAuthor(item);
                    });
                } finally {
                }






            });
        }
    }
}
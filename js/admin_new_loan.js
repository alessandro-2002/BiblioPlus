function addCopy() {
    // div container delle copie
    var container = document.getElementById("copie");

    //creo div principale
    var div = document.createElement("div");

    //assegno classe al div creato
    div.className = "form-group row";

    //aggiungo contenuto della riga nel form
    div.innerHTML = `<label for="" class="col-4 col-form-label"></label>
                    <div class="col-8">
                        <div class="input-group">
                            <input id="idCopy" name="idCopy[]" type="number" min="1" step="1" class="form-control" required>
                            <button type="button" class="btn btn-default btn-xs remove" onclick="removeCopy(this)">
                                <span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
                            </button>
                        </div>
                    </div>`;

    //append
    container.appendChild(div);

}

function removeCopy(e) {
    e.parentElement.parentElement.parentElement.remove();
}
function loadRecords(page = 1) {
    var query = document.getElementById('search').value;
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "cargar_registros.php?q=" + encodeURIComponent(query) + "&page=" + page, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            document.querySelector('#recordsTable tbody').innerHTML = xhr.responseText;
        } else {
            console.error("Error al cargar los registros: " + xhr.statusText);
        }
    };
    xhr.onerror = function() {
        console.error("Error de red al intentar cargar los registros.");
    };
    xhr.send();
}

function searchRecords() {
    loadRecords(1);
}

function loadPage(page) {
    loadRecords(page);
}

window.onload = function() {
    loadRecords();
};
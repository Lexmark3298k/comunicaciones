let currentSort = 'id'; // Columna de orden predeterminada
let currentOrder = 'asc'; // Orden predeterminado

// Función para cargar registros con soporte de búsqueda, paginación y ordenamiento
function loadRecords(page = 1, sort = 'id', order = 'asc') {
    const query = document.getElementById('search').value;
    const xhr = new XMLHttpRequest();

    xhr.open(
        "GET",
        `cargar_registros.php?q=${encodeURIComponent(query)}&page=${page}&sort=${sort}&order=${order}`,
        true
    );

    xhr.onload = function () {
        if (xhr.status === 200) {
            document.querySelector('#recordsTable tbody').innerHTML = xhr.responseText;
        } else {
            console.error("Error al cargar los registros: " + xhr.statusText);
        }
    };

    xhr.onerror = function () {
        console.error("Error de red al intentar cargar los registros.");
    };

    xhr.send();
}

// Función para buscar registros
function searchRecords() {
    loadRecords(1, currentSort, currentOrder);
}

// Función para cargar una página específica
function loadPage(page) {
    loadRecords(page, currentSort, currentOrder);
}

// Función para cambiar el orden de los registros
function sortRecords(column) {
    if (currentSort === column) {
        // Si ya está ordenado por esta columna, alternar entre asc y desc
        currentOrder = currentOrder === 'asc' ? 'desc' : 'asc';
    } else {
        // Cambiar a la nueva columna con orden ascendente por defecto
        currentSort = column;
        currentOrder = 'asc';
    }
    // Cargar registros con los nuevos parámetros de orden
    loadRecords(1, currentSort, currentOrder);
}

// Cargar registros al cargar la página
window.onload = function () {
    loadRecords();
};

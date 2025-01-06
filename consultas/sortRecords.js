let currentSort = 'id';
let currentOrder = 'asc';

function sortRecords(column) {
    if (currentSort === column) {
        currentOrder = currentOrder === 'asc' ? 'desc' : 'asc';
    } else {
        currentSort = column;
        currentOrder = 'asc';
    }
    loadRecords(1, currentSort, currentOrder);
}
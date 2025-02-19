document.getElementById('show-events').addEventListener('click', function() {
    document.getElementById('event-table').style.display = 'table';
    document.getElementById('member-table').style.display = 'none';
});

document.getElementById('show-members').addEventListener('click', function() {
    document.getElementById('event-table').style.display = 'none';
    document.getElementById('member-table').style.display = 'table';
});

// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('tbody tr');

    rows.forEach(row => {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
});
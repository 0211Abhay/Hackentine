document.addEventListener('DOMContentLoaded', function() {
    const viewDetailsButtons = document.querySelectorAll('.view-details');

    viewDetailsButtons.forEach(button => {
        button.addEventListener('click', function() {
            alert('Details for the event will be shown here.');
        });
    });
});
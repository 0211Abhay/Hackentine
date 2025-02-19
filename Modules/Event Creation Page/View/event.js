document.querySelectorAll('input[name="eventType"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        document.getElementById('customTypeInput').style.display =
            document.getElementById('otherType').checked ? 'block' : 'none';
    });
});
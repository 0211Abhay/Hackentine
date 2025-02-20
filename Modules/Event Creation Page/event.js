document.querySelectorAll('input[name="eventType"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        document.getElementById('customTypeInput').style.display =
            document.getElementById('otherType').checked ? 'block' : 'none';
    });
});


function toggleUniversityDropdown() {
    let universityDropdown = document.getElementById("universityDropdown");
    let universitySpecificRadio = document.querySelector('input[name="access_type"][value="university-specific"]');

    if (universitySpecificRadio.checked) {
        universityDropdown.style.display = "block";  // Show dropdown
    } else {
        universityDropdown.style.display = "none";  // Hide dropdown
    }
}

// Ensure dropdown visibility on page load (in case user reloads)
document.addEventListener("DOMContentLoaded", toggleUniversityDropdown);

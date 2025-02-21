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
        universityDropdown.style.display = "block"; // Show dropdown
    } else {
        universityDropdown.style.display = "none"; // Hide dropdown
    }
}

document.addEventListener("DOMContentLoaded", function() {
    const otherTypeRadio = document.getElementById("otherType");
    const customTypeInput = document.getElementById("customTypeInput");

    // Listen for changes in the radio buttons
    document.querySelectorAll('input[name="event_type"]').forEach((radio) => {
        radio.addEventListener("change", function() {
            if (otherTypeRadio.checked) {
                customTypeInput.style.display = "block";
                customTypeInput.setAttribute("name", "event_type_custom"); // Ensure it's included in form submission
            } else {
                customTypeInput.style.display = "none";
                customTypeInput.removeAttribute("name"); // Prevent unnecessary form submission
            }
        });
    });
});

// Ensure dropdown visibility on page load (in case user reloads)
document.addEventListener("DOMContentLoaded", toggleUniversityDropdown);
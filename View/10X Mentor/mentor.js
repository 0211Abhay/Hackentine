document.getElementById("show-chapters").addEventListener("click", function() {
    document.getElementById("chapter-table").style.display = "table";
    document.getElementById("event-table").style.display = "none";
});

document.getElementById("show-events").addEventListener("click", function() {
    document.getElementById("chapter-table").style.display = "none";
    document.getElementById("event-table").style.display = "table";
});

document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("search-input");

    searchInput.addEventListener("keyup", function () {
        let filter = searchInput.value.toLowerCase();
        let activeTable = document.querySelector(".event-table:not([style*='display: none']) tbody");

        if (activeTable) {
            let rows = activeTable.getElementsByTagName("tr");

            for (let i = 0; i < rows.length; i++) {
                let cells = rows[i].getElementsByTagName("td");
                let rowText = "";

                for (let j = 0; j < cells.length; j++) {
                    rowText += cells[j].textContent.toLowerCase() + " ";
                }

                rows[i].style.display = rowText.includes(filter) ? "" : "none";
            }
        }
    });
});
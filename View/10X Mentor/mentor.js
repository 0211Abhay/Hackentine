document.getElementById("show-chapters").addEventListener("click", function() {
    document.getElementById("chapter-table").style.display = "table";
    document.getElementById("event-table").style.display = "none";
});

document.getElementById("show-events").addEventListener("click", function() {
    document.getElementById("chapter-table").style.display = "none";
    document.getElementById("event-table").style.display = "table";
});
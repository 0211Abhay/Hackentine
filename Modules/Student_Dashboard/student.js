document.addEventListener("DOMContentLoaded", function() {
    let dots = document.querySelectorAll(".dot");
    let index = 0;

    function changeDot() {
        dots.forEach(dot => dot.style.background = "gray");
        dots[index].style.background = "black";
        index = (index + 1) % dots.length;
    }

    setInterval(changeDot, 2000);
});
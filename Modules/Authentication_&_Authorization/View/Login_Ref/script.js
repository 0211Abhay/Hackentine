const form = document.querySelector("form");
const eField = form.querySelector(".email"),
      eInput = eField.querySelector("input"),
      pField = form.querySelector(".password"),
      pInput = pField.querySelector("input");

form.onsubmit = (e) => {
    e.preventDefault(); // Prevent form submission

    // Add shake and error class if fields are empty, else validate
    eInput.value === "" ? eField.classList.add("shake", "error") : checkEmail();
    pInput.value === "" ? pField.classList.add("shake", "error") : checkPass();

    // Remove shake class after 500ms
    setTimeout(() => {
        eField.classList.remove("shake");
        pField.classList.remove("shake");
    }, 500);

    // Validate on keyup
    eInput.onkeyup = () => checkEmail();
    pInput.onkeyup = () => checkPass();

    function checkEmail() {
        const pattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/; // Email validation pattern
        const errorTxt = eField.querySelector(".error-txt");

        if (!eInput.value.match(pattern)) {
            eField.classList.add("error");
            eField.classList.remove("valid");
            errorTxt.innerText = eInput.value !== "" ? "Enter a Valid Email Address" : "Email can't be Blank";
        } else {
            eField.classList.remove("error");
            eField.classList.add("valid");
        }
    }

    function checkPass() {
        if (pInput.value === "") {
            pField.classList.add("error");
            pField.classList.remove("valid");
        } else {
            pField.classList.remove("error");
            pField.classList.add("valid");
        }
    }

    // Redirect if both fields are valid
    if (!eField.classList.contains("error") && !pField.classList.contains("error")) {
        window.location.href = form.getAttribute("action");
    }
};
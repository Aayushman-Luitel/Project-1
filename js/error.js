const form = document.getElementById("myForm");
const nameInput = document.getElementById("name");
const errorSpan = document.getElementById("error");

form.addEventListener("submit", function (event) {
  const value = nameInput.value.trim();
  const pattern = /^[A-Za-z\s]+$/;

  if (!pattern.test(value)) {
    event.preventDefault();
    errorSpan.textContent = "Only letters and spaces are allowed.";
  } else if (value === "") {
    event.preventDefault();
    errorSpan.textContent = "Name cannot be empty or only spaces.";
  } else {
    errorSpan.textContent = "";
  }
});

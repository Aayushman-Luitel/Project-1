const form = document.getElementById("myForm");
const nameInput = document.getElementById("name");
const errorSpan = document.getElementById("error");

form.addEventListener("submit", function (event) {
  const value = nameInput.value.trim();
  const lettersOnly = value.replace(/\s/g, '');
  
  errorSpan.textContent = "";

  if(lettersOnly.length < 3 || lettersOnly.length > 20) {
    event.preventDefault();
    errorSpan.textContent = "Name must contain 3-20 letters (spaces ignored)";
  }
  else if(!/^[A-Za-z ]+$/.test(value)) {
    event.preventDefault();
    errorSpan.textContent = "Only letters and spaces allowed";
  }
});
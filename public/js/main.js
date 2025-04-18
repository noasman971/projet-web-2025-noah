/******/ (() => { // webpackBootstrap
/*!******************************!*\
  !*** ./resources/js/main.js ***!
  \******************************/
console.log("Testing JS file");
function displayLoading() {
  var form = document.getElementById("create");
  var loading = document.querySelector('.loading');
  var submitButton = document.getElementById("bilan_submit");
  form.addEventListener('submit', function () {
    loading.classList.remove('hidden');
    submitButton.setAttribute('disabled', true);
  });
}
displayLoading();
function setAnswer(answer) {
  var hiddenInput = document.querySelector('input[name="answer"]');
  hiddenInput.value = answer;
}
function uncheck(checkbox) {
  var checkboxes = document.querySelectorAll('.uncheckedboc');
  var hiddenInputs = checkboxes;
  hiddenInputs.forEach(function (hiddenInput) {
    if (!checkbox.checked) {
      hiddenInput.value += checkbox.value;
    } else {
      hiddenInput.value = null;
    }
  });
}
window.setAnswer = setAnswer;
window.uncheck = uncheck;
/******/ })()
;
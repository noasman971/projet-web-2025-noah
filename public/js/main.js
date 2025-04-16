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
window.setAnswer = setAnswer;
/******/ })()
;
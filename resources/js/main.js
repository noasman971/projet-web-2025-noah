console.log("Testing JS file");

function displayLoading() {
    const form = document.getElementById("create");
    const loading = document.querySelector('.loading');
    const submitButton = document.getElementById("bilan_submit");

    form.addEventListener('submit', function() {
        loading.classList.remove('hidden');
        submitButton.setAttribute('disabled', true);
    });

}
displayLoading()

function setAnswer(answer) {
    const hiddenInput = document.querySelector('input[name="answer"]');
    hiddenInput.value = answer;

}
function uncheck(checkbox) {
    const checkboxes =  document.querySelectorAll('.uncheckedboc');
    const hiddenInputs = checkboxes
    hiddenInputs.forEach(hiddenInput => {
        if (!checkbox.checked) {
            hiddenInput.value += checkbox.value;

        } else {
            hiddenInput.value = null;

        }
    });
}

window.setAnswer = setAnswer;
window.uncheck = uncheck;


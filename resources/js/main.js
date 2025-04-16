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
window.setAnswer = setAnswer;



console.log("Testing JS file");

function displayLoading() {
    const form = document.getElementById("create_qcm");
    const loading = document.querySelector('.loading');
    const submitButton = document.getElementById("bilan_submit");

    form.addEventListener('submit', function() {
        loading.classList.remove('hidden');
        submitButton.setAttribute('disabled', true);
    });
    console.log("Loading displayed");

}
displayLoading()

function setAnswer(answer) {
    const hiddenInput = document.querySelector('input[name="answer"]');
    hiddenInput.value = answer;
}
setAnswer()



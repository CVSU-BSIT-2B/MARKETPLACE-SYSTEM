const signUpbtn = document.getElementById("signUpbtn");
const signInbtn = document.getElementById("signInbtn");
const signInFormContainer = document.getElementById("signIn");
const signUpFormContainer = document.getElementById("signUp");

signUpbtn.addEventListener('click', function() {
    signInFormContainer.style.display = "none";
    signUpFormContainer.style.display = "block";
});

signInbtn.addEventListener('click', function() {
    signInFormContainer.style.display = "block";
    signUpFormContainer.style.display = "none";
});

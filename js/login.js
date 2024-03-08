const signUpButton = document.getElementById('custom-signUp');
const signInButton = document.getElementById('custom-signIn');
const container = document.getElementById('custom-container');

signUpButton.addEventListener('click', () => {
	container.classList.add("right-panel-active");
});

signInButton.addEventListener('click', () => {
	container.classList.remove("right-panel-active");
});
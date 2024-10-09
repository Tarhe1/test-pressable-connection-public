if (document.body.classList.contains('page-template-portal')) {
	function redirectToChangePassword() {
		window.location.href = '/?view=change-password';
	}

	document
		.querySelector('.pmpro_btn-submit[value="Change Password"]')
		.addEventListener('click', function () {
			redirectToChangePassword();
		});
}

import './style.scss';
import './editor.scss';

/*
 *	 This move the modals to before </body>
 */
const moveModals = document.querySelectorAll('.filter__modal');
if (moveModals) {
	moveModals.forEach((moveModal) => {
		document.querySelector('body').append(moveModal);
	});
}

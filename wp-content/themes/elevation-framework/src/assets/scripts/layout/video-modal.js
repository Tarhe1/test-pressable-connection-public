var jQ = jQuery.noConflict();

/*
 *	 This move the modals to before </body>
 */
const moveModals = document.querySelectorAll('.block__video .modal');
if (moveModals) {
	moveModals.forEach((moveModal) => {
		document.querySelector('body').append(moveModal);
	});
}

/*
 * To Reemplaze with Javascript Native
 */

jQ(document).ready(function () {
	jQ('button.video.mp4').click(function () {
		var theModal = jQ(this).data('bs-target'),
			videoSRC = jQ(this).attr('data-video'),
			videoSRCauto = videoSRC;
		jQ(theModal).find('video').css('display', 'block');
		jQ(theModal).find('iframe').css('display', 'none');
		jQ(theModal + ' video source').attr('src', videoSRCauto);

		setTimeout(function () {
			jQ(theModal + ' video')
				.get(0)
				.load();
			jQ(theModal + ' video')
				.get(0)
				.play();
		}, 500);

		jQ(theModal + ' button.close').click(function () {
			jQ(theModal + ' video source').attr('src', videoSRC);
			jQ(theModal + ' video')
				.get(0)
				.pause();
		});
	});
	jQ('button.video.youtube').click(function () {
		var theModal1 = jQ(this).data('bs-target'),
			videoSRC1 = jQ(this).attr('data-video'),
			videoSRCauto1 =
				videoSRC1 +
				'?modestbranding=1&rel=0&controls=1&showinfo=0&html5=1&autoplay=1';
		jQ(theModal1).find('video').css('display', 'none');
		jQ(theModal1).find('iframe').css('display', 'block');
		jQ(theModal1 + ' iframe').attr('src', videoSRCauto1);
		jQ(theModal1 + ' button.close, .modal').click(function () {
			jQ(theModal1 + ' iframe').attr('src', videoSRC1);
		});
	});
	jQ('button.video.vimeo').click(function () {
		var theModal1 = jQ(this).data('bs-target'),
			videoSRC1 = jQ(this).attr('data-video'),
			videoSRCauto1 = videoSRC1 + '?autoplay=1&loop=1&autopause=0';
		jQ(theModal1).find('video').css('display', 'none');
		jQ(theModal1).find('iframe').css('display', 'block');
		jQ(theModal1 + ' iframe').attr('src', videoSRCauto1);
		jQ(theModal1 + ' button.close, .modal').click(function () {
			jQ(theModal1 + ' iframe').attr('src', videoSRC1);
		});
	});

	jQ(document).on('keydown', function (event) {
		if (event.key == 'Escape') {
			jQ('.embed-responsive-item').attr('src', '');
		}
	});
	jQ('.modal[role="dialog"]').on('hidden.bs.modal', function (e) {
		jQ('.embed-responsive-item').attr('src', '');
	});
});

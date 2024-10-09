import './style.scss';
import './editor.scss';

/*
 * To Reemplaze with Javascript Native
 */

var jQ = jQuery.noConflict();
jQ(document).ready(function () {
	function myOrientResizeFunction() {
		var sectionImpact = jQ('.counter');
		if (sectionImpact.length !== 0) {
			var dTop = jQ('.counter').offset().top;
			//console.log(dTop);
			var once = 0;
			jQ(window).bind('scroll', function () {
				if (jQ(window).scrollTop() > dTop - 500) {
					if (once == 0) {
						//console.log('in');
						jQ('.numscroller').each(function () {
							jQ(this)
								.prop('Counter', 0)
								.animate(
									{
										Counter: parseInt(
											jQ(this).attr('data-num')
										),
									},
									{
										duration: 3000,
										easing: 'swing',
										step: function (now) {
											jQ(this).text(
												Math.ceil(now)
													.toString()
													.replace(
														/(\d)(?=(\d\d\d)+(?!\d))/g,
														'$1,'
													)
											);
										},
									}
								);
						});
						jQ('.numscrollerdec').each(function () {
							jQ(this)
								.prop('Counter', 0)
								.animate(
									{
										Counter: parseFloat(
											jQ(this).attr('data-num')
										).toFixed(1),
									},
									{
										duration: 3000,
										easing: 'swing',
										step: function (now) {
											jQ(this).text(
												parseFloat(now)
													.toFixed(1)
													.toString()
													.replace(
														/(\d)(?=(\d\d\d)+(?!\d))/g,
														'$1,'
													)
											);
										},
									}
								);
						});
						jQ('.numscrolleryears').each(function () {
							jQ(this)
								.prop('Counter', 0)
								.animate(
									{
										Counter: parseInt(jQ(this).text()),
									},
									{
										duration: 3000,
										easing: 'swing',
										step: function (now) {
											jQ(this).text(parseInt(now));
										},
									}
								);
						});
						once = 1;
					}
				} else {
					once = 0;
				}
			});
		}
	}
	myOrientResizeFunction();
});

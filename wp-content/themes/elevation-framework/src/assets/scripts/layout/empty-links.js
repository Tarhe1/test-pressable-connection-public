$(document).ready(function () {
	$('a[href="#"], a[href=""]').each(function () {
		$(this).removeAttr('href');
		$(this).attr('aria-disabled', 'true');
		$(this).addClass(' empty-link ');
		//$( this ).wrapInner( '<span></span>')
	});
});

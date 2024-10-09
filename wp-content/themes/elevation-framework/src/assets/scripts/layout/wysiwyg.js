/*
 * Important Text Wrap out class
 */
var elements1 = document.querySelectorAll('.site-main p .important-text');

for (var i = 0; i < elements1.length; i++) {
	var parentElement = elements1[i].parentNode;
	parentElement.classList.add('main-important-text');
}

var elements2 = document.querySelectorAll('.site-main ul li .important-text');
for (var j = 0; j < elements2.length; j++) {
	var grandParentElement = elements2[j].parentNode.parentNode;
	grandParentElement.classList.add('main-important-text');
}

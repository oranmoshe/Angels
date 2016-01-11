function addEventListeners() {
	document.getElementById("postText").addEventListener('click',showPost);
	document.getElementById("sendPost").addEventListener('click',directToPost);
	document.getElementById("alertClose").addEventListener('click',alertClose);
 }
 function addEventListenersTask() {
	document.getElementById("alertClose").addEventListener('click',alertClose);
	document.getElementById("textBoxTaskPost").addEventListener('click',clickOnTaskPost);
 }
function showPost () {
	var box = document.getElementById("newPost");
	box.id="newPostLarge";
	document.getElementById("extendedInput").id = "showExtendedInput";
	box.style.borderColor= "#c3bb38";
}
function directToPost () {
	location.href = "task.html";
}
function alertClose () {
	var parent = this.parentNode;
	$(parent).fadeOut();
}
function clickOnTaskPost () {
	var toggle = document.getElementsByClassName("toggle")[0];
	$(toggle).fadeIn();
	var parent = toggle.parentNode;
	parent.style.borderColor= "#c3bb38";
	
}



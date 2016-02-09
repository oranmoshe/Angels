function init() {
	$(document).ready(function(){
		$(".postComment").click(function(){
			animatePostBox("newPostLarge",this);
		});
		$(".postTask").click(function(){
			animatePostBox("newPostExtraLarge",this);
		});
		$(".toTextarea").click(function(){
			replaceToTextarea(this);
		});
		$(".lightBoxOppener").click(function(){
			startLightBox(this);
		});
		$(".closeLightBox").click(function(){
			closeLightBox();
		});
		$(".close").click(function(){
				closeParent(this);
		});
		// Friends Invites feature of making mini clone of chosen user view 
		$("ul#friends li input").click(function(){
			var index = $(this).parent().parent().index();
			var selected = $("#sumSelected").html();
			// create mini clone
			if (this.checked){
				// parent - mark and define index
				$(this).parent().parent().css("background-color","#fffcee");
				$(this).parent().parent().attr('data-friend-selected', index );
				// clone - remove the checkbox, bkg and minimize profile img and define index
				var clone = $(this).parent().parent().clone();
				$(clone).find("input").remove();
				$(clone).find("img").css("width","40px");
				$(clone).css("background-color","#ffffff");
				$(clone).attr('data-friend-selected', index );
				// friends_queue appends of clone
				$("#friends_queue").append(clone);
				// increment of sum
				$("#sumSelected").html(++selected);
			}
			else{// remove mini clone
				var id = $(this).parent().parent().attr("data-friend-selected");
				$("#friends_queue").find("[data-friend-selected='" + id + "']").remove();
				// clear the real user background
				$(this).parent().parent().css("background-color","#ffffff");
				// decrement of sum
				$("#sumSelected").html(--selected);
			}
			index++;
		});
	});

 }
 
  // Fade out the parent of element
 function closeParent(e){
 	$(e).parent().fadeOut();
 }
 // Animates struct of post by a given text input click. Large
function animatePostBox(effect,e) {
	$("#newPost").addClass(effect);
	$(".extendedInput").addClass("showExtendedInput");
	$(".extendedInput").removeClass("extendedInput");
	replaceToTextarea(e);
}
// Converts text input to texterea
function replaceToTextarea(e){
	    var textbox = $(document.createElement('textarea'));
	    $(textbox).addClass("tb");
	    $(textbox).attr("name","body");
	    $(textbox).attr("id","body");
	    $(e).replaceWith(textbox);
}
// Lightbox open and close listeners
function startLightBox(e){
	var element = $(e).attr("data-lightbox-id");
	$(('#' + element)).css("visibility", "visible");
}
function closeLightBox(){	
	$(".lightBoxContainer").css("visibility", "hidden");
}



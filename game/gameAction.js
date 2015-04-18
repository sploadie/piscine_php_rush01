$(document).ready(function() {
	$(function() {
		console.log("Document ready. (gameAction.js)");
		var onButtonClick = function(e) {
			e.preventDefault();
			var button = $(this);
			var post_url = button.attr('title');
			//alert("Clicked on button [" + post_url + "]");
			$.ajax({
				type: 'GET',
				url: post_url,
				success: function(msg) {
					//alert("Returned: [" + msg + "]");
					$('#ships').load('ships.php');
					$('#gui').load('gui.php', function() {
						$('.game-button').click(onButtonClick);
					});
				}
			});
		};
		$('.game-button').click(onButtonClick);
	});
});
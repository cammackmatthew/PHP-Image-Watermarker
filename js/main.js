var currentImage = $('#mainImage').attr('src');

$('body').on("click", ".footerImageLink", function() {
    var img = $(this).find('img').attr('src');
	$('#mainImage').attr('src', img);
    currentImage = img;
});

$('body').on("click", "#upload", function() {
    $("body").snackbar({
        alive: 2000000,
        content:"Uploading images, please wait and do not leave this page."
    })
});

$('body').on("click", "#exportSelected", function() {
    $("body").snackbar({
        alive: 4000,
        content:"Preparing image, it will download shortly"
    })
    var text = $('#userText').val();
    var position = $('#position').val();
    var size = $('#userTextSize').val();
    var selectedImage = currentImage;
    var textColour = $('#textColour').val();
    var dataString = 'userText=' + text + '&image=' + selectedImage + '&mode=export' + '&pos=' + position + '&size=' + size + '&textColour=' + textColour;
	$.ajax({
		type: "POST",
		url: "./exportSelected.php",
		data: dataString,
		success: function(response) {
			window.open(response);
		}
	});
	return false;
});

$('body').on("click", "#exportAll", function() {
    $('#confirmModal').modal('toggle');
});

$('body').on("click", "#downloadProceed", function() {
    $('#confirmModal').modal('toggle');
    $("body").snackbar({
        alive: 4000,
        content:"Preparing download, it will begin shortly"
    })
    var text = $('#userText').val();
    var position = $('#position').val();
    var size = $('#userTextSize').val();
    var selectedImage = currentImage;
    var textColour = $('#textColour').val();
    var dataString = 'userText=' + text + '&image=' + selectedImage + '&pos=' + position + '&size=' + size + '&textColour=' + textColour;
	$.ajax({
		type: "POST",
		url: "./export.php",
		data: dataString,
		success: function(response) {
			window.open(response);
		}
	});
	return false;
});  

$('body').on("click", "#updateSettings", function() {
    var text = $('#userText').val();
    var position = $('#position').val();
    var size = $('#userTextSize').val();
    var selectedImage = currentImage;
    var textColour = $('#textColour').val();
    var dataString = 'userText=' + text + '&image=' + selectedImage + '&mode=preview' + '&pos=' + position + '&size=' + size + '&textColour=' + textColour;
	$.ajax({
		type: "POST",
		url: "./exportSelected.php",
		data: dataString,
		success: function(response) {
            $('#mainImage').attr('src', response);
		}
	});
	return false;
});
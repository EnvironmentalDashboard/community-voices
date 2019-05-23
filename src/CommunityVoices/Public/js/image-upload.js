// This is copied from image-collection.js, and should be handled as one file
// loaded in both pages.
$('#file').on('change', function(e) {
	var names = $.map($(this).prop('files'), function(val) { return val.name; });
	var namesList = 'Selected ' + names.join(', ');

	$('#fileList').text(namesList);

	var file = this.files[0];
	var reader = new FileReader();

	reader.onloadend = function() {
    // https://stackoverflow.com/a/7585267/2397924
    // https://stackoverflow.com/a/33901415/2397924
		var exif = EXIF.readFromBinaryFile(this.result);

		// exif.DateTime will be the date of photo taken, set by a camera
		// if it does not exist, this may be a screenshot
		// we will default to the file time if it is set
		// to anything other than 0
		var date = exif.DateTime || exif.FileDateTime;

		// date may equal 0, but we don't want to fill in
		// with a value of 0
		if (date != 0)
			$('#dateTaken').val(date);

		$('#title').val(names[0]);
	};

	reader.readAsArrayBuffer(file);
});

function sortCheckboxes($e) {
  var sorted = [];
  $e.children().each(function(i) {
    var $input = $(this).children('input');
    var $label = $(this).children('label');
    sorted.push({
      id: $input.attr('id'),
      value: $input.val(),
      label: $label.text(),
      name: $input.attr('name'),
      checked: $input.prop('checked')
    });
  });
  sorted.sort(function(a, b) {
    if (a.checked && b.checked) {
      return cmp_str(a.label, b.label);
    }
    if (a.checked) {
      return -1;
    }
    if (b.checked) {
      return 1;
    }
    return cmp_str(a.label, b.label);
  });
  var html = '';
  for (var i = 0; i < sorted.length; i++) {
    var checked = (sorted[i].checked) ? 'checked="checked"' : '';
    html += '<div class="form-check"><input '+checked+' value="'+sorted[i].value+'" class="form-check-input" type="checkbox" name="'+sorted[i].name+'" id="'+sorted[i].id+'"><label for="'+sorted[i].id+'" class="form-check-label">'+sorted[i].label+'</label></div>';
  }
  return html;
}
function cmp_str(a, b) {
  if(a < b) return -1;
  if(a > b) return 1;
  return 0;
}

$('.sorted-checkboxes').each(function(i, container){
  $(container).each(function(j, checkboxes) {
    var $checkboxes = $(checkboxes);
    $checkboxes.html(sortCheckboxes($checkboxes)); // sort once initially
    $checkboxes.on("change", function() {
      $(this).html(sortCheckboxes($(this))); // resort every time checkbox checked
    });
  });
});

$('.delete-form').on('submit', function(e) {
  e.preventDefault();
  var form = $(this);
  var action = form.attr('action');
  $('#alert').addClass('alert-danger').removeClass('d-none alert-success');
  $('#alert-content').html('<strong>Stop!</strong> Are you sure you want to delete this item?');
  var btn = document.createElement("button");
  btn.appendChild(document.createTextNode("Delete"));
  btn.className = "btn btn-outline-danger btn-sm float-right";
  btn.style.position = 'relative';
  btn.style.bottom = '3px';
  btn.addEventListener('click', function() {
    form.parent().parent().remove();
    $('#alert').addClass('d-none');
    $.post(action);
  });
  $('#alert-content').append(btn);
});

$('.edit-form').on('submit', function(e) {
  e.preventDefault();
  $('#alert').addClass('alert-success').removeClass('d-none alert-danger');
  $('#alert-content').text('Updated ' + this.elements[0].value);
  $.ajax({
    url : $(this).attr('action') || window.location.pathname,
    type: $(this).attr('method') || "POST",
    data: $(this).serialize()
  });
});

$('#file').on('change', function(e) {
	var names = $.map($(this).prop('files'), function(val) { return val.name; });
	var namesList = 'Selected ' + names.join(', ');

	$('#fileList').text(namesList);

	var file = this.files[0];
	var reader = new FileReader();

	reader.onloadend = function() {
		$.post("/public/exif.php", { image: reader.result }, function( exif ) {
			// exif.DateTime will be the date of photo taken, set by a camera
			// if it does not exist, this may be a screenshot
			// we will default to the file time if it is set, otherwise
			// the current time
			var date = exif.DateTime || (exif.FileDateTime > 0 ? exif.FileDateTime : new Date(Date.now()).toLocaleString());

			$('#dateTaken').val(date);
			$('#title').val(names[0]);
		}, "json").fail(function (r) {
			// If we have no data, we will empty out our auto-filled data.
				$('#dateTaken').val("");
			$('#title').val("");
		});
	};

	reader.readAsDataURL(file); // https://stackoverflow.com/a/20285053/2624391
});

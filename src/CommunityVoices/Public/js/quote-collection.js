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

var targets = [$('#sorted-tags'), $('#sorted-contentCategories'), $('#sorted-attribution'), $('#sorted-subattribution')];
$(targets).each(function(i, target){
	target.html(sortCheckboxes(target)); // sort once initially
  target.on("change", function() {
  	$(this).html(sortCheckboxes($(this))); // resort every time checkbox checked
  });
});

$('.delete-btn').on('click', function(e) {
	e.preventDefault();
	if (confirm('You are about to delete a quote. This action can not be undone.')) {
		$(this).parent().parent().remove();
		var action = $(this).data('action');
		$.post(action);
	}
});
$('.unpair-btn').on('click', function(e) {
	e.preventDefault();
	if (confirm('You are about to unpair a quote. This action can not be undone.')) {
		var action = $(this).data('action');
		$.post(action).done(function(d) {
			location.reload();
    });
	}
});

var loadingIcon = "far fa-check-circle";
var successIcon = "fas fa-check-circle";
var failureIcon = "fas fa-exclamation-circle";

function setLoadingIcon (icon) {
	icon.attr("class", loadingIcon);
}

function setSuccessIcon (icon) {
	icon.attr("class", successIcon);
}

function setFailureIcon (icon) {
	icon.attr("class", failureIcon);
}

function clearTooltip (icon) {
	icon.removeAttr("data-toggle");
	icon.removeAttr("data-placement");
	icon.removeAttr("title");
}

var tooltipExpire = 5000;

function addErrorsTooltip (icon, errors) {
	// Our combined string will be all errors separated by commas.
	// Due to the format of errors being lists inside of lists,
	// we need to run two joins jointly.
	var combinedString = Object.keys(errors).map(function (e) {
		return errors[e].join(", ");
	}).join(", ");

	icon.tooltip({
		title: combinedString,
		placement: "right"
	});
	icon.tooltip("show");

	// Automatically hide our information after five seconds.
	// Users can still pull it back up by hovering over the icon.
	setTimeout(function () {
		icon.tooltip("hide")
	}, tooltipExpire);
}

$('.save-quote-text').on('click', function(e) {
	e.preventDefault();
	var btn = $(this);
	var id = btn.data('id');
	var text = $("#text" + id).text();

	var icon = $('#modify-status' + id);
	clearTooltip(icon);
	setLoadingIcon(icon);

	$.post('/community-voices/api/quotes/' + id + '/edit', {text: text}).done(function(d) {
		if (Object.keys(d.errors).length > 0) {
			setFailureIcon(icon);

			addErrorsTooltip(icon, d.errors);
		} else {
			setSuccessIcon(icon);
		}
  	});
});

$('.approve-checkbox').on('click', function (c) {
	var target = c.target;
	var newValue = target.checked;
	var id = target.getAttribute('data-id');

	// approved = 3, pending = 1
	// probably should be able to pass this as strings
	var status = newValue ? 3 : 1;

	var icon = $("#modify-status" + id);
	clearTooltip(icon);
	setLoadingIcon(icon);

	$.post('/community-voices/api/quotes/' + id + '/edit', {status: status}).done(function(d) {
		if (Object.keys(d.errors).length > 0) {
			setFailureIcon(icon);

			addErrorsTooltip(icon, d.errors);

			target.checked = !target.checked;
		} else {
			setSuccessIcon(icon);
		}
	});
});

$('#fileUploadButton').on('click', function (c)  {
	alert("Guide To Uploading: Upload 2 files, one with quote information, one with source data information. \nBoth should be in csv format. Ensure that quote sheet has word 'quote' and that source sheet has word 'source' contained within.");
	$('#file').click();
});

$("#file").change(function(){
    $('#batchUploadForm').submit();
});

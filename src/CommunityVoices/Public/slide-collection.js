function sortCheckboxes($e) {
	var sorted = [];
	$e.children().each(function(i) { 
		var $input = $(this).children('input');
		var $label = $(this).children('label');
		sorted.push({
			id: $input.attr('id'),
			value: $input.val(),
			label: $label.text(),
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
		html += '<div class="form-check"><input '+checked+' value='+sorted[i].name+' class="form-check-input" type="checkbox" name="tags[]" id="'+sorted[i].id+'"><label for="'+sorted[i].id+'" class="form-check-label">'+sorted[i].label+'</label></div>';
	}
	return html;
}
function cmp_str(a, b) {
	if(a < b) return -1;
	if(a > b) return 1;
	return 0;
}

var targets = [$('#sorted-cc'), $('#sorted-tags'), $('#sorted-photographers'), $('#sorted-image-attributions'), $('#sorted-quote-attributions')];
for (var i = targets.length - 1; i >= 0; i--) {
	targets[i].html(sortCheckboxes(targets[i])); // sort once initially
	targets[i].children().children('input').change(function() { // resort every time checkbox checked
		var target = $(this).parent().parent();
		target.html(sortCheckboxes(target));
	});
}


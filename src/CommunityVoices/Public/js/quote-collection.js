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

var targets = [$('#sorted-tags'), $('#sorted-contentCategories'), $('#sorted-photographers'), $('#sorted-orgs')];
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

$('.save-quote-text').on('click', function(e) {
	e.preventDefault();
	var btn = $(this);
	var id = btn.data('id');
	var text = $('#text' + id).text();
	btn.text('Loading...');
	$.post('/community-voices/api/quotes/' + id + '/edit', {text: text}).done(function(d) {
		btn.text('Saved!');
  }).fail(function (f) {
	  btn.text('Failed!');
  }).always(function () {
	  setTimeout(function() { btn.text('Save changes'); }, 2500);
  });
});

$('.approve-checkbox').on('click', function (c) {
	var target = c.target;
	var newValue = target.checked;
	var id = target.getAttribute('data-id');

	var status = newValue ? 3 : 1;

	$.post('/community-voices/api/quotes/' + id + '/edit', {status: status}).done(function(d) {

	});
});

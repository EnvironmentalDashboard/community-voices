// form is the form that we are submitting (an edit form)
// whatUpdated is a string of what we will say we updated
function submitEdit (form, whatUpdated) {
    var data = $(form).serializeArray();

    $('#alert').addClass('alert-success').removeClass('d-none alert-danger');
    $('#alert-content').text('Updating...');

    $.ajax({
      url : $(form).attr('action') || window.location.pathname,
      type: $(form).attr('method') || "POST",
      data: $.param(data),
      success: function (data) {
          $('#alert-content').text('Updated ' + whatUpdated);
      },
      error: function (data) {
          $('#alert').addClass('alert-danger').removeClass('d-none alert-success');
          $('#alert-content').text('Failed to update ' + whatUpdated);
      }
    });
}
$('.edit-form').on('submit', function(e) {
  e.preventDefault();

  submitEdit(this, this.elements.label.value);
});

$('.delete-form').on('submit', function(e) {
  e.preventDefault();
  var form = $(this);
  var action = form.attr('action');
  $('#alert').addClass('alert-danger').removeClass('d-none alert-success');
  $('#alert-content').html('<strong>Warning:</strong> Deleting tags will affect related ');
  var btn = document.createElement("button");
  btn.appendChild(document.createTextNode("Delete"));
  btn.className = "btn btn-outline-danger btn-sm float-right";
  btn.style.position = 'relative';
  btn.style.bottom = '3px';
  btn.addEventListener('click', function() {
    form.parent().parent().remove();
    $('#alert').removeClass('alert-danger').addClass('alert-success');
    $('#alert-content').text("Success!");
    setTimeout(function () {
        $('#alert').removeClass('alert-success').addClass('d-none');
    }, 2000);
    $.post(action);
  });
  $('#alert-content').append(btn);
});

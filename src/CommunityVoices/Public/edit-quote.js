$('#form').on('submit', function(e) {
    e.preventDefault();
    $('#alert').html('<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Success!</strong> Quote updated<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
    $.ajax({
        url : $(this).attr('action'),
        type: $(this).attr('method'),
        data: $(this).serialize()
    });
});
$('#form').on('submit', function(e) {
    e.preventDefault();

    // The following code is duplicated in image-collection.js,
    // so maybe it should be extended to be helper code for all?
    var data = $(form).serializeArray();

    // Force our forms to include a tags attribute, even if empty.
    // https://stackoverflow.com/a/17809177/2397924
    var names = data.map(function (d) {
        return d.name;
    });

    if (!names.includes("tags[]")) {
        data.push({
            name: "tags[]",
            value: ""
        });
    }

    $('#alert').html('<div class="alert alert-success alert-dismissible fade show" role="alert">Quote updating...<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');

    $.ajax({
        url : $(this).attr('action'),
        type: $(this).attr('method'),
        data: $.param(data),
        success: function (data) {
            $('#alert').html('<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Success!</strong> Quote updated<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        },
        error: function (data) {
            $('#alert').html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Failure!</strong> Quote updated<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        }
    });
});

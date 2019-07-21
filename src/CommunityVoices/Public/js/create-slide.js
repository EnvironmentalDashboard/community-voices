var quote_page = 1, image_page = 1;
var quote_search = '', quote_tags = [], quote_attrs = [];
var image_search = '', image_tags = [], photographers = [], orgs = [];
if ($('#slide_text').length) { // if youre editing slide
    var current_text = $('#slide_text').val(),
        current_attr = $('#slide_attr').val(),
        current_image = $('#slide_image').val(),
        current_ccid = $('#slide_cc').val(),
        current_logo = $('#slide_logo').val();
} else { // creating slide
    var current_text = 'Quote goes here',
        current_attr = 'Attribution',
        current_image = 10,
        current_ccid = 1,
        current_logo = null;
}
var $quote_container = $('#ajax-quote');
var $image_container = $('#ajax-image');
var $content_categories = $('#content-categories');
var $prev_btn = $('#quote-btn');
getQuote(1);
getImage(1);
$(document).on('click', '.ajax-quote', function(e) { // need attach event handler this way bc targeted elements are dynamically generated
    current_text = $(this).data('text');
    current_attr = $(this).data('attribution');
    renderSlide(current_text, current_attr, current_image, current_ccid, current_logo);
    $("input[name='quote_id']").val($(this).data('id'));
});
$(document).on('click', '.ajax-image', function(e) {
    // This will set either the logo or the image depending on what
    // we have currently selected.
    var isImage = $prev_btn.attr('id') === 'img-btn';

    if (isImage)
        current_image = $(this).data('id');
    else
        current_logo = $(this).data('id');

    $("input[name='" + (isImage ? 'image_id' : 'logo_id') + "']").val($(this).data('id'));

    renderSlide(current_text, current_attr, current_image, current_ccid, current_logo);
});
$('#content-categories div.embed-responsive').on('click', function() {
    current_ccid = $(this).children('iframe').data('id');
    renderSlide(current_text, current_attr, current_image, current_ccid, current_logo);
    $("input[name='content_category']").val(current_ccid);
});
$('#quote-btn').on('click', function(e) {
    e.preventDefault();
    $quote_container.css('display', '');
    $image_container.css('display', 'none');
    $content_categories.css('display', 'none');
    $(this).addClass('active');
    $prev_btn.removeClass('active');
    $prev_btn = $(this);
    $('#filter-quotes').parent().css('display', '');
    $('#filter-images').parent().css('display', 'none');
});
function openImages (btn) {
    $quote_container.css('display', 'none');
    $image_container.css('display', '');
    $content_categories.css('display', 'none');
    btn.addClass('active');
    $prev_btn.removeClass('active');
    $prev_btn = btn;
    $('#filter-quotes').parent().css('display', 'none');
    $('#filter-images').parent().css('display', '');
}
$('#img-btn').on('click', function(e) {
    e.preventDefault();
    openImages($(this));
    $('#clear-image').css('display', 'none');
});
$('#cc-btn').on('click', function(e) {
    e.preventDefault();
    $quote_container.css('display', 'none');
    $image_container.css('display', 'none');
    $content_categories.css('display', '');
    $(this).addClass('active');
    $prev_btn.removeClass('active');
    $prev_btn = $(this);
    $('#filter-quotes').parent().css('display', 'none');
    $('#filter-images').parent().css('display', 'none');
});
$('#logo-btn').on('click', function (e) {
    e.preventDefault();
    openImages($(this));
    $('#clear-image').css('display', 'inline-block');
});
$('#next-quote').on('click', function(e) {
    e.preventDefault();
    $quote_container.find('.selectables').empty();
    getQuote(++quote_page);
});
$('#next-image').on('click', function(e) {
    e.preventDefault();
    $image_container.find('.selectables').empty();
    getImage(++image_page);
});
$('#clear-image').on('click', function(e) {
    e.preventDefault();

    current_logo = null;
    $("input[name='logo_id']").val(null);

    renderSlide(current_text, current_attr, current_image, current_ccid, current_logo);
});
$('#filter-quotes').on('submit', function(e) {
    e.preventDefault();
    quote_search = $('#search-quotes').val();
    quote_tags = [];
    $('.qtag-check:checkbox:checked').each(function() {
        quote_tags.push($(this).val());
    });
    quote_tags = $('#quote-tags').val();
    quote_attrs = [];
    $('.attr-check:checkbox:checked').each(function() {
        quote_attrs.push($(this).val());
    });
    $quote_container.find('.selectables').empty();
    quote_page = 1;
    getQuote(quote_page);
});
$('#filter-images').on('submit', function(e) {
    e.preventDefault();
    image_search = $('#search-quotes').val();
    image_tags = [];
    $('.itag-check:checkbox:checked').each(function() {
        image_tags.push((this).val());
    });
    $('.photo-check:checkbox:checked').each(function() {
        photographers.push($(this).val());
    });
    $('.org-check:checkbox:checked').each(function() {
        orgs.push($(this).val());
    });
    $image_container.find('.selectables').empty();
    image_page = 1;
    getImage(image_page);
});

function goToSlidesList () {
    // What if the API could give a path given an argument of the route
    // name? Would be kind of effectively doing what is done here.
    var here = window.location.href;
    var cv = here.substring(here.lastIndexOf("community-voices"), -1);

    window.location.replace(cv + "community-voices/slides");
}

var delete_form = $('#delete-form');
if (delete_form.length > 0) {
    delete_form.on('submit', function(e) {
        e.preventDefault();

        var action = $(this).attr('action');

        if (confirm('Are you sure? This action can not be undone.')) {
            $.post(action).done(function(d) {
                goToSlidesList();
            });
        }
    });
}
var form = $('#form');
if (form.length > 0) {
    form.on('submit', function(e) {
        e.preventDefault();

        // This could use helper functions for constructing this HTML.
        $('#alert').html('<div class="alert alert-secondary alert-dismissible fade show" role="alert">Loading... (will redirect upon success)<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');

        $.ajax({
            url : $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(this).serialize(),
            success: function (data) {
                // If our slide is successfully processed, we will redirect
                // back to the list of slides, which is a way of showing success.
                if (data.error && Object.keys(data.error).length == 0) {
                    goToSlidesList();
                } else {
                    // If our slide fails to be processed, we will display this.
                    var alert = $("#alert .alert");

                    // This is similar code as quote-collection.js, and thus
                    // should be transferred to a helper function.
                    var combinedString = Object.keys(data.error).map(function (e) {
                        return Array.isArray(data.error[e]) ? data.error[e].join(", ") : data.error[e];
                    }).join(", ");

                    if (alert.length == 0) {
                        $('#alert').html('<div class="alert alert-danger alert-dismissible fade show" role="alert">Failed! ' + combinedString + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                    } else {
                        alert.removeClass("alert-secondary");
                        alert.addClass("alert-danger");

                        alert.text("Failed! " + combinedString);
                    }
                }
            }
        });
    });
}

var prev_end_use = null;
$('[data-end_use]').each(function(i, v) {
    var end_use = $(v).data('end_use');
    if (end_use !== prev_end_use) {
        var checkbox = '<div class="custom-control custom-checkbox d-inline"><input checked="checked" type="checkbox" class="custom-control-input" id="'+end_use+'-checkAll" onchange="var isChecked = this.checked; $(\'[data-end_use='+end_use+']\').each(function(i, e) {$(e).find(\'input\').prop(\'checked\', isChecked)});" /><label class="custom-control-label" for="'+end_use+'-checkAll">Check all</label></div>';
        $(this).before('<div class="mt-2"><p class="mb-0 mr-2 text-capitalize d-inline" style="text-decoration:underline">' + end_use + ' screens</p>' + checkbox + '</div>');
    }
    prev_end_use = end_use;
});

var prefill_image = getParameterByName('prefill_image');
var prefill_quote = getParameterByName('prefill_quote');
if (prefill_image) {
    $("input[name='image_id']").val(prefill_image);
    current_image = prefill_image;
    renderSlide(current_text, current_attr, current_image, current_ccid, current_logo);
}

if (prefill_quote) {
    $.getJSON('/community-voices/api/quotes/'+prefill_quote, { }, function(data) {
        current_text = htmlDecode(data['quote']['text']);
        current_attr = data['quote']['attribution'];
        renderSlide(current_text, current_attr, current_image, current_ccid);
        $("input[name='quote_id']").val(data['quote']['id']);
    });
}

function renderSlide(quote_text, attribution, image, ccid, logo) {
    var iframe = document.getElementById('preview');

    $.getJSON('/community-voices/api/content-categories/' + ccid, {}, function (data) {
        var cc = data.contentCategory;
        var head = '<html><head><base href="' + window.location.origin + '" /><meta charset="utf-8" /><style>* { box-sizing:border-box }html, body { height: 100%; font-family:Comfortaa, sans-serif; }</style><link href="https://fonts.googleapis.com/css?family=Comfortaa:400,700" rel="stylesheet" /></head><body style="background:#000;margin:0;padding:0;">';
        var body = '<div style="display: flex;align-items:center;max-height:100%"><div><img src="/community-voices/uploads/'+
            image+'" style="flex-shrink: 0;width: auto;height: 86vh;max-width:70vw;max-height:100%" /></div><h1 style="color:#fff;padding:3vw;font-size:3vw;font-weight:400">'+
            quote_text+'<div style="font-size:2vw;margin-top:2vw">&#x2014; '+
            attribution+'</div></h1></div><div style="width:100%;background:'+
            cc.color+';position:absolute;bottom:0;height:14vh;text-transform:uppercase;color:#fff;font-size:7vh;line-height:14vh;font-weight:700;padding-left:1vw">'+
            (logo ? '<img src="/community-voices/uploads/' + logo + '" alt="" style="position:absolute;left:2vw;bottom:2vw;width:10vw;height:auto;" />' : '')+
            (logo ? '<span style="position:absolute;left:14vw;">' : '')+cc.label+(logo ? '</span>' : '')+
            '<img src="/community-voices/uploads/'+
            cc.image.image.id+'" alt="" style="position:absolute;right:3vw;bottom:2vw;width:25vw;height:auto" /></div></body></html>';
        iframe.src = 'data:text/html;charset=utf-8,' + encodeURIComponent(head + body);
    });
}

function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
}

function getQuote(page) {
    $.getJSON('/community-voices/api/quotes', { per_page: 15, page: page, search: quote_search, tags: quote_tags, attributions: quote_attrs, unused: 1 }, function(data) {
        var html = '<div class="card"><div class="card-header">Quotes</div><ul class="list-group list-group-flush">';
        $.each(data['quoteCollection'], function(index, element) {
            if (typeof element === 'object') {
                html += '<li class="list-group-item ajax-quote" data-id="'+element['quote']['id']+'" data-text="'+element['quote']['text']+'" data-attribution="'+element['quote']['attribution']+'"><blockquote class="blockquote mb-0"><p>'+element['quote']['text']+'</p><footer class="blockquote-footer">'+element['quote']['attribution']+'</footer></blockquote></li>';
            }
        });
        html += '</ul></div>';
        $quote_container.find('.selectables').append(html);
    });
}

function getImage(page) {
    $.getJSON('/community-voices/api/images', { per_page: 10, page: page, search: image_search, tags: image_tags, photographers: photographers, orgs: orgs, unused: 1 }, function(data) {
        var html = '<div class="card-columns">';
        $.each(data['imageCollection'], function(index, element) {
            if (typeof element === 'object') {
                html += '<div class="card bg-dark text-white ajax-image" data-id="'+element['image']['id']+'"><img class="card-img" src="https://environmentaldashboard.org/community-voices/uploads/'+element['image']['id']+'" alt="'+element['image']['title']+'" /></div>';
            }
        });
        html += '</div>';
        $image_container.find('.selectables').append(html);
    });
}

function htmlDecode(input){
  var e = document.createElement('div');
  e.innerHTML = input;
  return e.childNodes.length === 0 ? "" : e.childNodes[0].nodeValue;
}

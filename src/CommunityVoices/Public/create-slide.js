var quote_page = 1, image_page = 1;
var crop_img = false;
var quote_search = '', quote_tags = [], quote_attrs = [];
var image_search = '', image_tags = [], photographers = [], orgs = [];
if ($('#slide_text').length) {
    var current_text = $('#slide_text').val(),
        current_attr = $('#slide_attr').val(),
        current_image = $('#slide_image').val(),
        current_ccid = $('#slide_cc').val();
} else {
    var current_text = '"Tappan Square, you sense the history of this place as you’re walking through there. I think the trees in this town are amazing"',
        current_attr = 'Steve Hammond, Pastor, Peace Community Church',
        current_image = 10,
        current_ccid = 1;
}
var $quote_container = $('#ajax-quote');
var $image_container = $('#ajax-image');
var $content_categories = $('#content-categories');
getQuote(1);
getImage(1);
$(document).on('click', '.ajax-quote', function(e) { // need attach event handler this way bc targeted elements are dynamically generated
    current_text = $(this).data('text');
    current_attr = $(this).data('attribution');
    renderSlide(current_text, current_attr, current_image, current_ccid, crop_img);
    $("input[name='quote_id']").val($(this).data('id'));
});
$(document).on('click', '.ajax-image', function(e) {
    current_image = $(this).data('id');
    $("input[name='image_id']").val(current_image);
    renderSlide(current_text, current_attr, current_image, current_ccid, crop_img);
});
$('#content-categories img').on('click', function() {
    current_ccid = $(this).data('id');
    renderSlide(current_text, current_attr, current_image, current_ccid, crop_img);
    $("input[name='content_category']").val(current_ccid);
});
var $prev_btn = $('#quote-btn');
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
$('#img-btn').on('click', function(e) {
    e.preventDefault();
    $quote_container.css('display', 'none');
    $image_container.css('display', '');
    $content_categories.css('display', 'none');
    $(this).addClass('active');
    $prev_btn.removeClass('active');
    $prev_btn = $(this);
    $('#filter-quotes').parent().css('display', 'none');
    $('#filter-images').parent().css('display', '');
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
$('#crop').on('change', function(e) {
    e.preventDefault();
    if (e.checked) {
        crop_img = true;
    } else {
        crop_img = false;
    }
    renderSlide(current_text, current_attr, current_image, current_ccid, crop_img);
});
var delete_form = $('#delete-form');
if (delete_form.length > 0) {
    delete_form.on('submit', function(e) {
        e.preventDefault();
        var action = $(this).attr('action');
        $.post(action).done(function(d) {
            window.location.replace("https://environmentaldashboard.org/cv/slides");
        });
    });
}
var form = $('#form');
if (form.length > 0) {
    form.on('submit', function(e) {
        e.preventDefault();
        $('#alert').html('<div class="alert alert-success alert-dismissible fade show d-none" role="alert"><strong>Success!</strong> Slide updated<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        $.ajax({
            url : $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(this).serialize()
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
    renderSlide(current_text, current_attr, current_image, current_ccid, crop_img);
}

if (prefill_quote) {
    $.getJSON('https://api.environmentaldashboard.org/cv/quotes/'+prefill_quote, { }, function(data) {
        current_text = htmlDecode(data['quote']['text']);
        current_attr = data['quote']['attribution'];
        renderSlide(current_text, current_attr, current_image, current_ccid, crop_img);
        $("input[name='quote_id']").val(data['quote']['id']);
    });
}

function renderSlide(quote_text, attribution, image, ccid, crop_img) {
    var iframe = document.getElementById('preview');
    var cc = contentCategory(ccid);
    var head = '<html><head><meta charset="utf-8" /><style>* { box-sizing:border-box }html, body { height: 100%; font-family:Comfortaa, sans-serif; }</style><link href="https://fonts.googleapis.com/css?family=Comfortaa:400,700" rel="stylesheet" /><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.4.1/cropper.min.css" /></head><body style="background:#000;margin:0;padding:0;">';
    var cropperjs = (crop_img) ? '<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.4.1/cropper.min.js"></script><script>const crop_x = window.parent.document.getElementById("crop_x");const crop_y = window.parent.document.getElementById("crop_y");const crop_height = window.parent.document.getElementById("crop_height");const crop_width = window.parent.document.getElementById("crop_width");const image = document.getElementById("cropper-img");const cropper = new Cropper(image, {checkCrossOrigin: false, viewMode: 1, crop(event) {crop_x.value = event.detail.x;crop_y.value = event.detail.y;crop_width.value = event.detail.width;crop_height.value = event.detail.height;}});</script>' : '';
    var body = '<div style="display: flex;align-items:center;max-height:100%"><div><img src="https://environmentaldashboard.org/cv/uploads/'+image+'" style="flex-shrink: 0;width: auto;height: 86vh;max-width:70vw;max-height:100%" id="cropper-img" /></div><h1 style="color:#fff;padding:3vw;font-size:3vw;font-weight:400">'+quote_text+'<div style="font-size:2vw;margin-top:2vw">&#x2014; '+attribution+'</div></h1></div><div style="width:100%;background:'+cc.bg+';position:absolute;bottom:0;height:14vh;text-transform:uppercase;color:#fff;font-size:8vh;line-height:14vh;font-weight:700;padding-left:1vw">'+cc.text+'<img src="'+cc.image+'" alt="" style="position:absolute;right:3vw;bottom:2vw;width:25vw;height:auto" /></div>'+cropperjs+'</body></html>';
    iframe.src = 'data:text/html;charset=utf-8,' + encodeURIComponent(head + body);
}

function contentCategory(id) {
    switch (id) {
        case 1:
            return {text: 'Serving Our Community', image: 'https://environmentaldashboard.org/cv/public/1.png', bg: 'rgb(150,81,23)'}
        case 2:
            return {text: 'Our Downtown', image: 'https://environmentaldashboard.org/cv/public/2.png', bg: 'rgb(92,92,92)'}
        case 3:
            return {text: 'Next Generation', image: 'https://environmentaldashboard.org/cv/public/3.png', bg: 'rgb(4,54,75)'}
        case 4:
            return {text: 'Heritage', image: 'https://environmentaldashboard.org/cv/public/4.png', bg: 'rgb(86,114,34)'}
        case 5:
            return {text: 'Natural Oberlin', image: 'https://environmentaldashboard.org/cv/public/5.png', bg: 'rgb(67,118,45)'}
        case 6:
            return {text: 'Our Neighbours', image: 'https://environmentaldashboard.org/cv/public/6.png', bg: 'rgb(94,0,224)'}
    }
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
    $.getJSON('https://api.environmentaldashboard.org/cv/quotes', { per_page: 15, page: page, search: quote_search, tags: quote_tags, attributions: quote_attrs, unused: 1 }, function(data) {
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
    $.getJSON('https://api.environmentaldashboard.org/cv/images', { per_page: 10, page: page, search: image_search, tags: image_tags, photographers: photographers, orgs: orgs, unused: 1 }, function(data) {
        var html = '<div class="card-columns">';
        $.each(data['imageCollection'], function(index, element) {
            if (typeof element === 'object') {
                html += '<div class="card bg-dark text-white ajax-image" data-id="'+element['image']['id']+'"><img class="card-img" src="https://api.environmentaldashboard.org/cv/uploads/'+element['image']['id']+'" alt="'+element['image']['title']+'" /></div>';
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
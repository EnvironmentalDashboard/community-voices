var current_quote = 1, current_image = 1;
var quote_search = '', quote_tags = [], quote_attrs = [];
var image_search = '', image_tags = [], photographers = [], orgs = [];
var $quote_container = $('#ajax-quotes');
var $image_container = $('#ajax-images');
var $content_categories = $('#content-categories');
getQuote(1);
getImage(1);
$(document).on('click', '.ajax-quote', function(e) {
    $('#slide-text').text($(this).data('text'));
    $('#slide-attr').text($(this).data('attribution'));
    $("input[name='quote_id']").val($(this).data('id'));
});
$(document).on('click', '.ajax-image', function(e) {
    var id = $(this).data('id');
    $("input[name='image_id']").val(id);
    $('#slide-img').attr('src', 'https://api.environmentaldashboard.org/cv/uploads/'+id);
});
$('#content-categories img').on('click', function() {
    $('#preview-cc').remove();
    $("input[name='content_category']").val($(this).data('id'));
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
    getQuote(++current_quote);
});
$('#next-image').on('click', function(e) {
    e.preventDefault();
    $image_container.find('.selectables').empty();
    getImage(++current_image);
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
    getQuote(current_quote);
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
    getImage(current_image);
});

var prefill_image = getParameterByName('prefill_image');
var prefill_quote = getParameterByName('prefill_quote');
if (prefill_image) {
    $("input[name='image_id']").val(prefill_image);
    $('#slide-img').attr('src', 'https://api.environmentaldashboard.org/cv/uploads/'+prefill_image);
}

if (prefill_quote) {
    $.getJSON('https://api.environmentaldashboard.org/cv/quotes/'+prefill_quote, { }, function(data) {
        $('#slide-text').text(htmlDecode(data['quote']['text']));
        $('#slide-attr').text(data['quote']['attribution']);
        $("input[name='quote_id']").val(data['quote']['id']);
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
    $.getJSON('https://api.environmentaldashboard.org/cv/quotes', { per_page: 10, page: page, search: quote_search, tags: quote_tags, attributions: quote_attrs, unused: 1 }, function(data) {
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
    $.getJSON('https://api.environmentaldashboard.org/cv/images', { per_page: 8, page: page, search: image_search, tags: image_tags, photographers: photographers, orgs: orgs, unused: 1 }, function(data) {
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
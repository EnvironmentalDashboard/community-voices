$('.selector-img').on('click', function() {
    var cc = $(this).data('cc');
    if (cc === 'rand') {
        cc = getRandomInt(1, 6);
    }
    
    $.getJSON('https://environmentaldashboard.org/communitys-voices/api/slides', { content_category: [cc], per_page: 5, page: 1 }, function(data) {
        $.each(data['slideCollection'], function(index, element) {
            if (typeof element === 'object') {
                $('#slide' + (+index + +1)).attr('src', 'https://environmentaldashboard.org/community-voices/slides/' + element['slide']['id']);
            }
        });
    });
});
function getRandomInt(min, max) { return Math.floor(Math.random() * (max - min + 1)) + min; }

$('#searchables > a').on('click', function(e) {
    e.preventDefault();
    $('#search-form').attr('action', $(this).data('action'));
    $('#dropdown-btn').text($(this).text());
});

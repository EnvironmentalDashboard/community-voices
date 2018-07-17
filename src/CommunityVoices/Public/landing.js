$('.selector-img').on('click', function() {
    var cc = $(this).data('cc');
    if (cc === 'rand') {
        cc = getRandomInt(1, 6);
    }
    
    $.getJSON('https://api.environmentaldashboard.org/cv/slides', { content_category: [cc], per_page: 5, page: 2 }, function(data) {
        $.each(data['slideCollection'], function(index, element) {
            if (typeof element === 'object') {
                $('#slide' + (+index + +1)).attr('src', 'https://environmentaldashboard.org/cv/slides/' + element['slide']['id']);
            }
        });
    });
});
function getRandomInt(min, max) { return Math.floor(Math.random() * (max - min + 1)) + min; }
function makeSVG(tag, attrs) {
    var el = document.createElementNS('http://www.w3.org/2000/svg', tag);
    for (var k in attrs) {
        if (k == 'xlink:href') {
            el.setAttributeNS("http://www.w3.org/1999/xlink", 'xlink:href', attrs[k]);
        } else {
            el.setAttribute(k, attrs[k]);
        }
    }
    return el;
}
function formatText (s, attribution) {
    var text = makeSVG('text', {x: '50%', y: (25 + ( (10/s.length) * 75 ))+'%', fill: '#fff', 'font-size': '4px', 'font-family': 'Biko, Multicolore, Helvetica, sans-serif'});
    var arr = [];
    arr[0] = '';
    var tspans = 0, counter = 0;
    for (var i = 0; i < s.length; i++) {
        var char = s.charAt(i);
        arr[tspans] += char;
        if (counter++ > 16 && char === ' ') {
            tspans++;
            counter = 0;
            arr[tspans] = '';
        }
    }
    for (var i = 0; i < arr.length; i++) {
        var tspan = makeSVG('tspan', {dy: 4, x: '50%'});
        tspan = $(tspan).text(arr[i]);
        text.append(tspan[0]);
    }
    return text;
}
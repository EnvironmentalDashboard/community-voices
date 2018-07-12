<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:import href="Component.xslt" />

    <xsl:output method="html" doctype-system="about:legacy-compat"/>

    <xsl:template match="/domain">

    <html>
        <head>
            <title> <xsl:value-of select="title" /> </title>

            <link rel="stylesheet" href="https://environmentaldashboard.org/css/bootstrap.css"/>
            <xsl:if test="comfortaa != ''">
                <link href="https://fonts.googleapis.com/css?family=Comfortaa" rel="stylesheet" />
            </xsl:if>
        </head>
        <body>
            <div class="container">
                <xsl:call-template name="common-header" />
                <xsl:if test="navbarSection != ''">
                    <xsl:call-template name="sub-header" />
                </xsl:if>

                <xsl:value-of select="main-pane" disable-output-escaping="yes" />

                <xsl:call-template name="common-footer" />
            </div>
            <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
            <script>
            //<![CDATA[
            $(document).ready(function() {
                // from https://github.com/bootstrapthemesco/bootstrap-4-multi-dropdown-navbar
              $('.dropdown-menu a.dropdown-toggle').on('click', function (e) {
                var $el = $(this);
                var $parent = $(this).offsetParent(".dropdown-menu");
                if (!$(this).next().hasClass('show')) {
                  $(this).parents('.dropdown-menu').find('.card-columns').find( '.show').removeClass("show");
                }
                var $subMenu = $(this).next(".dropdown-menu");
                $subMenu.toggleClass('show');
                $(this).parent("li").toggleClass('show');
                $(this).parents('li.nav-item.dropdown.show' ).on( 'hidden.bs.dropdown', function (e) {
                  $('.dropdown-menu .show').removeClass("show");
                });
                if ( !$parent.parent().hasClass('navbar-nav')) {
                  $el.next().css( { "top": $el[0].offsetTop, "left": -$subMenu.outerWidth() } );
                }
                return false;
              });
            });
            //]]>
            </script>
            <xsl:if test="extraJS = 'create-slide'">
                <script>
                    <![CDATA[
                    var current_quote = 1, current_image = 1;
                    var quote_search = '', quote_tags = [], quote_attrs = [], quote_unused = 0;
                    var image_search = '', image_tags = [];
                    var list_view = true;
                    var $quote_container = $('#ajax-quotes');
                    var $image_container = $('#ajax-images');
                    var $content_categories = $('#content-categories');
                    function getQuote(page) {
                        $.getJSON('https://api.environmentaldashboard.org/cv/quotes', { per_page: 10, page: page, search: quote_search, tags: quote_tags, attributions: quote_attrs, unused: quote_unused }, function(data) {
                            if (list_view) {
                                var html = '<div class="card"><div class="card-header">Quotes <span class="float-right">Select view: <svg id="list-view" width="20" height="20" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg" style="position:relative;top:5px;fill:#21a7df"><path d="M832 1024v384q0 52-38 90t-90 38h-512q-52 0-90-38t-38-90v-384q0-52 38-90t90-38h512q52 0 90 38t38 90zm0-768v384q0 52-38 90t-90 38h-512q-52 0-90-38t-38-90v-384q0-52 38-90t90-38h512q52 0 90 38t38 90zm896 768v384q0 52-38 90t-90 38h-512q-52 0-90-38t-38-90v-384q0-52 38-90t90-38h512q52 0 90 38t38 90zm0-768v384q0 52-38 90t-90 38h-512q-52 0-90-38t-38-90v-384q0-52 38-90t90-38h512q52 0 90 38t38 90z"/></svg> <svg id="gallery-view" width="20" height="20" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg" style="position:relative;top:5px;left:5px;fill:#333"><path d="M256 1312v192q0 13-9.5 22.5t-22.5 9.5h-192q-13 0-22.5-9.5t-9.5-22.5v-192q0-13 9.5-22.5t22.5-9.5h192q13 0 22.5 9.5t9.5 22.5zm0-384v192q0 13-9.5 22.5t-22.5 9.5h-192q-13 0-22.5-9.5t-9.5-22.5v-192q0-13 9.5-22.5t22.5-9.5h192q13 0 22.5 9.5t9.5 22.5zm0-384v192q0 13-9.5 22.5t-22.5 9.5h-192q-13 0-22.5-9.5t-9.5-22.5v-192q0-13 9.5-22.5t22.5-9.5h192q13 0 22.5 9.5t9.5 22.5zm1536 768v192q0 13-9.5 22.5t-22.5 9.5h-1344q-13 0-22.5-9.5t-9.5-22.5v-192q0-13 9.5-22.5t22.5-9.5h1344q13 0 22.5 9.5t9.5 22.5zm-1536-1152v192q0 13-9.5 22.5t-22.5 9.5h-192q-13 0-22.5-9.5t-9.5-22.5v-192q0-13 9.5-22.5t22.5-9.5h192q13 0 22.5 9.5t9.5 22.5zm1536 768v192q0 13-9.5 22.5t-22.5 9.5h-1344q-13 0-22.5-9.5t-9.5-22.5v-192q0-13 9.5-22.5t22.5-9.5h1344q13 0 22.5 9.5t9.5 22.5zm0-384v192q0 13-9.5 22.5t-22.5 9.5h-1344q-13 0-22.5-9.5t-9.5-22.5v-192q0-13 9.5-22.5t22.5-9.5h1344q13 0 22.5 9.5t9.5 22.5zm0-384v192q0 13-9.5 22.5t-22.5 9.5h-1344q-13 0-22.5-9.5t-9.5-22.5v-192q0-13 9.5-22.5t22.5-9.5h1344q13 0 22.5 9.5t9.5 22.5z"/></svg></span></div><ul class="list-group list-group-flush">';
                            } else {
                                var html = '<div class="card-columns">';
                            }
                            $.each(data['quoteCollection'], function(index, element) {
                                if (typeof element === 'object') {
                                    if (list_view) {
                                        //html += '<li class="list-group-item ajax-quote" data-id="'+element['quote']['id']+'" data-text="'+element['quote']['text']+'">'+element['quote']['text']+'</li>';
                                        html += '<li class="list-group-item ajax-quote" data-id="'+element['quote']['id']+'" data-text="'+element['quote']['text']+'"><blockquote class="blockquote mb-0"><p>'+element['quote']['text']+'</p><footer class="blockquote-footer">'+element['quote']['attribution']+'</footer></blockquote></li>';
                                    } else {
                                        html += '<div class="card p-3 ajax-quote" data-id="'+element['quote']['id']+'" data-text="'+element['quote']['text']+'"><blockquote class="blockquote mb-0 card-body"><p>' + element['quote']['text'] + '</p><footer class="blockquote-footer"><small class="text-muted">' + element['quote']['attribution'] + '</small></footer></blockquote></div>';
                                    }
                                }
                            });
                            html += (list_view) ? '</ul></div>' : '</div>';
                            $quote_container.find('.selectables').append(html);
                        });
                    }
                    getQuote(1);
                    function getImage(page) {
                        $.getJSON('https://api.environmentaldashboard.org/cv/images', { per_page: 8, page: page, search: image_search, tags: image_tags }, function(data) {
                            var html = '<div class="card-columns">';
                            $.each(data['imageCollection'], function(index, element) {
                                if (typeof element === 'object') {
                                    html += '<div class="card bg-dark text-white ajax-image" data-id="'+element['image']['id']+'"><img class="card-img" src="https://api.environmentaldashboard.org/cv/uploads/'+element['image']['id']+'" alt="Card image" /><div class="card-img-overlay"><h5 class="card-title">' + element['image']['title'] + '</h5><p class="card-text">' + element['image']['description'] + '</p><p class="card-text">' + element['image']['dateCreated'] + '</p></div></div>';
                                }
                            });
                            html += '</div>';
                            $image_container.find('.selectables').append(html);
                        });
                    }
                    getImage(1);
                    $(document).on('click', '.ajax-quote', function(e) {
                        $('#preview-text').remove();
                        var s = $(this).data('text');
                        var text = formatText(s);
                        $('#render').append(text);
                        $("input[name='quote_id']").val($(this).data('id'));
                    });
                    $(document).on('click', '.ajax-image', function(e) {
                        $('#preview-image').remove();
                        var image = makeSVG('image', {id: 'preview-image', x: 10, y: 10, width: '35%', 'xlink:href': 'https://api.environmentaldashboard.org/cv/uploads/'+$(this).data('id')});
                        $('#render').prepend(image);
                        $("input[name='image_id']").val($(this).data('id'));
                    });
                    $('#content-categories img').on('click', function() {
                        $('#preview-cc').remove();
                        var image = makeSVG('image', {id: 'preview-cc', x: 0, y: 5, width: '100%', 'xlink:href': $(this).attr('src')});
                        //document.getElementById('render').appendChild(image);
                        $('#render').append(image);
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
                    $('#list-view').on('click', function(e) {
                        e.preventDefault();
                        list_view = true;
                        $quote_container.find('.selectables').empty();
                        getQuote(current_quote);
                        $(this).css('fill', '#21a7df');
                        $('#gallery-view').css('fill', '#333');
                    });
                    $('#gallery-view').on('click', function(e) {
                        e.preventDefault();
                        list_view = false;
                        $quote_container.find('.selectables').empty();
                        getQuote(current_quote);
                        $(this).css('fill', '#21a7df');
                        $('#list-view').css('fill', '#333');
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
                        quote_unused = ($('#quote-unused').is(':checked')) ? 1 : 0;
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
                        image_unused = ($('#image-unused').is(':checked')) ? 1 : 0;
                        $image_container.find('.selectables').empty();
                        getImage(current_image);
                    });
                    function makeSVG(tag, attrs) { // https://stackoverflow.com/a/3642265/2624391
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
                    function formatText (s) {
                        var text = makeSVG('text', {id: 'preview-text', x: '50%', y: (25 + ( (10/s.length) * 75 ))+'%', fill: '#fff', 'font-size': '4px', 'font-family': 'Biko, Multicolore, Helvetica, sans-serif'});
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
                    var css = `@font-face {
                              font-family: 'Biko';
                              src: url('https://environmentaldashboard.org/fonts/biko/Biko_Regular.otf');
                                }
                              text {font-family: 'Biko';}`,
                        head = document.head || document.getElementsByTagName('head')[0],
                        style = document.createElement('style');

                    style.type = 'text/css';
                    if (style.styleSheet){
                      // This is required for IE8 and below.
                      style.styleSheet.cssText = css;
                    } else {
                      style.appendChild(document.createTextNode(css));
                    }
                    head.appendChild(style);
                    ]]>
                </script>
            </xsl:if>
            <xsl:if test="extraJS = 'landing'">
                <script>
                    <![CDATA[
                    var items = $('.carousel-item');
                    var cc_map = {
                        1: 'https://environmentaldashboard.org/cv_slides/categorybars/serving-our-community.png',
                        2: 'https://environmentaldashboard.org/cv_slides/categorybars/our-downtown.png',
                        3: 'https://environmentaldashboard.org/cv_slides/categorybars/next-generation.png',
                        4: 'https://environmentaldashboard.org/cv_slides/categorybars/heritage.png',
                        5: 'https://environmentaldashboard.org/cv_slides/categorybars/nature_photos.png',
                        6: 'https://environmentaldashboard.org/cv_slides/categorybars/neighbors.png'
                    };

                    $('.selector-img').on('click', function() {
                        var cc = $(this).data('cc');
                        if (cc === 'rand') {
                            cc = getRandomInt(1, 6);
                        }
                        
                        $.getJSON('https://api.environmentaldashboard.org/cv/slides', { content_category: [cc], per_page: 5 }, function(data) {
                            $.each(data['slideCollection'], function(index, element) {
                                if (typeof element === 'object') {
                                    var main_img = $(items[index]).find('image')[0];
                                    main_img.setAttribute('xlink:href', 'https://environmentaldashboard.org/cv/uploads/'+element['slide']['image']['image']['id']);
                                    main_img.setAttribute('y', 10);
                                    $(items[index]).find('image')[1].setAttribute('xlink:href', cc_map[cc]);
                                    
                                    var text_node = $(items[index]).find('text')[0];
                                    text_node.parentNode.removeChild(text_node);

                                    var text_parent = $(items[index]).find('#render');

                                    var s = element['slide']['quote']['quote']['text'];
                                    var tmp = $('#tmp');
                                    s = tmp.html(s).text();
                                    tmp.html('');
                                    var text = formatText(s);
                                    text_parent.append(text);
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
                    function formatText (s) {
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
                    ]]>
                </script>
            </xsl:if>
        </body>
    </html>

    </xsl:template>

</xsl:stylesheet>

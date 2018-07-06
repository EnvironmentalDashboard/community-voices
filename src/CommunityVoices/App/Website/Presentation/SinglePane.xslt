<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:import href="Component.xslt" />

    <xsl:output method="html" doctype-system="about:legacy-compat"/>

    <xsl:template match="/domain">

    <html>
        <head>
            <title> <xsl:value-of select="title" /> </title>

            <link rel="stylesheet" href="https://environmentaldashboard.org/css/bootstrap.css"/>
            <base href="{baseUrl}"/>
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
                  $(this).parents('.dropdown-menu').first().find( '.show').removeClass("show");
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
                    var $quote_container = $('#ajax-quotes');
                    var $image_container = $('#ajax-images');
                    var $content_categories = $('#content-categories');
                    $.getJSON('http://api.environmentaldashboard.org/community-voices/quotes', { }, function(data) {
                        $.each(data['quoteCollection'], function(index, element) {
                            var html = '<div class="card p-3 ajax-quote" data-id="'+element['quote']['id']+'" data-text="'+element['quote']['text']+'"><blockquote class="blockquote mb-0 card-body"><p>' + element['quote']['text'] + '</p><footer class="blockquote-footer"><small class="text-muted">' + element['quote']['attribution'] + '</small></footer></blockquote></div>';
                            $quote_container.append(html);
                        });
                    });
                    $.getJSON('http://api.environmentaldashboard.org/community-voices/images', { }, function(data) {
                        $.each(data['imageCollection'], function(index, element) {
                            //console.log(element['image']['id']);
                            var html = '<div class="card bg-dark text-white ajax-image" data-id="'+element['image']['id']+'"><img class="card-img" src="/community-voices/uploads/'+element['image']['id']+'" alt="Card image" /><div class="card-img-overlay"><h5 class="card-title">' + element['image']['title'] + '</h5><p class="card-text">' + element['image']['description'] + '</p><p class="card-text">' + element['image']['dateCreated'] + '</p></div></div>';
                            $image_container.append(html);
                        });
                    });
                    $(document).on('click', '.ajax-quote', function(e) {
                        var text = makeSVG('text', {x: '50%', y: '45%', fill: '#fff', 'font-size': '4px'});
                        var s = $(this).data('text');
                        var arr = [];
                        arr[0] = '';
                        var tspans = 0, counter = 0;
                        for (var i = 0; i < s.length; i++) {
                            var char = s.charAt(i);
                            arr[tspans] += char;
                            if (counter++ > 10 && char === ' ') {
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
                        $('#render').append(text);
                        $("input[name='quote_id']").val($(this).data('id'));
                    });
                    $(document).on('click', '.ajax-image', function(e) {
                        var image = makeSVG('image', {x: 10, y: 10, width: '35%', 'xlink:href': '/community-voices/uploads/'+$(this).data('id')});
                        $('#render').prepend(image);
                        $("input[name='image_id']").val($(this).data('id'));
                    });
                    $('#content-categories img').on('click', function() {
                        var image = makeSVG('image', {x: 0, y: 5, width: '100%', 'xlink:href': $(this).attr('src')});
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
                    });
                    $('#img-btn').on('click', function(e) {
                        e.preventDefault();
                        $quote_container.css('display', 'none');
                        $image_container.css('display', '');
                        $content_categories.css('display', 'none');
                        $(this).addClass('active');
                        $prev_btn.removeClass('active');
                        $prev_btn = $(this);
                    });
                    $('#cc-btn').on('click', function(e) {
                        e.preventDefault();
                        $quote_container.css('display', 'none');
                        $image_container.css('display', 'none');
                        $content_categories.css('display', '');
                        $(this).addClass('active');
                        $prev_btn.removeClass('active');
                        $prev_btn = $(this);
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
                    ]]>
                </script>
            </xsl:if>
            <xsl:if test="extraJS = 'images'">
            </xsl:if>
        </body>
    </html>

    </xsl:template>

</xsl:stylesheet>

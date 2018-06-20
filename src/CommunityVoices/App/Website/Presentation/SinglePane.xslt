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

                <div class="row" style="padding:15px;">
                    <div class="col-12">
                        <xsl:value-of select="main-pane" disable-output-escaping="yes" />
                    </div>
                </div>

                <xsl:call-template name="common-footer" />
            </div>
            <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
            <script>
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
            </script>
            <xsl:if test="extraJS = 'create-slide'">
                <script>
                    //<![CDATA[
                    $.getJSON('http://api.example.com:8080/community-voices/quotes', { }, function(data) {
                        $.each(data['quoteCollection'], function(index, element) {
                            //console.log(element['quote']);
                            var html = '<div class="card p-3"><blockquote class="blockquote mb-0 card-body"><p>' + element['quote']['text'] + '</p><footer class="blockquote-footer"><small class="text-muted">' + element['quote']['attribution'] + '</small></footer></blockquote></div>';
                            $('#ajax-quotes').append(html);
                        });
                    });
                    $.getJSON('http://api.example.com:8080/community-voices/images', { }, function(data) {
                        $.each(data['imageCollection'], function(index, element) {
                            //console.log(element['image']['id']);
                            var html = '<div class="card bg-dark text-white"><img class="card-img" src="/community-voices/uploads/'+element['image']['id']+'" alt="Card image" /><div class="card-img-overlay"><h5 class="card-title">' + element['image']['title'] + '</h5><p class="card-text">' + element['image']['description'] + '</p><p class="card-text">' + element['image']['dateCreated'] + '</p></div></div>';
                            $('#ajax-images').append(html);
                        });
                    });
                    //]]>
                </script>
            </xsl:if>
        </body>
    </html>

    </xsl:template>

</xsl:stylesheet>

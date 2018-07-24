<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:import href="Component.xslt" />

    <xsl:output method="html" doctype-system="about:legacy-compat"/>

    <xsl:template match="/domain">

    <html>
        <head>
            <meta charset="utf-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
            <!-- TODO: meta description -->
            <!-- <meta name="description" content="Environmental Dashboard. A technology &amp; approach for organizations and whole communities that combines feedback, through real-time public displays of resource use and environmental conditions, with thoughts and actions of community to engage, motivate, empower &amp; celebrate sustainable thought and action." /> -->
            <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png?v=9ByOqqx0o3" />
            <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png?v=9ByOqqx0o3" />
            <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png?v=9ByOqqx0o3" />
            <link rel="manifest" href="/manifest.json?v=9ByOqqx0o3" />
            <link rel="mask-icon" href="/safari-pinned-tab.svg?v=9ByOqqx0o3" color="#00a300" />
            <link rel="shortcut icon" href="/favicon.ico?v=9ByOqqx0o3" />
            <meta name="theme-color" content="#000000" />
            <title> <xsl:value-of select="title" /> </title>

            <link rel="stylesheet" href="https://environmentaldashboard.org/css/bootstrap.css?v=2"/>
            <xsl:if test="comfortaa != ''">
                <link href="https://fonts.googleapis.com/css?family=Comfortaa" rel="stylesheet" />
            </xsl:if>
            <style>
            .form-group ::-webkit-scrollbar {
                height: 16px;
                overflow: visible;
                width: 16px;
            }
            .form-group ::-webkit-scrollbar-button {
                height: 0;
                width: 0;
            }
            .form-group ::-webkit-scrollbar-corner {
                background: transparent;
            }
            .form-group ::-webkit-scrollbar-thumb {
                background-color: rgba(0,0,0,.2);
                background-clip: padding-box;
                border: solid transparent;
                border-width: 1px 1px 1px 6px;
                min-height: 28px;
                padding: 100px 0 0;
                -webkit-box-shadow: inset 1px 1px 0 rgba(0,0,0,.1), inset 0 -1px 0 rgba(0,0,0,.07);
                box-shadow: inset 1px 1px 0 rgba(0,0,0,.1), inset 0 -1px 0 rgba(0,0,0,.07);
            }
            .form-group ::-webkit-scrollbar-track {
                background-clip: padding-box;
                border: solid transparent;
                border-width: 0 0 0 4px;
            }
            </style>
        </head>
        <body>
            <div class="container">
                <xsl:call-template name="common-header" />

                <xsl:value-of select="main-pane" disable-output-escaping="yes" />

                <xsl:call-template name="common-footer" />
            </div>
            <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
            <script>
            <![CDATA[
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
            ]]>
            </script>
            <xsl:if test="extraJS != ''">
                <script src="https://environmentaldashboard.org/cv/public/{extraJS}.js"></script>
            </xsl:if>
        </body>
    </html>

    </xsl:template>

</xsl:stylesheet>

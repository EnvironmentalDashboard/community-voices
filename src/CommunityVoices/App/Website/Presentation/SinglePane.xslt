<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:import href="Component.xslt" />

    <xsl:output method="html" doctype-system="about:legacy-compat"/>

    <xsl:template match="/domain">

    <html>
        <head>
            <title> <xsl:value-of select="title" /> </title>

            <style>

                /* default styling */

                body{
                    font-size: 15px;
                    font-family: "Lucida Grande", "Helvetica Nueue", Arial, sans-serif;
                }
                header{
                    text-align: center;
                    margin-bottom: 50px;
                }
                footer{
                    text-align: right;
                    background-color: #333;
                    border: 1px solid #333;
                    color: #fff;
                    margin-top: 50px;
                }
                img{
                    width:800px;
                    height:600px;
                }

                /* Navigation Bar */

                nav {
                    background-color: #333;
                    border: 1px solid #333;
                    color: #fff;
                }
                nav ul li{
                    display: inline-block;
                }
                nav > ul > li > a {
                    color: #aaa;
                    line-height: 2em;
                    padding: 0.5em 2em;
                    text-decoration: none;
                }

                /* Submenus */

                nav li > ul {
                    display: none;
                }
                nav li > ul li a {
                    color: #aaa;
                    line-height: 2em;
                    padding: 0.5em 2em;
                    text-decoration: none;
                }
                nav li:hover > ul {
                    position: absolute;
                    background-color: #333;
                    border: 5px solid #444;
                    display: block;
                }

            </style>
            <base href="{baseUrl}"/>
        </head>
        <body>
            <xsl:call-template name="common-header" />

            <div class="main-pane">
                <xsl:value-of select="main-pane" disable-output-escaping="yes" />
            </div>

            <xsl:call-template name="common-footer" />
        </body>
    </html>

    </xsl:template>

</xsl:stylesheet>

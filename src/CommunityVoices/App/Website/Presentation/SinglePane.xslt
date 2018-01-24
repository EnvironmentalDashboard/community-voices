<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:import href="Component.xslt" />

    <xsl:template match="/domain">

    <html>
        <head>
            <title>Select Choices</title>

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

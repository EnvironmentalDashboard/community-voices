<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <!-- <xsl:import href="Component.xslt" /> -->

    <xsl:output method="xml" doctype-system="about:legacy-compat" omit-xml-declaration="yes"/>

    <xsl:template match="/domain">

    <!-- <html>
        <head>
            <title> <xsl:value-of select="title" /> </title>

            <link href="https://fonts.googleapis.com/css?family=Comfortaa" rel="stylesheet" />
        </head>
        <body>
            <xsl:value-of select="main-pane" disable-output-escaping="yes" />
            
        </body>
    </html> -->
    <xsl:value-of select="main-pane" disable-output-escaping="yes" />

    </xsl:template>

</xsl:stylesheet>

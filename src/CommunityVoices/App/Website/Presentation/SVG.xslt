<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:output method="xml" doctype-system="about:legacy-compat" omit-xml-declaration="yes"/>

    <xsl:template match="/domain">

        <xsl:value-of select="main-pane" disable-output-escaping="yes" />

    </xsl:template>

</xsl:stylesheet>

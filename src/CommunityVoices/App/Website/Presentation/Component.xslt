<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:template name="common-header">
        <header>
            <xsl:choose>
                <xsl:when test="identity/user/id &gt; 0">
                    Welcome, <xsl:value-of select="identity/user/id" />
                </xsl:when>
                <xsl:otherwise>
                    You need to <a href="login">login.</a>
                </xsl:otherwise>
            </xsl:choose>
        </header>
    </xsl:template>

    <xsl:template name="common-footer">
        <footer>
            Some rights reserved
        </footer>
    </xsl:template>

</xsl:stylesheet>

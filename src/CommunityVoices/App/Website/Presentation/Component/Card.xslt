<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0"
    xmlns:exslt="http://exslt.org/common">

    <xsl:template name="card">
        <xsl:param name = "title"/>
        <xsl:param name = "message"/> <!-- should be passed in as set of <item> tags, which allows splitting bullets -->

        <div class="card" style="margin-bottom: 16px; max-width:400px;margin: 0 auto">
            <div class="card-body">
                <h1 class="h4 mb-4 font-weight-normal" style="margin-bottom: 0.5rem !important;">
                    <xsl:value-of select="$title" />
                </h1>
                    <ul style="margin-bottom: 0.5rem;">
                        <xsl:for-each select="exslt:node-set($message)//item">
                            <li style="margin-bottom: 0px;"><xsl:value-of select="."/></li>
                        </xsl:for-each>
                    </ul>
            </div>
        </div>
    </xsl:template>
</xsl:stylesheet>

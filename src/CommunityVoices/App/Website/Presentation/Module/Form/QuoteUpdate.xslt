<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:output method="xml" indent="yes" omit-xml-declaration="yes" />

    <xsl:template match="/form">
        <xsl:if test="@failure">
            <p>Attribution missing.</p>
        </xsl:if>

        <form method='post'>
            <xsl:attribute name="action">./quotes/<xsl:value-of select="domain/quote/id"/>/edit/authenticate</xsl:attribute>

            Quote:
            <input type='text' name='text'>
                <xsl:attribute name="value"><xsl:value-of select="domain/quote/text"/></xsl:attribute>
            </input><br />

            Attribution:
            <xsl:choose>
                <xsl:when test='@text'>
                    <input type='text' name='attribution' value='{@text}' />
                </xsl:when>
                <xsl:otherwise>
                    <input type='text' name='attribution'>
                        <xsl:attribute name="value"><xsl:value-of select="domain/quote/attribution"/></xsl:attribute>
                    </input>
                </xsl:otherwise>
            </xsl:choose><br />

            Sub-Attribution:
            <input type='text' name='subAttribution'>
                <xsl:attribute name="value"><xsl:value-of select="domain/quote/subAttribution"/></xsl:attribute>
            </input><br />
            Date Recorded:
            <input type='text' name='dateRecorded'>
                <xsl:attribute name="value"><xsl:value-of select="domain/quote/dateRecorded"/></xsl:attribute>
            </input><br />

            Status: @TODO
            <input type='' name='status' /><br />

            <input type='hidden' name='id'>
                <xsl:attribute name="value"><xsl:value-of select="domain/quote/id"/></xsl:attribute>
            </input>

            <input type='submit'/>
        </form>
    </xsl:template>

</xsl:stylesheet>

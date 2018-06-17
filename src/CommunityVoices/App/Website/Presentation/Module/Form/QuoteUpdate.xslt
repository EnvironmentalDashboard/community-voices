<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:output method="xml" indent="yes" omit-xml-declaration="yes" />

    <xsl:template match="/form">
        <xsl:if test="@failure">
            <p>Attribution missing.</p>
        </xsl:if>

        <form method='post' style="max-width:400px;margin: 0 auto">
            <xsl:attribute name="action">./quotes/<xsl:value-of select="domain/quote/id"/>/edit/authenticate</xsl:attribute>

            <div class="form-group">
                <label for="text">Quote</label>
                <input type='text' name='text' id='text' class='form-control'>
                    <xsl:attribute name="value"><xsl:value-of select="domain/quote/text"/></xsl:attribute>
                </input>
            </div>

            <div class="form-group">
                <label for="attribution">Attribution</label>
                <xsl:choose>
                    <xsl:when test='@text'>
                        <input type='text' name='attribution' id='attribution' value='{@text}' class='form-control' />
                    </xsl:when>
                    <xsl:otherwise>
                        <input type='text' name='attribution' id='attribution' class='form-control'>
                            <xsl:attribute name="value"><xsl:value-of select="domain/quote/attribution"/></xsl:attribute>
                        </input>
                    </xsl:otherwise>
                </xsl:choose>
            </div>

            <div class="form-group">
                <label for="subAttribution">Sub-Attribution</label>
                <input type='text' name='subAttribution' id='subAttribution' class='form-control'>
                    <xsl:attribute name="value"><xsl:value-of select="domain/quote/subAttribution"/></xsl:attribute>
                </input>
            </div>

            <div class="form-group">
                <label for="dateRecorded">Date Recorded</label>
                <input type='text' name='dateRecorded' id='dateRecorded' class='form-control'>
                    <xsl:attribute name="value"><xsl:value-of select="domain/quote/dateRecorded"/></xsl:attribute>
                </input>
            </div>

            <div class="form-group">
                <p>Status: @TODO</p>
            </div>
            <!-- <input type='' name='status' /><br /> -->

            <input type='hidden' name='id'>
                <xsl:attribute name="value"><xsl:value-of select="domain/quote/id"/></xsl:attribute>
            </input>

            <input type='submit' class='btn btn-primary' />
        </form>
    </xsl:template>

</xsl:stylesheet>

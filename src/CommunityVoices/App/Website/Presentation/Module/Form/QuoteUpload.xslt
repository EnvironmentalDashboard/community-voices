<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:output method="xml" indent="yes" omit-xml-declaration="yes" />

    <xsl:template match="/form">
        <xsl:if test="@failure">
            <p>Attribution missing.</p>
        </xsl:if>

        <form action='./quotes/new/authenticate' method='post'>

            Quote: <input type='text' name='text' /><br />

            Attribution:
            <xsl:choose>
                <xsl:when test='@text'>
                    <input type='text' name='attribution' value='{@text}'/>
                </xsl:when>
                <xsl:otherwise>
                    <input type='text' name='attribution' />
                </xsl:otherwise>
            </xsl:choose><br />

            Sub-Attribution: <input type='text' name='subAttribution' /><br />
            Date Recorded: <input type='text' name='dateRecorded' /><br />

            Approve:
            <xsl:choose>
              <xsl:when test="@approve-value &gt; 0">
                  <input type='checkbox' name='approved' checked='{@approve-value}'/>
              </xsl:when>
              <xsl:otherwise>
                  <input type='checkbox' name='approved' />
              </xsl:otherwise>
            </xsl:choose>
            <br/>

            <input type='submit'/>
        </form>
    </xsl:template>

</xsl:stylesheet>

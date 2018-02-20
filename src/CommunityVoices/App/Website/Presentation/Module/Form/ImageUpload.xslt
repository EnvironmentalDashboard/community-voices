<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:output method="xml" indent="yes" omit-xml-declaration="yes" />

    <xsl:template match="/form">

        <form action='./images/new/authenticate' method='post'>

            File: <input type='file' name='filename' accept=''.jpg, .jpeg, .png' /><br />

            Title: <input type='text' name='title' /><br />
            Description: <input type='text' name='description' /><br />

            Date Taken: <input type='text' name='dateTaken' /><br />
            Photographer: <input type='text' name='photographer' /><br />
            Organization: <input type='text' name='organization' /><br />

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

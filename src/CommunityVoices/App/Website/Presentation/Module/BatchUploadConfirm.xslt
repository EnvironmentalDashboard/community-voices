<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:import href="../Component/Card.xslt" />
    <xsl:import href="../Component/Navbar.xslt" />
    <xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

<xsl:template name="sourceInfo">
    <xsl:param name="sheetData"/>
    <div class="container">

    </div>
</xsl:template>

<xsl:template name="quoteInfo">
    <xsl:param name="individualQuote"/>
</xsl:template>


<xsl:template match="/package">
    <xsl:variable name="dataFromCSV" select="domain/csvResults"/>
        <xsl:choose>
            <xsl:when test="$dataFromCSV/errors != ''"> <!-- if any serious errors are detected upload will be prevented. -->
                <xsl:call-template name="card">
                    <xsl:with-param name="title"> The following errors are unfixable on website. Please fix them and then reupload both documents. </xsl:with-param>
                    <xsl:with-param name="message" select="$dataFromCSV/errors"/>
                </xsl:call-template>
            </xsl:when>

            <xsl:otherwise>
                <xsl:if test="$dataFromCSV/warnings/expected !=''">
                    <xsl:call-template name="card">
                        <xsl:with-param name="title">Warning: Some columns that were expected could not be found. </xsl:with-param>
                        <xsl:with-param name="message" select="$dataFromCSV/warnings/expected"/>
                    </xsl:call-template>
                </xsl:if>
                <xsl:if test="$dataFromCSV/warnings/unrecognized !=''">
                    <xsl:call-template name="card">
                        <xsl:with-param name="title">Warning: The following columns were unrecognized and any data associated with them will not be uploaded. </xsl:with-param>
                        <xsl:with-param name="message" select="$dataFromCSV/warnings/unrecognized"/>
                    </xsl:call-template>
                </xsl:if>
                <xsl:if test="$dataFromCSV/unpairedQuotes != ''">
                    <xsl:variable name="toggleMessage">
                        <item>Click here to toggle unpaired quotes</item>
                    </xsl:variable>
                    <div id="allowToggling">
                        <xsl:call-template name="card">
                            <xsl:with-param name="title">Warning: Unpaired quotes without valid identifiers were found </xsl:with-param>
                            <xsl:with-param name="message" select="$toggleMessage"/>
                        </xsl:call-template>
                    </div>
                    <div class="collapse" id="collapseExample">
                        <div class="card card-body">
                            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident.
                        </div>
                    </div>
                </xsl:if>
            </xsl:otherwise>
        </xsl:choose>
</xsl:template>
</xsl:stylesheet>

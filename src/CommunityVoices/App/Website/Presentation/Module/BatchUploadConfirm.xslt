<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:import href="../Component/Card.xslt" />
    <xsl:import href="../Component/Navbar.xslt" />
    <xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

<xsl:template name="sources">
    <xsl:param name="entries"/>
        <xsl:for-each select="$entries/*"> <!-- selects each identifier, which are all different tags so require * -->
            <div class="card m-3">
                <xsl:attribute name="id"><xsl:value-of select="name(.)"/></xsl:attribute> <!-- allows us to pair unpaired quotes with this id -->
                <h6><xsl:value-of select="name(.)"/></h6>
                <xsl:for-each select="rowData/column">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label"><xsl:value-of select="./originalName"/></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control">
                                <xsl:attribute name="value"><xsl:value-of select="./columnData"/></xsl:attribute>
                            </input>
                        </div>
                    </div>
                </xsl:for-each>
                <div class="card ml-5 pairedQuotes">
                    <xsl:for-each select="quotes">
                        <xsl:call-template name="quotes">
                            <xsl:with-param name="sourceInfo" select="."/>
                            <xsl:with-param name="validIdentifiers"/>
                        </xsl:call-template>
                    </xsl:for-each>
                </div>
            </div>
        </xsl:for-each>
</xsl:template>

<xsl:template name="quotes">
    <xsl:param name="sourceInfo"/>
    <xsl:param name="validIdentifiers"/>
        <xsl:for-each select="$sourceInfo/item/rowData">
            <div class="card">
                <xsl:attribute name="uid"><xsl:value-of select="generate-id(.)"/></xsl:attribute>
                <form class="quoteForm">
                    <xsl:if test="$validIdentifiers">
                        <div class="form-group row identifiersFormElm">
                            <label class="col-sm-4 col-form-label">Choose identifier to pair with</label>
                            <div class="col-sm-8">
                                <select class="validIdentifiers">
                                    <option label=" "></option>
                                    <xsl:for-each select="$validIdentifiers/item">
                                        <option value="yes">
                                            <xsl:attribute name="value"><xsl:value-of select="."/></xsl:attribute>
                                            <xsl:value-of select="."/>
                                        </option>
                                    </xsl:for-each>
                                </select>
                            </div>
                        </div>
                    </xsl:if>
                    <xsl:for-each select="column">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><xsl:value-of select="./originalName"/></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control">
                                    <xsl:attribute name="value"><xsl:value-of select="./columnData"/></xsl:attribute>
                                </input>
                            </div>
                        </div>
                    </xsl:for-each>
                    <xsl:if test="$validIdentifiers">
                        <div class="form-group row pairButton">
                            <div class="col-sm">
                                <button type="submit" class="btn btn-primary">Pair with Selected identifier</button>
                            </div>
                        </div>
                    </xsl:if>
                </form>
            </div>
        </xsl:for-each>
</xsl:template>


<xsl:template match="/package">
    <xsl:variable name="dataFromCSV" select="domain/csvResults"/>
        <xsl:choose>
            <xsl:when test="$dataFromCSV/errors != ''"> <!-- if any serious errors are detected upload will be prevented. -->
                <xsl:call-template name="card">
                    <xsl:with-param name="title"> The following errors are unfixable on website. Please fix them and then reupload both documents. </xsl:with-param>
                    <xsl:with-param name="message" select="$dataFromCSV/errors"/>
                </xsl:call-template>
                <div class="row">
                    <div class="col text-center">
                        <input type="button" form="batchUploadForm" class="btn btn-primary" value="Reupload" id="fileUploadButton"></input>
                    </div>
                </div>
                <form action='/community-voices/quotes/confirm' method='post' enctype='multipart/form-data' id="batchUploadForm"> </form>
                    <input class="custom-file-input" form="batchUploadForm" id="file" type='file' name='file[]' multiple="" accept='.xlsx, .xls, .csv' style="display: none;"/>
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
                        <item>These quotes will not be uploaded unless you specify a identifier below</item>
                        <item>Click here to toggle unpaired quotes</item>
                    </xsl:variable>
                    <div id="allowToggling">
                        <xsl:call-template name="card">
                            <xsl:with-param name="title">Warning: Unpaired quotes without valid identifiers were found </xsl:with-param>
                            <xsl:with-param name="message" select="$toggleMessage"/>
                        </xsl:call-template>
                    </div>
                    <div id="unpairedQuotes" class="collapse">
                        <div class="card m-3">
                            <xsl:call-template name="quotes">
                                <xsl:with-param name="sourceInfo" select="$dataFromCSV/unpairedQuotes"/>
                                <xsl:with-param name="validIdentifiers" select="$dataFromCSV/validIdentifiers/allIdentifiers"/>
                            </xsl:call-template>
                        </div>
                    </div>
                    <xsl:call-template name="sources">
                        <xsl:with-param name="entries" select="$dataFromCSV/entries"/>
                    </xsl:call-template>
                </xsl:if>
            </xsl:otherwise>
        </xsl:choose>
</xsl:template>
</xsl:stylesheet>

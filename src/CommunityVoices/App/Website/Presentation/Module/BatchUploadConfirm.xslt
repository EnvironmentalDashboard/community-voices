<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:import href="../Component/Card.xslt" />
    <xsl:import href="../Component/Navbar.xslt" />
    <xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

<xsl:template name="sources"> <!-- template for each source -->
    <xsl:param name="entries"/>
    <xsl:param name="contentCategoryCollection"/>
    <xsl:param name="tagCollection"/>
        <xsl:for-each select="$entries/*"> <!-- selects each identifier, which are all different tags so require * -->
            <div class="card m-3 individualSource">
                <xsl:attribute name="id"><xsl:value-of select="substring(name(.), 2)"/></xsl:attribute> <!-- allows us to pair unpaired quotes with this id -->
                <xsl:attribute name="hasIdentifier">true</xsl:attribute>
                <div class="sourceNotQuote">
                    <div class="row">
                        <div class="col">
                            <strong><xsl:value-of select="substring(name(.), 2)"/></strong>
                        </div>
                        <div class="col">
                            <div class="float-right">
                                <a class="btn btn-light deleteEntry sourceDelete">
                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5a.5.5 0 0 0-1 0v7a.5.5 0 0 0 1 0v-7z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    <xsl:for-each select="rowData/*">
                        <div class="form-group row">
                            <xsl:attribute name="formattedName"><xsl:value-of select="./formattedName"/></xsl:attribute>
                            <xsl:if test="./error">
                                <xsl:attribute name="message"><xsl:value-of select="./error"/></xsl:attribute>
                                <xsl:attribute name="hasErrors" value="true()"/>
                            </xsl:if>
                            <label class="col-sm-4 col-form-label">
                                <xsl:value-of select="./originalName"/>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control">
                                    <xsl:attribute name="value"><xsl:value-of select="./columnData"/></xsl:attribute>
                                    <xsl:attribute name="name"><xsl:value-of select="./formattedName"/></xsl:attribute>
                                </input>
                            </div>
                        </div>
                    </xsl:for-each>
                </div>
                <div class="card ml-5 pairedQuotes">
                    <xsl:for-each select="quotes">
                        <xsl:call-template name="quotes">
                            <xsl:with-param name="sourceInfo" select="."/>
                            <xsl:with-param name="validIdentifiers"/>
                            <xsl:with-param name="contentCategoryCollection" select="$contentCategoryCollection"/>
                            <xsl:with-param name="tagCollection" select="$tagCollection"/>
                        </xsl:call-template>
                    </xsl:for-each>
                </div>
                <div class="uploadButtonContainer">
                    <!-- https://stackoverflow.com/questions/18189948/jquery-button-click-function-is-not-working See Jquery-->
                </div>
            </div>
        </xsl:for-each>
</xsl:template>

<xsl:template name="quotes"> <!-- template for each quote -->
    <xsl:param name="sourceInfo"/>
    <xsl:param name="validIdentifiers"/>
    <xsl:param name="contentCategoryCollection"/>
    <xsl:param name="tagCollection"/>
        <xsl:for-each select="$sourceInfo/item">
            <div class="card individualQuote">
                <xsl:attribute name="quoteNumber"><xsl:value-of select="quoteNumber"/></xsl:attribute>
                <div class="row">
                    <div class="col">
                        <div class="float-right">
                            <a class="btn btn-light deleteEntry quoteDelete">
                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5a.5.5 0 0 0-1 0v7a.5.5 0 0 0 1 0v-7z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                    <xsl:if test="$validIdentifiers">
                        <div class="form-group row identifiersFormElm">
                            <label class="col-sm-4 col-form-label">Choose identifier to pair with</label>
                            <div class="col-sm-8">
                                <select class="validIdentifiers">
                                    <option label=" "></option>
                                    <xsl:for-each select="$validIdentifiers/item">
                                        <option>
                                            <xsl:attribute name="value"><xsl:value-of select="substring(.,2)"/></xsl:attribute>
                                            <xsl:attribute name="class"><xsl:value-of select="substring(.,2)"/></xsl:attribute>
                                            <xsl:value-of select="substring(.,2)"/>
                                        </option>
                                    </xsl:for-each>
                                </select>
                            </div>
                        </div>
                    </xsl:if>
                    <xsl:for-each select="rowData/*">
                        <div class="form-group row">
                            <xsl:attribute name="formattedName"><xsl:value-of select="./formattedName"/></xsl:attribute>
                            <xsl:choose>
                                <xsl:when test="error">
                                    <xsl:attribute name="message"><xsl:value-of select="./error"/></xsl:attribute>
                                    <xsl:attribute name="hasErrors" value="true()"/>
                                </xsl:when>
                                <xsl:when test="warning">
                                    <xsl:attribute name="message"><xsl:value-of select="./warning"/></xsl:attribute>
                                    <xsl:attribute name="hasWarnings" value="true()"/>
                                </xsl:when>
                            </xsl:choose>
                            <xsl:choose>
                                <xsl:when test="name(.)='contentcategories'">
                                    <ul style="display:none" class="selectedContentCategories">
                                        <xsl:for-each select="all/columnData">
                                            <li><xsl:value-of select="."/></li>
                                        </xsl:for-each>
                                    </ul>
                                    <div class="col-sm-6">
                                    <p class="checkboxHeader">
                                        Potential Content Categories
                                        <span style="font-size: small;">(Check all that apply)</span>
                                    </p>
                                    <div style="overflow-y:scroll;width:100%;height: 145px;" class="contentCategoryCheckboxList">
                                      <xsl:for-each select="$contentCategoryCollection/contentCategory">
                                        <div class="form-check">
                                          <input class="form-check-input" type="checkbox" name="contentCategories[]" id="contentCategory{id}">
                                            <xsl:attribute name="value"><xsl:value-of select='id' /></xsl:attribute>
                                          </input>
                                          <label class="form-check-label">
                                            <xsl:attribute name="for">contentCategory<xsl:value-of select='id' /></xsl:attribute>
                                            <xsl:value-of select="label"></xsl:value-of>
                                          </label>
                                        </div>
                                      </xsl:for-each>
                                    </div>
                                </div>
                                </xsl:when>
                                <xsl:when test="name(.)='tags'">
                                    <ul style="display:none" class="selectedTags">
                                        <xsl:for-each select="all/columnData">
                                            <li><xsl:value-of select="."/></li>
                                        </xsl:for-each>
                                    </ul>
                                    <div class="col-sm-6">
                                    <p class="checkboxHeader">
                                        Tags
                                        <span style="font-size: small;">(Check all that apply)</span>
                                    </p>
                                    <div style="overflow-y:scroll;width:100%;height: 145px;" class="tagCheckboxList">
                                      <xsl:for-each select="$tagCollection/tag">
                                        <div class="form-check">
                                          <input class="form-check-input" type="checkbox" name="tags[]" id="tag{id}">
                                            <xsl:attribute name="value"><xsl:value-of select='id' /></xsl:attribute>
                                          </input>
                                          <label class="form-check-label">
                                            <xsl:attribute name="for">tag<xsl:value-of select='id' /></xsl:attribute>
                                            <xsl:value-of select="label"></xsl:value-of>
                                          </label>
                                        </div>
                                      </xsl:for-each>
                                    </div>
                                  </div>
                                </xsl:when>
                                <xsl:otherwise>
                                    <label class="col-sm-4 col-form-label">
                                        <xsl:value-of select="./originalName"/>
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control">
                                            <!-- need to modify names of form elements to match expected names for a quote upload -->
                                            <xsl:choose>
                                                <xsl:when test="./formattedName = 'originalquote'">
                                                    <xsl:attribute name="name">originalText</xsl:attribute>
                                                </xsl:when>
                                                <xsl:when test="./formattedName = 'editedquotes'">
                                                    <xsl:attribute name="name">text</xsl:attribute>
                                                </xsl:when>
                                                <xsl:otherwise>
                                                    <xsl:attribute name="name"><xsl:value-of select="./formattedName"/></xsl:attribute>
                                                </xsl:otherwise>
                                            </xsl:choose>
                                            <xsl:attribute name="value"><xsl:value-of select="./columnData"/></xsl:attribute>
                                        </input>
                                    </div>
                                </xsl:otherwise>
                            </xsl:choose>
                        </div>
                    </xsl:for-each>
                    <xsl:if test="$validIdentifiers">
                        <div class="form-group row pairButton">
                            <div class="col-sm">
                                <button type="submit" class="btn btn-primary pairWithIdentifier">Pair with Selected identifier</button>
                            </div>
                        </div>
                    </xsl:if>
            </div>
        </xsl:for-each>
</xsl:template>


<xsl:template match="/package">
    <xsl:variable name="dataFromCSV" select="domain/csvResults"/>
    <xsl:variable name="contentCategoryCollection" select="domain/contentCategoryCollection"/>
    <xsl:variable name="tagCollection" select="domain/tagCollection"/>
    <div class="container" style="overflow-anchor: none;">
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
                        <item>Click here to delete all unpaired quotes</item>
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
                                <xsl:with-param name="contentCategoryCollection" select="$contentCategoryCollection"/>
                                <xsl:with-param name="tagCollection" select="$tagCollection"/>
                            </xsl:call-template>
                        </div>
                    </div>
                </xsl:if>
                <div id="entryIssues">
                    <div class="card" style="margin-bottom: 16px; max-width:400px;margin: 0 auto">
                        <div class="card-body">
                            <h1 class="h4 mb-4 font-weight-normal" style="margin-bottom: 0.5rem !important;"> Warning: Some of your entries have errors preventing their upload or warnings</h1>
                                <ul style="margin-bottom: 0.5rem;">
                                </ul>
                        </div>
                    </div>
                </div>
                    <xsl:call-template name="sources">
                        <xsl:with-param name="entries" select="$dataFromCSV/entries"/>
                        <xsl:with-param name="contentCategoryCollection" select="$contentCategoryCollection"/>
                        <xsl:with-param name="tagCollection" select="$tagCollection"/>
                    </xsl:call-template>
                <form id="actualForm" method="post" action="/community-voices/quotes/batchUpload" style="display:none"/>
                <div class="row">
                    <div class="col text-center">
                        <input type='submit' name='submit_exit' value='Submit All' class='btn btn-primary mr-4' id="submitAll" target='_blank'/>
                    </div>
                </div>
            </xsl:otherwise>
        </xsl:choose>
    </div>
</xsl:template>
</xsl:stylesheet>

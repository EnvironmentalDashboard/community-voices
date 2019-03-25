<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:output method="html" indent="yes" omit-xml-declaration="yes" />
    <xsl:variable name="selectedGroups" select="/form/domain/selectedGroups" />

    <xsl:template match="/form">
        <div class="row" style="padding:15px;">
            <div class="col-12">
                <xsl:if test="@failure">
                    <p>Attribution missing.</p>
                </xsl:if>

                <div id="alert"></div>

                <form method='POST' style="max-width:400px;margin: 0 auto" id="form">
                    <xsl:attribute name="action">/community-voices/quotes/<xsl:value-of select="domain/quote/id" />/edit</xsl:attribute>

                    <div class="form-group">
                        <label for="text">Quote</label>
                        <textarea name='text' id='text' class='form-control'>
                            <xsl:value-of select="domain/quote/text"/>
                        </textarea>
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
                        <p class="mb-0">Content Categories</p>
                        <div style="overflow-y:scroll;width:100%;height: 145px;border:none">
                          <xsl:for-each select="domain/contentCategoryCollection/contentCategory">
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="contentCategories[]" id="contentCategory{id}">
                                <xsl:attribute name="value"><xsl:value-of select='id' /></xsl:attribute>
                                <xsl:if test="contains($selectedGroups, concat(',', id, ','))">
                                  <xsl:attribute name="checked">checked</xsl:attribute>
                                </xsl:if>
                              </input>
                              <label class="form-check-label">
                                <xsl:attribute name="for">tag<xsl:value-of select='id' /></xsl:attribute>
                                <xsl:value-of select="label"></xsl:value-of>
                              </label>
                            </div>
                          </xsl:for-each>
                        </div>
                      </div>

                    <div class="form-group">
                        <p class="mb-0">Tags</p>
                        <div style="overflow-y:scroll;width:100%;height: 145px;border:none">
                          <xsl:for-each select="domain/tagCollection/tag">
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="tags[]" id="tag{id}">
                                <xsl:attribute name="value"><xsl:value-of select='id' /></xsl:attribute>
                                <xsl:if test="contains($selectedGroups, concat(',', id, ','))">
                                  <xsl:attribute name="checked">checked</xsl:attribute>
                                </xsl:if>
                              </input>
                              <label class="form-check-label">
                                <xsl:attribute name="for">tag<xsl:value-of select='id' /></xsl:attribute>
                                <xsl:value-of select="label"></xsl:value-of>
                              </label>
                            </div>
                          </xsl:for-each>
                        </div>
                      </div>

                      <div class="form-group">
                        Approve:
                        <xsl:choose>
                          <xsl:when test="@approve-value &gt; 0">
                              <input type='checkbox' name='approved' checked='{@approve-value}'/>
                          </xsl:when>
                          <xsl:otherwise>
                              <input type='checkbox' name='approved' />
                          </xsl:otherwise>
                        </xsl:choose>
                      </div>


                    <input type='submit' class='btn btn-primary' />
                </form>
            </div>
        </div>
    </xsl:template>

</xsl:stylesheet>

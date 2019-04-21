<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:output method="html" indent="yes" omit-xml-declaration="yes" />
    <xsl:variable name="selectedGroups" select="/form/domain/selectedGroups" />

    <xsl:template match="domain/errors/*">
        <li style="margin-bottom: 0px;"><xsl:value-of select="." /></li>
    </xsl:template>

    <xsl:template match="/form">
        <div class="row" style="padding:15px;">
            <div class="col-12">
                <form method='POST' style="max-width:400px;margin: 0 auto" id="form">
                    <xsl:attribute name="action">/community-voices/quotes/<xsl:value-of select="domain/quote/id" />/edit</xsl:attribute>

                    <h1 class="h3 mb-3 font-weight-normal">Update Quote</h1>

                    <xsl:if test="domain/errors != ''">
                        <div class="card" style="margin-bottom: 16px;">
                            <div class="card-body">
                                <h1 class="h4 mb-4 font-weight-normal" style="margin-bottom: 0.5rem !important;">Some errors prevented update</h1>
                                <ul style="margin-bottom: 0.5rem;">
                                    <xsl:apply-templates select="domain/errors/*" />
                                </ul>
                            </div>
                        </div>
                    </xsl:if>

                    <div class="form-group">
                        <label for="text">Quote</label>
                        <textarea name='text' id='text' class='form-control'>
                            <xsl:choose>
                                <xsl:when test="domain/form != ''">
                                    <xsl:value-of select="domain/form/text"/>
                                </xsl:when>
                                <xsl:otherwise>
                                    <xsl:value-of select="domain/quote/text"/>
                                </xsl:otherwise>
                            </xsl:choose>
                        </textarea>
                    </div>

                    <div class="form-group">
                        <label for="attribution">Attribution</label>
                        <input type='text' name='attribution' id='attribution' class='form-control'>
                            <xsl:attribute name="value">
                                <xsl:choose>
                                    <xsl:when test="domain/form != ''">
                                        <xsl:value-of select="domain/form/attribution"/>
                                    </xsl:when>
                                    <xsl:otherwise>
                                        <xsl:value-of select="domain/quote/attribution"/>
                                    </xsl:otherwise>
                                </xsl:choose>
                            </xsl:attribute>
                        </input>
                    </div>

                    <div class="form-group">
                        <label for="subAttribution">Sub-Attribution</label>
                        <input type='text' name='subAttribution' id='subAttribution' class='form-control'>
                            <xsl:attribute name="value">
                                <xsl:choose>
                                    <xsl:when test="domain/form != ''">
                                        <xsl:value-of select="domain/form/subAttribution"/>
                                    </xsl:when>
                                    <xsl:otherwise>
                                        <xsl:value-of select="domain/quote/subAttribution"/>
                                    </xsl:otherwise>
                                </xsl:choose>
                            </xsl:attribute>
                        </input>
                    </div>

                    <div class="form-group">
                        <label for="quotationMarks">Include Quotation Marks</label>
                        <input type='checkbox' name='quotationMarks' id='quotationMarks' class='form-control'>
                            <xsl:if test="domain/quote/quotationMarks != ''">
                                <xsl:attribute name="checked"/>
                            </xsl:if>
                        </input>
                    </div>

                    <div class="form-group">
                        <label for="dateRecorded">Date Recorded</label>
                        <input type='text' name='dateRecorded' id='dateRecorded' class='form-control'>
                            <xsl:attribute name="value">
                                <xsl:choose>
                                    <xsl:when test="domain/form != ''">
                                        <xsl:value-of select="domain/form/dateRecorded"/>
                                    </xsl:when>
                                    <xsl:otherwise>
                                        <xsl:value-of select="domain/quote/dateRecorded"/>
                                    </xsl:otherwise>
                                </xsl:choose>
                            </xsl:attribute>
                        </input>
                    </div>

                    <div class="form-group">
                        <p class="mb-0">
                            Potential Content Categories
                            <span style="font-size: small;">(Check all that apply)</span>
                        </p>
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
                                <xsl:attribute name="for">contentCategory<xsl:value-of select='id' /></xsl:attribute>
                                <xsl:value-of select="label"></xsl:value-of>
                              </label>
                            </div>
                          </xsl:for-each>
                        </div>
                      </div>

                    <div class="form-group">
                        <p class="mb-0">
                            Tags
                            <span style="font-size: small;">(Check all that apply)</span>
                        </p>
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
                        <input type="checkbox" name="status" id="status">
                            <xsl:choose>
                                <xsl:when test="domain/form = 'approved'">
                                    <xsl:if test="domain/form/status != ''">
                                        <xsl:attribute name="checked">checked</xsl:attribute>
                                    </xsl:if>
                                </xsl:when>
                                <xsl:otherwise>
                                    <xsl:if test="domain/quote/status = 'approved'">
                                        <xsl:attribute name="checked">checked</xsl:attribute>
                                    </xsl:if>
                                </xsl:otherwise>
                            </xsl:choose>
                        </input>
                      </div>

                    <input type='submit' class='btn btn-primary' />
                </form>
            </div>
        </div>
    </xsl:template>

</xsl:stylesheet>

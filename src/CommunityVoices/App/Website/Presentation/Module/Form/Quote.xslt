<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:output method="html" indent="yes" omit-xml-declaration="yes" />
    <xsl:variable name="selectedGroups" select="/form/domain/selectedGroups" />

    <xsl:template match="domain/errors/*|domain/upload/errors/*">
        <li style="margin-bottom: 0px;"><xsl:value-of select="." /></li>
    </xsl:template>

    <xsl:template match="/form">
        <div class="row" style="padding:15px;">
            <div class="col-12">
                <form method='POST' style="max-width:400px;margin: 0 auto" id="form">
                    <xsl:attribute name="action">
                      <xsl:choose>
                        <xsl:when test="domain/quote != ''">
                          <xsl:text>/community-voices/quotes/</xsl:text>
                          <xsl:value-of select="domain/quote/id" />
                          <xsl:text>/edit</xsl:text>
                        </xsl:when>
                        <xsl:otherwise>
                          <xsl:text>/community-voices/quotes/new</xsl:text>
                        </xsl:otherwise>
                      </xsl:choose>
                    </xsl:attribute>

                    <h1 class="h3 mb-3 font-weight-normal">
                      <xsl:choose>
                        <xsl:when test="domain/quote != ''">
                          Update
                        </xsl:when>
                        <xsl:otherwise>
                          Upload
                        </xsl:otherwise>
                      </xsl:choose>

                      Quote
                    </h1>

                    <xsl:if test="domain/errors != '' or domain/upload/errors != ''">
                        <div class="card" style="margin-bottom: 16px;">
                            <div class="card-body">
                                <h1 class="h4 mb-4 font-weight-normal" style="margin-bottom: 0.5rem !important;">
                                  Some errors prevented
                                  <xsl:choose>
                                    <xsl:when test="domain/quote != ''">
                                      update
                                    </xsl:when>
                                    <xsl:otherwise>
                                      upload
                                    </xsl:otherwise>
                                  </xsl:choose>
                                </h1>
                                <ul style="margin-bottom: 0.5rem;">
                                    <xsl:apply-templates select="domain/errors/*" />
                                    <xsl:apply-templates select="domain/upload/errors/*" />
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
                                <xsl:when test="domain/quote != ''">
                                    <xsl:value-of select="domain/quote/text"/>
                                </xsl:when>
                            </xsl:choose>
                        </textarea>
                    </div>

                    <div class="form-group" id="originalTextDiv">
                      <xsl:choose>
                          <xsl:when test="domain/form != ''">
                              <xsl:if test="domain/form/originalText = ''">
                                  <xsl:attribute name="style">display: none</xsl:attribute>
                              </xsl:if>
                          </xsl:when>
                          <xsl:when test="domain/quote != ''">
                              <xsl:if test="domain/quote/originalText = ''">
                                  <xsl:attribute name="style">display: none</xsl:attribute>
                              </xsl:if>
                          </xsl:when>
                          <xsl:otherwise>
                            <xsl:attribute name="style">display: none</xsl:attribute>
                          </xsl:otherwise>
                      </xsl:choose>
                        <label for="originalText">Original Text</label>
                        <textarea name='originalText' id='originalText' class='form-control'>
                            <xsl:choose>
                                <xsl:when test="domain/form != ''">
                                    <xsl:value-of select="domain/form/originalText"/>
                                </xsl:when>
                                <xsl:when test="domain/quote != ''">
                                    <xsl:value-of select="domain/quote/originalText"/>
                                </xsl:when>
                            </xsl:choose>
                        </textarea>
                    </div>

                    <div class="form-group">
                      Edited
                      <input type="checkbox" id="editedCheckbox" class="form-control" onclick="clickEdited()">
                        <xsl:choose>
                            <xsl:when test="domain/form != ''">
                                <xsl:if test="domain/form/originalText != ''">
                                    <xsl:attribute name="checked">checked</xsl:attribute>
                                </xsl:if>
                            </xsl:when>
                            <xsl:when test="domain/quote != ''">
                                <xsl:if test="domain/quote/originalText != ''">
                                    <xsl:attribute name="checked">checked</xsl:attribute>
                                </xsl:if>
                            </xsl:when>
                        </xsl:choose>
                      </input>
                    </div>

                    <div class="form-group">
                        <label for="interviewer">Interviewer</label>
                        <input type='text' name='interviewer' id='interviewer' class='form-control'>
                            <xsl:attribute name="value">
                                <xsl:choose>
                                    <xsl:when test="domain/form != ''">
                                        <xsl:value-of select="domain/form/interviewer"/>
                                    </xsl:when>
                                    <xsl:when test="domain/quote != ''">
                                        <xsl:value-of select="domain/quote/interviewer"/>
                                    </xsl:when>
                                </xsl:choose>
                            </xsl:attribute>
                        </input>
                    </div>

                    <div class="form-group">
                        <label for="attribution">Attribution</label>
                        <input type='text' name='attribution' id='attribution' class='form-control'>
                            <xsl:attribute name="value">
                                <xsl:choose>
                                    <xsl:when test="domain/form != ''">
                                        <xsl:value-of select="domain/form/attribution"/>
                                    </xsl:when>
                                    <xsl:when test="domain/quote != ''">
                                        <xsl:value-of select="domain/quote/attribution"/>
                                    </xsl:when>
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
                                    <xsl:when test="domain/quote != ''">
                                        <xsl:value-of select="domain/quote/subAttribution"/>
                                    </xsl:when>
                                </xsl:choose>
                            </xsl:attribute>
                        </input>
                    </div>

                    <div class="form-group">
                        <label for="quotationMarks">Include Quotation Marks</label>
                        <input type='checkbox' name='quotationMarks' id='quotationMarks' class='form-control'>
                          <xsl:choose>
                              <xsl:when test="domain/form != ''">
                                  <xsl:if test="domain/form/quotationMarks = 'true'">
                                      <xsl:attribute name="checked">checked</xsl:attribute>
                                  </xsl:if>
                              </xsl:when>
                              <xsl:when test="domain/quote != ''">
                                  <xsl:if test="domain/quote/quotationMarks = 'true'">
                                      <xsl:attribute name="checked">checked</xsl:attribute>
                                  </xsl:if>
                              </xsl:when>
                              <xsl:otherwise>
                                <xsl:attribute name="checked">checked</xsl:attribute>
                              </xsl:otherwise>
                          </xsl:choose>
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
                                    <xsl:when test="domain/quote != ''">
                                        <xsl:value-of select="domain/quote/dateRecorded"/>
                                    </xsl:when>
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
                        <xsl:comment>
                          It would be good to standardize 'approved' or '3' coming out to XSLT.
                        </xsl:comment>
                        <input type="checkbox" name="status" id="status">
                            <xsl:choose>
                                <xsl:when test="domain/form != ''">
                                    <xsl:if test="domain/form/status = '3'">
                                        <xsl:attribute name="checked">checked</xsl:attribute>
                                    </xsl:if>
                                </xsl:when>
                                <xsl:when test="domain/quote != ''">
                                    <xsl:if test="domain/quote/status = 'approved'">
                                        <xsl:attribute name="checked">checked</xsl:attribute>
                                    </xsl:if>
                                </xsl:when>
                            </xsl:choose>
                        </input>
                      </div>

                    <input type='submit' name='submit' value='submit' class='btn btn-primary' />
                    <input type='submit' name='batch_submit' value='Batch Submit' class='btn btn-primary' />
                </form>
            </div>
        </div>
    </xsl:template>

</xsl:stylesheet>

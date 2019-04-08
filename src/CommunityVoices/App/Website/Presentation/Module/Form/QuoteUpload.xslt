<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:output method="html" indent="yes" omit-xml-declaration="yes" />
    <xsl:variable name="selectedGroups" select="/package/domain/selectedGroups" />

    <xsl:template match="domain/errors/*">
        <li style="margin-bottom: 0px;"><xsl:value-of select="." /></li>
    </xsl:template>

    <xsl:template match="/package">
      <div class="row" style="padding:15px;">
        <div class="col-12">
          <form action='/community-voices/quotes/new' method='post' style="max-width:400px;margin: 0 auto">

              <h1 class="h3 mb-3 font-weight-normal">Upload Quote</h1>

              <xsl:if test="domain/errors != ''">
                  <div class="card" style="margin-bottom: 16px;">
                      <div class="card-body">
                          <h1 class="h4 mb-4 font-weight-normal" style="margin-bottom: 0.5rem !important;">Some errors prevented upload</h1>
                          <ul style="margin-bottom: 0.5rem;">
                              <xsl:apply-templates select="domain/errors/*" />
                          </ul>
                      </div>
                  </div>
              </xsl:if>

              <div class="form-group">
                <label for="text">Quote</label>
                <textarea name='text' id='text' class='form-control'>
                    <xsl:if test="domain/form != ''">
                        <xsl:value-of select="domain/form/text"/>
                    </xsl:if>
                </textarea>
              </div>

              <div class="form-group">
                <label for="attribution">Attribution</label>
                <input type='text' name='attribution' id='attribution' class='form-control'>
                    <xsl:if test="domain/form != ''">
                        <xsl:attribute name="value">
                            <xsl:value-of select="domain/form/attribution"/>
                        </xsl:attribute>
                    </xsl:if>
                </input>
              </div>

              <div class="form-group">
                <label for="subAttribution">Sub-Attribution</label>
                <input class="form-control" id="subAttribution" type='text' name='subAttribution'>
                    <xsl:if test="domain/form != ''">
                        <xsl:attribute name="value">
                            <xsl:value-of select="domain/form/subAttribution"/>
                        </xsl:attribute>
                    </xsl:if>
                </input>
              </div>

              <div class="form-group">
                <label for="dateRecorded">Date Recorded</label>
                <input class="form-control" id="dateRecorded" type='text' name='dateRecorded'>
                    <xsl:if test="domain/form != ''">
                        <xsl:attribute name="value">
                            <xsl:value-of select="domain/form/dateRecorded"/>
                        </xsl:attribute>
                    </xsl:if>
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

              <input type='submit' class='btn btn-primary' />
          </form>
        </div>
      </div>
    </xsl:template>

</xsl:stylesheet>

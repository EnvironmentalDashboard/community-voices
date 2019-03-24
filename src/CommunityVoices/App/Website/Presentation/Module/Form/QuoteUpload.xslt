<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

    <xsl:template match="/package">
      <div class="row" style="padding:15px;">
        <div class="col-12">
          <xsl:if test="@failure">
              <p>Attribution missing.</p>
          </xsl:if>

          <form action='/community-voices/quotes/new/authenticate' method='post' style="max-width:400px;margin: 0 auto">

              <div class="form-group">
                <label for="text">Quote</label>
                <input class="form-control" id="text" type='text' name='text' />
              </div>

              <div class="form-group">
                <label for="attribution">Attribution</label>
                  <xsl:choose>
                      <xsl:when test='@text'>
                          <input type='text' class="form-control" id='attribution' name='attribution' value='{@text}'/>
                      </xsl:when>
                      <xsl:otherwise>
                          <input type='text' class="form-control" id='attribution' name='attribution' />
                      </xsl:otherwise>
                  </xsl:choose>
                  </div>

              <div class="form-group">
                <label for="subAttribution">Sub-Attribution</label>
                <input class="form-control" id="subAttribution" type='text' name='subAttribution' />
              </div>

              <div class="form-group">
                <label for="dateRecorded">Date Recorded</label>
                <input class="form-control" id="dateRecorded" type='text' name='dateRecorded' />
              </div>

              <div class="form-group">
                  <p class="mb-0">
                      Potential Content Categories
                      <span style="font-size: small;">(Check all that apply)</span>
                  </p>
                  <div style="overflow-y:scroll;width:100%;height: 145px;border:none">
                    <xsl:for-each select="domain/contentCategoryCollection/contentCategory">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="categories[]" id="category{id}">
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

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  version="1.0">

  <xsl:output method="xml" indent="yes" omit-xml-declaration="yes" />

  <xsl:variable name="isManager" select="package/identity/user/role = 'manager'
    or package/identity/user/role = 'administrator'"/>
  <xsl:variable name="search" select="package/domain/search"/>
  <xsl:variable name="tags" select="package/domain/tags"/>
  <xsl:variable name="attributions" select="package/domain/attributions"/>

  <xsl:template match="/package">

    <nav class="navbar navbar-light bg-light">
      <a class="navbar-brand" href="#">Quotes</a>
      <a class="btn btn-outline-primary active mr-auto" href="#">Newest first</a>
      <a class="btn btn-outline-primary" href="./quotes/new">+ Add quote</a>
    </nav>

    <div class="row" style="padding:15px;">
      <div class="col-sm-3">
        <div class="card bg-light mb-3">
          <div class="card-header bg-transparent">Search Quotes</div>
          <form action="" method="GET">
            <div class="card-body">
              <div class="form-group">
                <label for="search">Search</label>
                <input type="text" class="form-control" name="search" id="search" placeholder="Enter search terms" value="{$search}" />
              </div>
              <div class="form-group">
                <label for="tags">Tags</label>
                <select multiple="" class="form-control" id="tags" name="tags[]">
                  <xsl:for-each select="domain/groupCollection/group">
                    <option>
                      <xsl:if test="contains($tags, concat(',', id, ','))">
                        <xsl:attribute name="selected">selected</xsl:attribute>
                      </xsl:if>
                      <xsl:attribute name="value"><xsl:value-of select='id' /></xsl:attribute>
                      <xsl:value-of select="label"></xsl:value-of>
                    </option>
                  </xsl:for-each>
                </select>
              </div>
              <div class="form-group">
                <label for="attributions">Attribution</label>
                <select multiple="" class="form-control" id="attributions" name="attributions[]">
                  <xsl:for-each select="domain/allAttributions/attribution">
                    <option value="{.}">
                      <xsl:if test="contains($attributions, concat(',', ., ','))">
                        <xsl:attribute name="selected">selected</xsl:attribute>
                      </xsl:if>
                      <xsl:value-of select="."></xsl:value-of>
                    </option>
                  </xsl:for-each>
                </select>
              </div>
            </div>
            <div class="card-footer bg-transparent"><button type="button" id="reset" class="btn btn-secondary">Reset</button> <button type="submit" class="btn btn-primary">Search</button></div>
          </form>
        </div>
      </div>
      <div class="col-sm-9">
        <div class="card-columns">

          <xsl:for-each select="domain/quoteCollection/quote">

            <xsl:if test="$isManager or status = 'approved'">

              <a href='quotes/{id}' style="color: inherit; text-decoration: inherit;">
                <div class="card">
                  <div class="card-header">
                    Quote
                  </div>
                  <div class="card-body">
                    <blockquote class="blockquote mb-0">
                      <p><xsl:value-of select='text' /></p>
                      <footer class='blockquote-footer'>
                        <cite>
                          <xsl:attribute name="title"><xsl:value-of select='attribution' /></xsl:attribute>
                          <xsl:value-of select='attribution' />
                          <xsl:value-of select='subAttribution' />
                        </cite>
                      </footer>
                    </blockquote>
                  </div>
                  <xsl:if test="$isManager">
                    <div class="card-footer text-muted">
                      Status: <xsl:value-of select='status' />
                    </div>
                  </xsl:if>
                </div>
              </a>
            </xsl:if>

          </xsl:for-each>

        </div>
      </div>
    </div>
    <div class="row" style="padding:15px;">
      <div class="col-12">
        <!-- <xsl:value-of select="domain/count"></xsl:value-of> -->
        <xsl:copy-of select="domain/div"></xsl:copy-of>
      </div>
    </div>

  </xsl:template>

</xsl:stylesheet>

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  version="1.0">

  <xsl:output method="xml" indent="yes" omit-xml-declaration="yes" />

  <xsl:variable name="isManager" select="package/identity/user/role = 'manager'
    or package/identity/user/role = 'administrator'"/>

  <xsl:template match="/package">
    <div class="row" style="padding:15px;">
      <div class="col-12">
        <div class="card-columns">

          <xsl:for-each select="domain/articleCollection/quote">

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

  </xsl:template>

</xsl:stylesheet>

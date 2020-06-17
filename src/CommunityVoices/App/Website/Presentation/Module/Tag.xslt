<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  version="1.0">
  <xsl:import href="../Component/Navbar.xslt" />
  <xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

  <xsl:template match="/package">
          <xsl:call-template name="navbar">
              <xsl:with-param name="active">
                  Tags
              </xsl:with-param>
              <xsl:with-param name="rightButtons">
                  <xsl:if test="$isManager">
                    <a class="btn btn-outline-primary mr-2" href="/community-voices/tags/new">+ Add Tag</a>
                  </xsl:if>

                  <xsl:call-template name="userButtons" />
              </xsl:with-param>
          </xsl:call-template>
        <div>
            <xsl:value-of select="domain/tag/label" />
        </div>
  </xsl:template>
</xsl:stylesheet>

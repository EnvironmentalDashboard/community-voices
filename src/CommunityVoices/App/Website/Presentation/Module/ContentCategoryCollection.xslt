<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  version="1.0">

  <xsl:import href="../Component/Navbar.xslt" />
  <xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

  <xsl:template match="/package">
      <xsl:call-template name="navbar">
          <xsl:with-param name="active">
              Content Categories
          </xsl:with-param>
          <xsl:with-param name="rightButtons">
              <xsl:if test="accessControl/ContentCategory/getContentCategoryUpload != ''">
                <a class="btn btn-outline-primary mr-2" href="/community-voices/content-categories/new">+ Add content category</a>
              </xsl:if>

              <xsl:call-template name="userButtons" />
          </xsl:with-param>
      </xsl:call-template>

  <div class="row no-gutters">
    <xsl:for-each select="domain/contentCategoryCollection/contentCategory">
        <div class="col-md-4">
            <a>
                <xsl:attribute name="href">
                    <xsl:text>/community-voices/content-categories/</xsl:text>
                    <xsl:value-of select="id" />
                    <xsl:if test="/package/accessControl/ContentCategory/getContentCategoryUpdate != ''">
                      <xsl:text>/edit</xsl:text>
                    </xsl:if>
                </xsl:attribute>
                <div class="embed-responsive embed-responsive-16by9 mb-4">
                    <iframe class="embed-responsive-item" id="preview" style="pointer-events: none; width: 100%" src="/community-voices/content-categories/{id}"></iframe>
                </div>
            </a>
        </div>
    </xsl:for-each>
</div>
  </xsl:template>
</xsl:stylesheet>

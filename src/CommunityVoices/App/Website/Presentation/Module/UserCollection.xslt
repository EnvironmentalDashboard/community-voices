<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	version="1.0">

	<xsl:import href="../Component/Navbar.xslt" />
	<xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

	<xsl:template match="/package">
		<xsl:call-template name="navbar" />

      <div class="row">
          <div class="col-12">
              <h4 style="padding: 10px 20px 10px 20px">Users</h4>

              <ul>
                  <xsl:for-each select="domain/userCollection/user">
                      <li>
                          <a href="/community-voices/users/{id}">
                              <xsl:value-of select="firstName" />
                              <xsl:text> </xsl:text>
                              <xsl:value-of select="lastName" />
                          </a>
                      </li>
                  </xsl:for-each>
              </ul>
          </div>
      </div>

	</xsl:template>

</xsl:stylesheet>

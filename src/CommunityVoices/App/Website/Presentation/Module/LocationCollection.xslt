<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	version="1.0">

	<xsl:import href="../Component/Navbar.xslt" />
	<xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

	<xsl:variable name="isManager" select="package/identity/user/role = 'manager'
		or package/identity/user/role = 'administrator'"/>

	<xsl:template match="/package">
		<xsl:call-template name="navbar" />

      <div class="row">
          <div class="col-12">
              <h4 style="padding: 10px 20px 10px 20px">Locations</h4>

              <ul>
                  <xsl:for-each select="domain/locCollection/location">
                      <li>
                          <a href="/community-voices/public/digital-signage.php?loc={id}">
                              <xsl:value-of select="label" />
                          </a>
                      </li>
                  </xsl:for-each>
              </ul>
          </div>
      </div>

	</xsl:template>

</xsl:stylesheet>

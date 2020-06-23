<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  version="1.0">

  <xsl:import href="../Component/Navbar.xslt" />
  <xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

  <xsl:variable name="isManager" select="package/identity/user/role = 'manager'
      or package/identity/user/role = 'administrator'"/>

  <xsl:template match="/package">
      <xsl:call-template name="navbar"/>
          <ul class="list-group list-group-flush">
            <xsl:for-each select="domain/errors/item">
                <li class="list-group-item">
                    <xsl:value-of select="."></xsl:value-of>
                </li>
            </xsl:for-each>
          </ul>

  </xsl:template>
</xsl:stylesheet>

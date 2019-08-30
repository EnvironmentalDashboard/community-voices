<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	version="1.0">

	<xsl:import href="../Component/Navbar.xslt" />
	<xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

	<xsl:template match="/package">
		<xsl:call-template name="navbar">
			<xsl:with-param name="active">
				Users
			</xsl:with-param>
		</xsl:call-template>

      <div class="row">
          <div class="col-12">
              <h4 style="padding: 10px 20px 10px 20px">Users</h4>

              <div class="row">
                <xsl:for-each select="domain/roles/role">
                  <xsl:variable name="currentRole" select="lowercase" />

                  <div class="col-6">
                    <h5 style="padding: 10px 20px 10px 20px"><xsl:value-of select="capitalized" /></h5>

                    <xsl:choose>
                      <xsl:when test="/package/domain/userCollection/user[role=$currentRole] != ''">
                        <xsl:for-each select="/package/domain/userCollection/user[role=$currentRole]">
                          <ul>
                            <li>
                                <a href="/community-voices/users/{id}">
                                    <xsl:value-of select="firstName" />
                                    <xsl:text> </xsl:text>
                                    <xsl:value-of select="lastName" />
                                </a>
                            </li>
                          </ul>
                        </xsl:for-each>
                      </xsl:when>
                      <xsl:otherwise>
                        <p style="padding-left: 20px">No users at this role.</p>
                      </xsl:otherwise>
                    </xsl:choose>
                  </div>
                </xsl:for-each>
              </div>
          </div>
      </div>

	</xsl:template>

</xsl:stylesheet>

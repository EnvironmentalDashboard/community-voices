<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	version="1.0">

	<xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

	<xsl:variable name="isManager" select="package/identity/user/role = 'manager'
		or package/identity/user/role = 'administrator'"/>

	<xsl:template match="/package">
      <div class="row">
          <div class="col-12">
              <h1 class="text-center">Access Denied</h1>

              <p class="text-center">
                  You are not allowed to view this page.
				  <xsl:choose>
					  <xsl:when test="identity/user/id &gt; 0">
						  You do not have sufficient permissions for this action.
					  </xsl:when>
					  <xsl:otherwise>
						<a href="/community-voices/login">
						  	Please log in.
						</a>
					  </xsl:otherwise>
			  	</xsl:choose>
              </p>
          </div>
      </div>

	</xsl:template>

</xsl:stylesheet>

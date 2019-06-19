<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	version="1.0">

	<xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

	<xsl:template match="/package">
      <div class="row">
          <div class="col-12">
			  <h1 class="text-center">
				  <xsl:choose>
					  <xsl:when test="@error = 'AccessDenied'">
						  Access Denied
					  </xsl:when>
					  <xsl:when test="@error = '404'">
						  404 Error
					  </xsl:when>
					  <xsl:otherwise>
	              	  	  System Error
					  </xsl:otherwise>
			  	  </xsl:choose>
		  	  </h1>

              <p class="text-center">
				  <xsl:choose>
					  <xsl:when test="@error = 'AccessDenied'">
						  You are not allowed to view this page.

						  <xsl:choose>
							  <xsl:when test="identity/user/id = ''">
								  <a href="/community-voices/login">
									  Please log in.
								  </a>
							  </xsl:when>
							  <xsl:otherwise>
								  You do not have sufficient permissions for this action.
							  </xsl:otherwise>
						  </xsl:choose>
					  </xsl:when>
					  <xsl:when test="@error = '404'">
						  The page you are looking for could not be found.
					  </xsl:when>
					  <xsl:otherwise>
						  The page you are looking for could not be loaded.
		                  Please contact the website owner or check the system
		                  logs for more information.
					  </xsl:otherwise>
			      </xsl:choose>
              </p>
          </div>
      </div>

	</xsl:template>

</xsl:stylesheet>

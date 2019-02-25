<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	version="1.0">

	<xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

	<xsl:template match="/package">
      <div class="row">
          <div class="col-12">
              <h1 class="text-center">Access Denied</h1>

              <p class="text-center">
                  You are not allowed to view this page.
				  Are you logged in?
              </p>
          </div>
      </div>

	</xsl:template>

</xsl:stylesheet>

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	version="1.0">

	<xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

	<xsl:template match="/package">
      <div class="row">
          <div class="col-12">
              <h1 class="text-center">System Error</h1>

              <p class="text-center">
                  The page you are looking for could not be loaded.
                  Please contact the website owner or check the system
                  logs for more information.
              </p>
          </div>
      </div>

	</xsl:template>

</xsl:stylesheet>

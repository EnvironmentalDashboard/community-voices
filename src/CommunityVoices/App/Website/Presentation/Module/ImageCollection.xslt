<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	version="1.0">

	<xsl:output method="xml" indent="yes" omit-xml-declaration="yes" />

	<xsl:variable name="isManager" select="package/identity/user/role = 'manager'
		or package/identity/user/role = 'administrator'"/>

	<xsl:template match="/package">

		<div class="card-columns">

			<xsl:for-each select="domain/imageCollection/image">

				<xsl:if test="$isManager or status = 'approved'">

					<div class="card">
						<a href="images/{id}">
							<img>
								<xsl:attribute name="src">./uploads/<xsl:value-of select='id' /></xsl:attribute>
								<xsl:attribute name="alt"><xsl:value-of select='title' /></xsl:attribute>
								<xsl:attribute name="class">card-img-top</xsl:attribute>
							</img>
						</a>
						<div class="card-body">
							<blockquote class="blockquote mb-0 card-body">
								<h5><xsl:value-of select='title' /></h5>
								<p><xsl:value-of select='description' /></p>
								<footer class='blockquote-footer'>
	                <small class='text-muted'>
	                  <cite>
	                  	<xsl:attribute name="title"><xsl:value-of select='photographer' /></xsl:attribute>
	                  	<xsl:value-of select='photographer' />
	                  </cite>
	                </small>
	              </footer>
							</blockquote>
						</div>

						<xsl:if test="$isManager">

							Status:
							<xsl:value-of select='status' />

						</xsl:if>

					</div>

				</xsl:if>

			</xsl:for-each>

		</div>

		<a href="./images/new">Upload new image!</a>

	</xsl:template>

</xsl:stylesheet>

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	version="1.0">

	<xsl:output method="xml" indent="yes" omit-xml-declaration="yes" />

	<xsl:variable name="isManager" select="package/identity/user/role = 'manager'
		or package/identity/user/role = 'administrator'"/>

	<xsl:template match="/package">

		<div class="card-columns">

			<xsl:for-each select="domain/quoteCollection/quote">

				<xsl:if test="$isManager or status = 'approved'">

					<a href='quotes/{id}'>
						<div class="card">
							<div class="card-body">
								<blockquote class="blockquote mb-0 card-body">
									<p><xsl:value-of select='text' /></p>
									<footer class='blockquote-footer'>
		                <small class='text-muted'>
		                  <cite>
		                  	<xsl:attribute name="title"><xsl:value-of select='attribute' /></xsl:attribute>
		                  	<xsl:value-of select='attribution' /><br/>
		                  	<xsl:value-of select='subAttribution' />
		                  </cite>
		                </small>
		              </footer>
								</blockquote>
								<xsl:if test="$isManager">
									Status:
									<xsl:value-of select='status' />
								</xsl:if>
							</div>
						</div>
					</a>
				</xsl:if>

			</xsl:for-each>

		</div>

		<a href="./quotes/new">Upload new quote!</a> 

	</xsl:template>

</xsl:stylesheet>

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	version="1.0">

	<xsl:output method="xml" indent="yes" omit-xml-declaration="yes" />

	<xsl:variable name="isManager" select="package/identity/user/role = 'manager'
		or package/identity/user/role = 'administrator'"/>

		<xsl:template match="/package">
			
			<div class="row" style="padding:15px;">
        <div class="col-12">
					<a class="btn btn-primary mb-3" href="./quotes">&#x2190; Go back</a>

					<xsl:choose><xsl:when test="$isManager or domain/quote/status = 'approved'">

						<div class="card">
		          <div class="card-header">
		            Quote
		          </div>
		          <div class="card-body">
		            <blockquote class="blockquote mb-0">
		              <p><xsl:value-of select='domain/quote/text' /></p>
		              <footer class='blockquote-footer'>
		                <cite>
		                  <xsl:attribute name="title"><xsl:value-of select='domain/quote/attribution' /></xsl:attribute>
		                  <xsl:value-of select='domain/quote/attribution' />
		                  <xsl:value-of select='domain/quote/subAttribution' />
		                </cite>
		              </footer>
		            </blockquote>
		          </div>
		          <div class="card-footer text-muted">
		          	<p>Added <xsl:value-of select='domain/quote/dateCreated' /></p>
			          <xsl:if test="$isManager">
			            <p>Status: <xsl:value-of select='domain/quote/status' /></p>
			            <p>
										Uploader:
										<xsl:value-of select='domain/quote/addedBy/user/firstName' />
										<xsl:text> </xsl:text>
										<xsl:value-of select='domain/quote/addedBy/user/lastName' />
									</p>
									<p>
										<a>
						            <xsl:attribute name="href">./quotes/<xsl:value-of select='domain/quote/id'/>/edit</xsl:attribute>
												Edit
						        </a>
									</p>
									<p>
										<xsl:for-each select="domain/quote/tagCollection/groupCollection/group">
											<xsl:value-of select="label"></xsl:value-of>, 
										</xsl:for-each>
									</p>
			          </xsl:if>
		          </div>
		        </div>


					</xsl:when>

					<xsl:otherwise>
						Unauthorized Content
					</xsl:otherwise>

				</xsl:choose>
			</div>
		</div>

	</xsl:template>

</xsl:stylesheet>

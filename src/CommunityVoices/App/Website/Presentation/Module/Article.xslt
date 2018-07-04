<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	version="1.0">

	<xsl:output method="xml" indent="yes" omit-xml-declaration="yes" />

	<xsl:variable name="isManager" select="package/identity/user/role = 'manager'
		or package/identity/user/role = 'administrator'"/>

		<xsl:template match="/package">
			
			<div class="row" style="padding:15px;">
        <div class="col-12">
					<a class="btn btn-primary mb-3" href="./articles">&#x2190; Go back</a>

					<xsl:choose><xsl:when test="$isManager or domain/article/status = 'approved'">

						<div class="card">
		          <div class="card-header">
		            Quote
		          </div>
		          <div class="card-body">
		            <blockquote class="blockquote mb-0">
		              <xsl:value-of select='domain/article/text' />
		              <!-- <p><xsl:copy-of select='domain/article/text' disable-output-escaping="no" /></p> -->
		              <footer class='blockquote-footer'>
		                <cite>
		                  <xsl:attribute name="title"><xsl:value-of select='domain/article/attribution' /></xsl:attribute>
		                  <xsl:value-of select='domain/article/attribution' />
		                  <xsl:value-of select='domain/article/subAttribution' />
		                </cite>
		              </footer>
		            </blockquote>
		          </div>
		          <div class="card-footer text-muted">
		          	<p>Added <xsl:value-of select='domain/article/dateCreated' /></p>
			          <xsl:if test="$isManager">
			            <p>Status: <xsl:value-of select='domain/article/status' /></p>
			            <p>
										Uploader:
										<xsl:value-of select='domain/article/addedBy/user/firstName' />
										<xsl:text> </xsl:text>
										<xsl:value-of select='domain/article/addedBy/user/lastName' />
									</p>
									<p>
										<a>
						            <xsl:attribute name="href">./article/<xsl:value-of select='domain/article/id'/>/edit</xsl:attribute>
												Edit
						        </a>
									</p>
			          </xsl:if>
		          </div>
		        </div>

							<!-- <p>	Date Created: <xsl:value-of select='domain/article/dateRecorded' /> </p>

							<p> Tags: TODO </p> -->


					</xsl:when>

					<xsl:otherwise>
						Unauthorized Content
					</xsl:otherwise>

				</xsl:choose>
			</div>
		</div>

	</xsl:template>

</xsl:stylesheet>

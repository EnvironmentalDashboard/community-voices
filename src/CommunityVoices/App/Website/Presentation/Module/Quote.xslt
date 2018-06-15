<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	version="1.0">

	<xsl:output method="xml" indent="yes" omit-xml-declaration="yes" />

	<xsl:variable name="isManager" select="package/identity/user/role = 'manager'
		or package/identity/user/role = 'administrator'"/>

		<xsl:template match="/package">

			<xsl:choose><xsl:when test="$isManager or domain/quote/status = 'approved'">

				<div class="card">
					<div class="card-body">
						<blockquote class="blockquote mb-0 card-body">
							<p><xsl:value-of select='domain/quote/text' /></p>
							<footer class='blockquote-footer'>
                <small class='text-muted'>
                  <cite>
                  	<xsl:attribute name="title"><xsl:value-of select='domain/quote/attribution' /></xsl:attribute>
                  	<xsl:value-of select='domain/quote/attribution' /><br/>
                  	<xsl:value-of select='domain/quote/subAttribution' />
                  </cite>
                </small>
              </footer>
						</blockquote>
						<xsl:if test="$isManager">

						<p>---- Internal Information Below ----</p>

						<p>
							Uploader:
							<xsl:value-of select='domain/quote/addedBy/user/firstName' />
							<xsl:text> </xsl:text>
							<xsl:value-of select='domain/quote/addedBy/user/lastName' />
						</p>

						<p>
							Date Uploaded:
							<xsl:value-of select='domain/quote/dateCreated' />
						</p>

						<p>
							Status:
							<xsl:value-of select='domain/quote/status' />
						</p>

						<p>
							Edit:
							<a>
			            <xsl:attribute name="href">./quotes/<xsl:value-of select='domain/quote/id'/>/edit</xsl:attribute>
									xxx
			        </a>
						</p>

					</xsl:if>
					</div>
				</div>

					<!-- <p>	Date Created: <xsl:value-of select='domain/quote/dateRecorded' /> </p>

					<p> Tags: TODO </p> -->


			</xsl:when>

			<xsl:otherwise>
				Unauthorized Content
			</xsl:otherwise>

		</xsl:choose>

	</xsl:template>

</xsl:stylesheet>

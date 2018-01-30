<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	version="1.0">

	<xsl:output method="xml" indent="yes" omit-xml-declaration="yes" />

	<xsl:variable name="isManager" select="package/identity/user/role = 'manager'
		or package/identity/user/role = 'administrator'"/>

		<xsl:template match="/package">

			<xsl:choose><xsl:when test="$isManager or domain/quote/status = 'approved'">

				<div class="middle">

					<p> Quote <xsl:value-of select='domain/quote/id' /> </p>

					<h2> <xsl:value-of select='domain/quote/text' /> </h2>

					<p>
						- <xsl:value-of select='domain/quote/attribution' />,
						<xsl:value-of select='domain/quote/subAttribution' />
					</p>

				</div>

				<div class="right">

					<p>	Date Created: <xsl:value-of select='domain/quote/dateRecorded' /> </p>

					<p> Tags: TODO </p>

					<!-- Information for Manager & above -->

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

			</xsl:when>

			<xsl:otherwise>
				Unauthorized Content
			</xsl:otherwise>

		</xsl:choose>

	</xsl:template>

</xsl:stylesheet>

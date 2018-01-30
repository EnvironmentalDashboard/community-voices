<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	version="1.0">

	<xsl:output method="xml" indent="yes" omit-xml-declaration="yes" />

	<xsl:variable name="isManager" select="package/identity/user/role = 'manager'
		or package/identity/user/role = 'administrator'"/>

		<xsl:template match="/package">

			<xsl:choose><xsl:when test="$isManager or domain/image/status = 'approved'">

				<div class="middle">

					<p> image <xsl:value-of select='domain/image/id' /> </p>

					<img>
							<xsl:attribute name="src"><xsl:value-of select='domain/image/filename' /></xsl:attribute>
					</img>

					<p>
						- <xsl:value-of select='domain/image/title' />,
						<xsl:value-of select='domain/image/description' />
					</p>

				</div>

				<div class="right">

					<p>	Date Created: <xsl:value-of select='domain/image/datetaken' /> </p>

					<p> Tags: TODO </p>

					<!-- Information for Manager & above -->

					<xsl:if test="$isManager">

						<p>---- Internal Information Below ----</p>

						<p>
							Uploader:
							<xsl:value-of select='domain/image/addedBy/user/firstName' />
							<xsl:text> </xsl:text>
							<xsl:value-of select='domain/image/addedBy/user/lastName' />
						</p>

						<p>
							Date Uploaded:
							<xsl:value-of select='domain/image/dateCreated' />
						</p>

						<p>
							Status:
							<xsl:value-of select='domain/image/status' />
						</p>

						<p>
							Edit:
							<a>
			            <xsl:attribute name="href">./images/<xsl:value-of select='domain/image/id'/>/edit</xsl:attribute>
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

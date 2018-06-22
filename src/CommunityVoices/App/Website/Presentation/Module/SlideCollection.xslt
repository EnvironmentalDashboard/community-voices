<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	version="1.0">

	<xsl:output method="xml" indent="yes" omit-xml-declaration="yes" />

	<xsl:variable name="isManager" select="package/identity/user/role = 'manager'
		or package/identity/user/role = 'administrator'"/>

	<xsl:template match="/package">

		<xsl:for-each select="domain/slideCollection/slide">

			<xsl:if test="$isManager or status = 'approved'">

				<!-- <xsl:variable name="i" select="position()" />
				<xsl:copy>
         <xsl:value-of select="concat('$i = ', $i)"/>
        </xsl:copy> -->
        <!-- https://stackoverflow.com/questions/3344965/increment-a-value-in-xslt -->

				<div class="col-sm-3">

					<a href= "slides/{id}"/>

						<svg height="1080" width="1920" style="width:100%;height:auto" viewBox="0 0 100 50" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
							<rect width="100%" height="100%" style="fill:rgb(0,0,0)" />
							<g id="render">
								<image x="10" y="10" width="35%">
									<xsl:attribute name="xlink:href">/community-voices/uploads/<xsl:value-of select='image' /></xsl:attribute>
								</image>
								<!-- <image x="10" y="10" width="35%">
									<xsl:attribute name="xlink:href">/community-voices/uploads/<xsl:value-of select='image' /></xsl:attribute>
								</image> -->
								<text x="50%" y="45%" fill="#fff" font-size="4px"><xsl:value-of select='quote/quote/text' /></text>
							</g>
						</svg>

						<!-- <p>
							<xsl:value-of select='image' />,
							<xsl:value-of select='quote/quote/text' />,
							<xsl:value-of select='contentCategory/contentCategory/id' />
						</p> -->

					<a/>

					<xsl:if test="$isManager">

						Status:
						<xsl:value-of select='status' />

					</xsl:if>

				</div>

			</xsl:if>

		</xsl:for-each>


	</xsl:template>

</xsl:stylesheet>

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	version="1.0">

	<xsl:output method="xml" indent="yes" omit-xml-declaration="yes" />

	<xsl:variable name="isManager" select="package/identity/user/role = 'manager'
		or package/identity/user/role = 'administrator'"/>

	<xsl:template match="/package">

		<div class="row" style="padding:15px;">
				<xsl:for-each select="domain/slideCollection/slide">

					<xsl:if test="$isManager or status = 'approved'">

						<div class="col-xl-3 col-md-4 col-sm-6">

							<a href= "slides/{id}"/>

								<svg height="1080" width="1920" style="width:100%;height:auto" viewBox="0 0 100 50" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
									<rect width="100%" height="100%" style="fill:rgb(0,0,0)" />
									<g id="render">
										<image x="10" y="10" width="35%">
											<xsl:attribute name="xlink:href">/community-voices/uploads/<xsl:value-of select='image' /></xsl:attribute>
										</image>
										<text x="50%" y="45%" fill="#fff" font-size="4px"><xsl:copy-of select='tspan' /></text>
										<image x="0" y="5" width="100%">
                      <xsl:choose>
                        <xsl:when test="contentCategory/contentCategory/id = 1">
                          <xsl:attribute name="xlink:href">https://environmentaldashboard.org/cv_slides/categorybars/serving-our-community.png</xsl:attribute>
                        </xsl:when>
                        <xsl:when test="contentCategory/contentCategory/id = 2">
                          <xsl:attribute name="xlink:href">https://environmentaldashboard.org/cv_slides/categorybars/our-downtown.png</xsl:attribute>
                        </xsl:when>
                        <xsl:when test="contentCategory/contentCategory/id = 3">
                          <xsl:attribute name="xlink:href">https://environmentaldashboard.org/cv_slides/categorybars/next-generation.png</xsl:attribute>
                        </xsl:when>
                        <xsl:when test="contentCategory/contentCategory/id = 4">
                          <xsl:attribute name="xlink:href">https://environmentaldashboard.org/cv_slides/categorybars/heritage.png</xsl:attribute>
                        </xsl:when>
                        <xsl:when test="contentCategory/contentCategory/id = 5">
                          <xsl:attribute name="xlink:href">https://environmentaldashboard.org/cv_slides/categorybars/nature_photos.png</xsl:attribute>
                        </xsl:when>
                        <xsl:when test="contentCategory/contentCategory/id = 6">
                          <xsl:attribute name="xlink:href">https://environmentaldashboard.org/cv_slides/categorybars/neighbors.png</xsl:attribute>
                        </xsl:when>
                      </xsl:choose>
                    </image>
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
		</div>


	</xsl:template>

</xsl:stylesheet>

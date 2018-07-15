<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	version="1.0">

	<xsl:output method="xml" indent="yes" omit-xml-declaration="yes" />

	<xsl:variable name="isManager" select="package/identity/user/role = 'manager'
		or package/identity/user/role = 'administrator'"/>

	<xsl:template match="/package">

		<div class="row" style="padding:15px;">
				<xsl:for-each select="domain/slideCollection/slide">

					<xsl:if test="$isManager or status = 'approved'">

						<div class="col-sm-10">

							<a href= "slides/{id}"/>

								<img src="https://environmentaldashboard.org/cv/slides/{id}" class="img-fluid" />

								<!-- <p>
									<xsl:value-of select='image/image/id' />,
									<xsl:value-of select='quote/quote/text' />,
									<xsl:value-of select='contentCategory/contentCategory/id' />
								</p> -->

							<a/>

						</div>

						<div class="col-sm-2">
							<xsl:if test="$isManager">

								Status:
								<xsl:value-of select='status' />

							</xsl:if>
						</div>

					</xsl:if>

				</xsl:for-each>
		</div>
		<div class="row" style="padding:15px;">
      <div class="col-12">
        <xsl:copy-of select="domain/div"></xsl:copy-of>
      </div>
    </div>


	</xsl:template>

</xsl:stylesheet>

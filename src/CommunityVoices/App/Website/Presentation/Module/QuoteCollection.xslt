<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	version="1.0">

	<xsl:output method="xml" indent="yes" omit-xml-declaration="yes" />

	<xsl:variable name="isManager" select="package/identity/user/role = 'manager' 
		or package/identity/user/role = 'administrator'"/>

	<xsl:template match="/package">

		<ul style="list-style-type:none">

			<xsl:for-each select="domain/quoteCollection/quote">

				<xsl:if test="$isManager or status = 'approved'">

					<li>

						<a href= "quotes/{id}"/> 

							<p> 
								<xsl:value-of select='text' /> 
								- 
								<xsl:value-of select='attribution' />,
								<xsl:value-of select='subAttribution' /> 
							</p>

						<a/>

						<xsl:if test="$isManager">

							Status: 
							<xsl:value-of select='status' />

						</xsl:if>

					</li>

				</xsl:if>			

			</xsl:for-each>

		</ul>

	</xsl:template>

</xsl:stylesheet>
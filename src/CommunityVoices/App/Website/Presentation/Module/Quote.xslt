<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	version="1.0">

	<xsl:output method="xml" indent="yes" omit-xml-declaration="yes" />

	<xsl:variable name="isManager" select="identity/user/role "/>

	<xsl:template match="/quote">

		<div class="middle">

			<p> Quote <xsl:value-of select='id' /> </p>

			<h2> <xsl:value-of select='text' /> </h2>

			<p> Author: <xsl:value-of select='attribution' /> </p>

		</div>

		<div class="right">

			<p>	Date Created: <xsl:value-of select='dateRecorded' /> </p>

			<!-- Information for Manager & above -->

			<xsl:if test="isManager">

				<p> Internal Information Below </p>

				<p> 
					Uploader: 
					<xsl:value-of select='addedBy/user/firstName' />
					
					<xsl:value-of select='addedBy/user/lastName' />
				</p>

				<p>
					Date Uploaded:
					<xsl:value-of select='dateCreated' />
				</p>

				<p>
					Status:
				</p>

			</xsl:if>

		</div> 

	</xsl:template>

</xsl:stylesheet>

<!-- <quote>
	<id>3</id>

	<addedBy>
		<user>
			<id>2</id>
			<email>aarthur@oberlin.edu</email>
			<firstName>Augustus</firstName>
			<lastName>Arthur</lastName>
			<role>3</role>
		</user>
	</addedBy>
	<dateCreated>2018-01-19 16:20:34</dateCreated>

	<type>3</type>
	<status>3</status>
	<tagCollection>
		<groupCollection>
			
		</groupCollection>
	</tagCollection>
	<text>Text Tim.</text>
	<attribution>Augustus Arthur</attribution>
	<dateRecorded>2018</dateRecorded>
	<publicDocumentLink>
		
	</publicDocumentLink>
	<sourceDocumentLink>
		
	</sourceDocumentLink>
</quote> -->
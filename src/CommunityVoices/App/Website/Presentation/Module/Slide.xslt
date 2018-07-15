<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	version="1.0">

	<xsl:output method="xml" indent="yes" omit-xml-declaration="yes" />

	<xsl:variable name="isManager" select="package/identity/user/role = 'manager'
		or package/identity/user/role = 'administrator'"/>

  <xsl:template match="domain/slide/g" name="contents">
     <xsl:copy-of select="node()"/>
  </xsl:template>

	<xsl:template match="/package"><![CDATA[<?xml version="1.0" encoding="UTF-8" standalone="no"?><!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">]]>
    <svg height="1080" width="1920" style="width:100%;height:auto" viewBox="0 0 100 50" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1">
			<rect width="100%" height="100%" style="fill:rgb(0,0,0)" />
			<g id="render">
        <!-- TODO: FIX BELOW -->
        <![CDATA[<!--]]>
        <xsl:apply-templates name="contents"/>
			</g>
		</svg>
	</xsl:template>

</xsl:stylesheet>

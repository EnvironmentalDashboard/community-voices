<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl"
    version="1.0">

    <xsl:variable name="isManager" select="package/identity/user/role = 'manager'
      or package/identity/user/role = 'administrator'" />

    <xsl:template name="can">
      <xsl:param name="action" />
      <xsl:param name="details" />
      <xsl:param name="then" />

      <xsl:if test="package/accessControl/action != ''">
        <xsl:copy-of select="$then" />
      </xsl:if>
    </xsl:template>

</xsl:stylesheet>

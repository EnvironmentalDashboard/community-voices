<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl"
    version="1.0">

    <xsl:variable name="isManager" select="package/identity/user/role = 'manager'
      or package/identity/user/role = 'administrator'" />

    <xsl:template name="can">
      <xsl:param name="action" />
      <xsl:param name="details" />
      <xsl:param name="then" />

      <xsl:variable name="phpFunction">
        <xsl:text>CommunityVoices\App\Website\Component\Presenter::can</xsl:text>
      </xsl:variable>
      <xsl:variable name="accessControlNamespace">
        <xsl:text>CommunityVoices\App\Api\AccessControl\</xsl:text>
      </xsl:variable>

      <xsl:if test="php:function(string($phpFunction),concat(string($accessControlNamespace),$action),/package/identity/user,$details)">
        <xsl:copy-of select="$then" />
      </xsl:if>
    </xsl:template>

</xsl:stylesheet>

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  version="1.0">

  <xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

  <xsl:template match="/package">
    <div>
        <xsl:attribute name="style">width:100%;background:<xsl:value-of select="domain/contentCategory/color" />;position:absolute;bottom:0;height:14vh;text-transform:uppercase;color:#fff;font-size:8vh;line-height:14vh;font-weight:700;padding-left:1vw</xsl:attribute>
        <xsl:value-of select="domain/contentCategory/label" />
      <img src="" alt="" style="position:absolute;right:3vw;bottom:2vw;width:25vw;height:auto">
        <xsl:attribute name="src">/community-voices/uploads/<xsl:value-of select="domain/contentCategory/image/image/id" /></xsl:attribute>
      </img>
    </div>
  </xsl:template>

</xsl:stylesheet>

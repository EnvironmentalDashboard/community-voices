<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  version="1.0">

  <xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

  <xsl:template match="/package">
    <div>
        <xsl:attribute name="style">width:100%;background:<xsl:value-of select="domain/slide/contentCategory/contentCategory/color" />;position:absolute;bottom:0;height:14vh;text-transform:uppercase;color:#fff;font-size:8vh;line-height:14vh;font-weight:700;padding-left:1vw</xsl:attribute>
        <xsl:value-of select="domain/slide/contentCategory/contentCategory/label" />
      <img src="" alt="" style="position:absolute;right:3vw;bottom:2vw;width:25vw;height:auto">
        <xsl:choose>
          <xsl:when test="domain/slide/contentCategory/contentCategory/id = 1">
            <xsl:attribute name="src">https://environmentaldashboard.org/community-voices/public/images/1.png</xsl:attribute>
          </xsl:when>
          <xsl:when test="domain/slide/contentCategory/contentCategory/id = 2">
            <xsl:attribute name="src">https://environmentaldashboard.org/community-voices/public/images/2.png</xsl:attribute>
          </xsl:when>
          <xsl:when test="domain/slide/contentCategory/contentCategory/id = 3">
            <xsl:attribute name="src">https://environmentaldashboard.org/community-voices/public/images/3.png</xsl:attribute>
          </xsl:when>
          <xsl:when test="domain/slide/contentCategory/contentCategory/id = 4">
            <xsl:attribute name="src">https://environmentaldashboard.org/community-voices/public/images/4.png</xsl:attribute>
          </xsl:when>
          <xsl:when test="domain/slide/contentCategory/contentCategory/id = 5">
            <xsl:attribute name="src">https://environmentaldashboard.org/community-voices/public/images/5.png</xsl:attribute>
          </xsl:when>
          <xsl:when test="domain/slide/contentCategory/contentCategory/id = 6">
            <xsl:attribute name="src">https://environmentaldashboard.org/community-voices/public/images/6.png</xsl:attribute>
          </xsl:when>
        </xsl:choose>
      </img>
    </div>
  </xsl:template>

</xsl:stylesheet>

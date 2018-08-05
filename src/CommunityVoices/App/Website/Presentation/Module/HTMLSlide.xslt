<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  version="1.0">

  <xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

  <xsl:variable name="isManager" select="package/identity/user/role = 'manager'
    or package/identity/user/role = 'administrator'"/>

  <xsl:template match="/package">
    <div style="display: flex;align-items:center;height: 86vh">
      <img src="https://environmentaldashboard.org/cv/uploads/{domain/slide/image/image/id}" alt="{domain/slide/image/image/title}" style="flex-shrink: 0;width: auto;max-height: 86vh;max-width:70vw;max-height:100%" />
      <h1 style="{concat('color:#fff;padding:3vw;font-size:', domain/slide/font_size, 'vw;font-weight:400')}">
        <xsl:value-of select="domain/slide/quote/quote/text"></xsl:value-of>
        <xsl:if test="domain/slide/quote/quote/attribution != ''">
          <div style="margin-top:1.5rem;font-size:70%">
            <div style="max-width:6%;display:inline-block;height:100%;vertical-align:top">
              &#x2014;
            </div>
            <div style="max-width:90%;display:inline-block;height:100%">
              <xsl:value-of select="domain/slide/quote/quote/attribution" />
            </div>
          </div>
          <!-- <div style="{concat('font-size:', domain/slide/font_size, 'vw;margin-top:2vw')}">&#x2014; <xsl:value-of select="domain/slide/quote/quote/attribution"></xsl:value-of></div> -->
        </xsl:if>
      </h1>
    </div>
    <div>
      <xsl:choose>
        <xsl:when test="domain/slide/contentCategory/contentCategory/id = 1">
          <xsl:attribute name="style">width:100%;background:rgb(150,81,23);position:absolute;bottom:0;height:14vh;text-transform:uppercase;color:#fff;font-size:8vh;line-height:14vh;font-weight:700;padding-left:1vw</xsl:attribute>
          Serving Our Community
        </xsl:when>
        <xsl:when test="domain/slide/contentCategory/contentCategory/id = 2">
          <xsl:attribute name="style">width:100%;background:rgb(92,92,92);position:absolute;bottom:0;height:14vh;text-transform:uppercase;color:#fff;font-size:8vh;line-height:14vh;font-weight:700;padding-left:1vw</xsl:attribute>
          Our Downtown
        </xsl:when>
        <xsl:when test="domain/slide/contentCategory/contentCategory/id = 3">
          <xsl:attribute name="style">width:100%;background:rgb(4,54,75);position:absolute;bottom:0;height:14vh;text-transform:uppercase;color:#fff;font-size:8vh;line-height:14vh;font-weight:700;padding-left:1vw</xsl:attribute>
          Next Generation
        </xsl:when>
        <xsl:when test="domain/slide/contentCategory/contentCategory/id = 4">
          <xsl:attribute name="style">width:100%;background:rgb(86,114,34);position:absolute;bottom:0;height:14vh;text-transform:uppercase;color:#fff;font-size:8vh;line-height:14vh;font-weight:700;padding-left:1vw</xsl:attribute>
          Heritage
        </xsl:when>
        <xsl:when test="domain/slide/contentCategory/contentCategory/id = 5">
          <xsl:attribute name="style">width:100%;background:rgb(67,118,45);position:absolute;bottom:0;height:14vh;text-transform:uppercase;color:#fff;font-size:8vh;line-height:14vh;font-weight:700;padding-left:1vw</xsl:attribute>
          Natural Oberlin
        </xsl:when>
        <xsl:when test="domain/slide/contentCategory/contentCategory/id = 6">
          <xsl:attribute name="style">width:100%;background:rgb(94,0,224);position:absolute;bottom:0;height:14vh;text-transform:uppercase;color:#fff;font-size:8vh;line-height:14vh;font-weight:700;padding-left:1vw</xsl:attribute>
          Our Neighbours
        </xsl:when>
      </xsl:choose>
      <img src="" alt="" style="position:absolute;right:3vw;bottom:2vw;width:25vw;height:auto">
        <xsl:choose>
          <xsl:when test="domain/slide/contentCategory/contentCategory/id = 1">
            <xsl:attribute name="src">https://environmentaldashboard.org/cv/public/1.png</xsl:attribute>
          </xsl:when>
          <xsl:when test="domain/slide/contentCategory/contentCategory/id = 2">
            <xsl:attribute name="src">https://environmentaldashboard.org/cv/public/2.png</xsl:attribute>
          </xsl:when>
          <xsl:when test="domain/slide/contentCategory/contentCategory/id = 3">
            <xsl:attribute name="src">https://environmentaldashboard.org/cv/public/3.png</xsl:attribute>
          </xsl:when>
          <xsl:when test="domain/slide/contentCategory/contentCategory/id = 4">
            <xsl:attribute name="src">https://environmentaldashboard.org/cv/public/4.png</xsl:attribute>
          </xsl:when>
          <xsl:when test="domain/slide/contentCategory/contentCategory/id = 5">
            <xsl:attribute name="src">https://environmentaldashboard.org/cv/public/5.png</xsl:attribute>
          </xsl:when>
          <xsl:when test="domain/slide/contentCategory/contentCategory/id = 6">
            <xsl:attribute name="src">https://environmentaldashboard.org/cv/public/6.png</xsl:attribute>
          </xsl:when>
        </xsl:choose>
      </img>
    </div>
  </xsl:template>

</xsl:stylesheet>

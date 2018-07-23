<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  version="1.0">

  <xsl:output method="xml" indent="yes" omit-xml-declaration="yes" />

  <xsl:variable name="isManager" select="package/identity/user/role = 'manager'
    or package/identity/user/role = 'administrator'"/>

  <xsl:template match="/package">
    <div style="display: flex;align-items:center;">
      <img src="https://environmentaldashboard.org/cv/uploads/{domain/slide/image/image/id}" alt="{domain/slide/image/image/title}" style="flex-shrink: 0;width: auto;height: 86vh;max-width:70vw" />
      <h1 style="color:#fff;padding:3vw;font-size:3vw;font-weight:400">
        <xsl:value-of select="domain/slide/quote/quote/text"></xsl:value-of>
        <div style="font-size:2vw;margin-top:2vw">&#x2014; <xsl:value-of select="domain/slide/quote/quote/attribution"></xsl:value-of></div>
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
      <img src="http://via.placeholder.com/200x200?text=Content Category" alt="" style="position:absolute;right:3vw;bottom:2vw;width:25vw;height:auto">
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

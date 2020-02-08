<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  version="1.0">

  <xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

  <xsl:variable name="isManager" select="package/identity/user/role = 'manager'
    or package/identity/user/role = 'administrator'"/>

  <xsl:template match="/package">
    <div style="display: flex;align-items:center;height: 86vh">
      <img src="/community-voices/uploads/{domain/slide/image/image/id}" alt="{domain/slide/image/image/title}" style="flex-shrink: 0;width: auto;max-height: 86vh;max-width:70vw;max-height:100%" />
      <h1 style="{concat('color:#fff;padding:3vw;font-size:', domain/slide/font_size, 'vw;font-weight:400')}">
        <xsl:choose>
          <xsl:when test="domain/slide/quote/quote/quotationMarks = ''">
            <xsl:value-of select="domain/slide/quote/quote/text"></xsl:value-of>
          </xsl:when>
          <xsl:otherwise>
            &#8220;<xsl:value-of select="domain/slide/quote/quote/text"></xsl:value-of>&#8221;
          </xsl:otherwise>
        </xsl:choose>
        <xsl:if test="domain/slide/quote/quote/attribution != ''">
          <div style="margin-top:1.5rem;font-size:70%">
            <div style="max-width:6%;display:inline-block;height:100%;vertical-align:top">
              &#x2014;
            </div>
            <div style="max-width:90%;display:inline-block;height:100%">
              <xsl:value-of select="domain/slide/quote/quote/attribution" />
              <xsl:if test="domain/slide/quote/quote/subAttribution != ''">, <xsl:value-of select='domain/slide/quote/quote/subAttribution' /></xsl:if>
            </div>
          </div>
          <!-- <div style="{concat('font-size:', domain/slide/font_size, 'vw;margin-top:2vw')}">&#x2014; <xsl:value-of select="domain/slide/quote/quote/attribution"></xsl:value-of></div> -->
        </xsl:if>
      </h1>
    </div>
    <div>
        <xsl:attribute name="style">width:100%;background:<xsl:value-of select="domain/slide/contentCategory/contentCategory/color" />;position:absolute;bottom:0;height:14vh;text-transform:uppercase;color:#fff;font-size:7vh;line-height:14vh;font-weight:700;padding-left:1vw</xsl:attribute>
        <xsl:if test="domain/slide/logo/image/id != ''">
            <img alt="" style="position:absolute;left:2vw;bottom:2vw;width:10vw;height:auto">
                <xsl:attribute name="src">/community-voices/uploads/<xsl:value-of select="domain/slide/logo/image/id" /></xsl:attribute>
            </img>
        </xsl:if>
        <span>
            <xsl:if test="domain/slide/logo/image/id != ''">
                <xsl:attribute name="style">position:absolute;left:14vw</xsl:attribute>
            </xsl:if>
            <xsl:value-of select="domain/slide/contentCategory/contentCategory/label" />
        </span>
        <img alt="" style="position:absolute;right:3vw;bottom:2vw;width:25vw;height:auto">
          <xsl:attribute name="src">/community-voices/uploads/<xsl:value-of select="domain/slide/contentCategory/contentCategory/image/image/id" /></xsl:attribute>
        </img>
    </div>
  </xsl:template>

</xsl:stylesheet>

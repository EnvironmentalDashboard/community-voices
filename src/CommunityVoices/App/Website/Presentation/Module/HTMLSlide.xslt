<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  version="1.0">

  <xsl:output method="xml" indent="yes" omit-xml-declaration="yes" />

  <xsl:variable name="isManager" select="package/identity/user/role = 'manager'
    or package/identity/user/role = 'administrator'"/>

  <xsl:template match="/package">
    <div style="display: flex;align-items:center;justify-content: center;overflow: hidden;margin: 10px;">
      <img src="http://environmentaldashboard.org/cv/uploads/{domain/slide/image/image/id}" alt="{domain/slide/image/image/title}" style="margin:10px;flex-shrink: 0;width: auto;min-height: 100%;" />
      <h1 style="color:#fff;font-family:Comfortaa, sans-serif">
        <xsl:value-of select="domain/slide/quote/quote/text"></xsl:value-of>
        <div style="font-size:1rem;margin-top:1rem">&#x2014; <xsl:value-of select="domain/slide/quote/quote/attribution"></xsl:value-of></div>
      </h1>
    </div>
  </xsl:template>

</xsl:stylesheet>

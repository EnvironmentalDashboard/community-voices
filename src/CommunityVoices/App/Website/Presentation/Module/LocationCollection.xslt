<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	version="1.0">

	<xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

	<xsl:variable name="isManager" select="package/identity/user/role = 'manager'
		or package/identity/user/role = 'administrator'"/>

	<xsl:template match="/package">

      <nav class="navbar navbar-light bg-light" style="justify-content:initial">
        <a class="navbar-brand" href="/community-voices/" style="color:#28a745;font-family:'Multicolore',sans-serif">Community Voices</a>
        <div class="float-left">
          <ul class="navbar-nav" style="width:initial">
            <li class="nav-item mr-2">
              <a class="nav-link" href="../articles">Articles</a>
            </li>
            <li class="nav-item mr-2">
              <a class="nav-link" href="../slides">Slides</a>
            </li>
            <li class="nav-item mr-2">
              <a class="nav-link" href="../images">Images</a>
            </li>
            <li class="nav-item mr-2">
              <a class="nav-link" href="../quotes">Quotes</a>
            </li>
          </ul>
        </div>
        <div style="margin-left:auto"></div>
      </nav>

      <div class="row">
          <div class="col-12">
              <h4 style="padding: 10px 20px 10px 20px">Locations</h4>

              <ul>
                  <xsl:for-each select="domain/locCollection/location">
                      <li>
                          <a href="/community-voices/public/digital-signage.php?loc={id}">
                              <xsl:value-of select="label" />
                          </a>
                      </li>
                  </xsl:for-each>
              </ul>
          </div>
      </div>

	</xsl:template>

</xsl:stylesheet>

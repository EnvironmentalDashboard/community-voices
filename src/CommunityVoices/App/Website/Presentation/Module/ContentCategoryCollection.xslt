<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  version="1.0">

  <xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

  <xsl:variable name="isManager" select="package/identity/user/role = 'manager'
      or package/identity/user/role = 'administrator'"/>

  <xsl:template match="/package">
      <nav class="navbar navbar-light bg-light" style="justify-content:initial">
    <a class="navbar-brand" href="/community-voices/" style="color:#28a745;font-family:'Multicolore',sans-serif">Community Voices</a>
    <ul class="navbar-nav" style="width:initial">
      <li class="nav-item mr-2">
        <a class="nav-link" href="/community-voices/articles">Articles</a>
      </li>
      <li class="nav-item mr-2">
        <a class="nav-link" href="/community-voices/slides">Slides</a>
      </li>
      <li class="nav-item mr-2">
        <a class="nav-link" href="/community-voices/images">Images</a>
      </li>
      <li class="nav-item mr-2">
        <a class="nav-link" href="/community-voices/quotes">Quotes</a>
      </li>
      <li class="nav-item mr-2 active">
          <a class="nav-link" href="/community-voices/content-categories">Content Categories <span class="sr-only">(current)</span></a>
      </li>
    </ul>
    <div style="margin-left:auto">
        <xsl:if test="$isManager">
          <a class="btn btn-outline-primary mr-2" href="/community-voices/slides/new">+ Add content category</a>
        </xsl:if>
      <xsl:choose>
        <xsl:when test="identity/user/id &gt; 0">
          <a class="btn btn-outline-primary" href="/community-voices/logout">Logout <xsl:value-of select="identity/user/firstName" /></a>
          <!-- <a>
            <xsl:attribute name="href">user/<xsl:value-of select="identity/user/id" /></xsl:attribute>
            View Account
          </a> -->
        </xsl:when>
        <xsl:otherwise>
          <div class="btn-group">
            <a class="btn btn-outline-primary" href="/community-voices/login">Login</a>
            <a class="btn btn-outline-primary" href="/community-voices/register">Register</a>
          </div>
        </xsl:otherwise>
      </xsl:choose>
    </div>
  </nav>

  <div class="row">
    <xsl:for-each select="domain/contentCategoryCollection/contentCategory">
        <div class="col-md-4">
            <iframe class="embed-responsive-item" id="preview" style="pointer-events: none;" src="/community-voices/content-categories/{id}"></iframe>
        </div>
    </xsl:for-each>
</div>
  </xsl:template>
</xsl:stylesheet>

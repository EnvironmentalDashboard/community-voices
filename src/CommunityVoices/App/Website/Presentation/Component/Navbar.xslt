<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:variable name="isManager" select="package/identity/user/role = 'manager'
      or package/identity/user/role = 'administrator'" />

    <xsl:template name="userButtons">
        <xsl:choose>
          <xsl:when test="identity/user/id &gt; 0">
            <a class="btn btn-outline-primary mr-2" href="/community-voices/logout">Logout <xsl:value-of select="identity/user/firstName" /></a>
          </xsl:when>
          <xsl:otherwise>
            <div class="btn-group">
              <a class="btn btn-outline-primary" href="/community-voices/login">Login</a>
              <a class="btn btn-outline-primary" href="/community-voices/register">Register</a>
            </div>
          </xsl:otherwise>
        </xsl:choose>
    </xsl:template>

    <xsl:template name="navitem">
        <xsl:param name="href" />
        <xsl:param name="name" />
        <xsl:param name="active" />

        <li>
            <xsl:attribute name="class">
                <xsl:choose>
                    <xsl:when test="normalize-space($active) = normalize-space($name)">
                        nav-item mr-2 active
                    </xsl:when>
                    <xsl:otherwise>
                        nav-item mr-2
                    </xsl:otherwise>
                </xsl:choose>
            </xsl:attribute>

            <a class="nav-link" href="{normalize-space($href)}">
                <xsl:value-of select="$name" />
            </a>
        </li>
    </xsl:template>

    <xsl:template name="navbar">
        <xsl:param name="active" />
        <xsl:param name="rightButtons" />

        <nav class="navbar navbar-light bg-light" style="justify-content:initial">
          <a class="navbar-brand" href="/community-voices/" style="color:#28a745;font-family:'Multicolore',sans-serif">Community Voices</a>
          <ul class="navbar-nav" style="width:initial">
              <xsl:call-template name="navitem">
                  <xsl:with-param name="href">
                      /community-voices/articles
                  </xsl:with-param>
                  <xsl:with-param name="name">
                      Articles
                  </xsl:with-param>
                  <xsl:with-param name="active">
                      <xsl:value-of select="$active" />
                  </xsl:with-param>
              </xsl:call-template>
              <xsl:call-template name="navitem">
                  <xsl:with-param name="href">
                      /community-voices/slides
                  </xsl:with-param>
                  <xsl:with-param name="name">
                      Slides
                  </xsl:with-param>
                  <xsl:with-param name="active">
                      <xsl:value-of select="$active" />
                  </xsl:with-param>
              </xsl:call-template>
              <xsl:call-template name="navitem">
                  <xsl:with-param name="href">
                      /community-voices/images
                  </xsl:with-param>
                  <xsl:with-param name="name">
                      Images
                  </xsl:with-param>
                  <xsl:with-param name="active">
                      <xsl:value-of select="$active" />
                  </xsl:with-param>
              </xsl:call-template>
              <xsl:call-template name="navitem">
                  <xsl:with-param name="href">
                      /community-voices/quotes
                  </xsl:with-param>
                  <xsl:with-param name="name">
                      Quotes
                  </xsl:with-param>
                  <xsl:with-param name="active">
                      <xsl:value-of select="$active" />
                  </xsl:with-param>
              </xsl:call-template>
              <xsl:if test="$isManager">
                  <xsl:call-template name="navitem">
                      <xsl:with-param name="href">
                          /community-voices/content-categories
                      </xsl:with-param>
                      <xsl:with-param name="name">
                          Content Categories
                      </xsl:with-param>
                      <xsl:with-param name="active">
                          <xsl:value-of select="$active" />
                      </xsl:with-param>
                  </xsl:call-template>
            </xsl:if>
            <xsl:if test="$isManager">
                <xsl:call-template name="navitem">
                    <xsl:with-param name="href">
                        /community-voices/tags
                    </xsl:with-param>
                    <xsl:with-param name="name">
                        Tags
                    </xsl:with-param>
                    <xsl:with-param name="active">
                        <xsl:value-of select="$active" />
                    </xsl:with-param>
                </xsl:call-template>
          </xsl:if>
          </ul>
          <div style="margin-left:auto">
              <xsl:choose>
                  <xsl:when test="$rightButtons != ''">
                      <xsl:copy-of select="$rightButtons" />
                  </xsl:when>
                  <xsl:otherwise>
                      <xsl:call-template name="userButtons" />
                  </xsl:otherwise>
              </xsl:choose>
          </div>
        </nav>
    </xsl:template>

</xsl:stylesheet>

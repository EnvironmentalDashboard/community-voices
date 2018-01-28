<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:template name="common-header">
        <header>

          <nav>
              <ul>
                  <li><a href="./">Home</a></li>
                  <li><a href="./quotes">Quotes</a></li>
                  <li>
                    <xsl:choose>
                        <xsl:when test="identity/user/id &gt; 0">
                            Welcome, <xsl:value-of select="identity/user/firstName" />!
                            <a href="./logout">Logout</a>
                        </xsl:when>
                        <xsl:otherwise>
                            <a href="./login">Login</a>
                        </xsl:otherwise>
                    </xsl:choose>
                  </li>
              </ul>
          </nav>
        </header>
    </xsl:template>

    <xsl:template name="common-footer">
        <footer>
            Some rights reserved
        </footer>
    </xsl:template>

</xsl:stylesheet>

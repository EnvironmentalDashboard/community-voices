<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:template name="common-header">
        <header>

          <nav>
              <ul>
                  <li><a href="http://localhost:8888/community-voices/">Home</a></li>
                  <li><a href="http://localhost:8888/community-voices/quotes">Quotes</a></li>
                  <li>
                    <xsl:choose>
                        <xsl:when test="identity/user/id &gt; 0">
                            Welcome, <xsl:value-of select="identity/user/firstName" />!
                            <a href="http://localhost:8888/community-voices/logout">Logout</a>
                        </xsl:when>
                        <xsl:otherwise>
                            <a href="http://localhost:8888/community-voices/login">Login</a>
                        </xsl:otherwise>
                    </xsl:choose>
                  </li>
              </ul>
          </nav>
        </header>
    </xsl:template>

    <xsl:template name="common-footer">
        <footer>
            Some rights reserved<br/>
            Environmental Dasbhoard
        </footer>
    </xsl:template>

</xsl:stylesheet>

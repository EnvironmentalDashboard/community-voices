<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:template name="common-header">
        <header>

          <h1>Community Voices</h1>

          <nav>
              <ul>
                  <li><a href="http://localhost:8888/community-voices/landing">Home</a></li>
                  <li><a href="http://localhost:8888/community-voices/slides">Slides</a></li>
                  <li><a href="http://localhost:8888/community-voices/images">Images</a></li>
                  <li><a href="http://localhost:8888/community-voices/quotes">Quotes</a></li>
                    <xsl:choose>
                        <xsl:when test="identity/user/id &gt; 0">
                            <li>
                                Welcome, <xsl:value-of select="identity/user/firstName" />!
                                <ul>
                                    <li><a href="http://localhost:8888/community-voices/logout">Logout</a></li>
                                </ul>
                            </li>
                        </xsl:when>
                        <xsl:otherwise>
                            <li><a href="http://localhost:8888/community-voices/login">Login</a></li>
                        </xsl:otherwise>
                    </xsl:choose>
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

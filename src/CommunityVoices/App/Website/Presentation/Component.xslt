<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:template name="common-header">
        <header>

          <h1>Community Voices</h1>

          <nav>
              <ul>
                  <li><a href="{baseUrl}landing">Home</a></li>
                  <li><a href="{baseUrl}slides">Slides</a></li>
                  <li><a href="{baseUrl}images">Images</a></li>
                  <li><a href="{baseUrl}quotes">Quotes</a></li>
                    <xsl:choose>
                        <xsl:when test="identity/user/id &gt; 0">
                            <li>
                                Welcome, <xsl:value-of select="identity/user/firstName" />!
                                <ul>
                                    <li><a href="{baseUrl}logout">Logout</a></li>
                                    <li>
                                      <a>
                                        <xsl:attribute name="href">user/<xsl:value-of select="identity/user/id" /></xsl:attribute>
                                        View Account
                                      </a>
                                    </li>
                                </ul>
                            </li>
                        </xsl:when>
                        <xsl:otherwise>
                            <li><a href="{baseUrl}login">Login</a></li>
                            <li><a href="{baseUrl}register">Register</a></li>
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

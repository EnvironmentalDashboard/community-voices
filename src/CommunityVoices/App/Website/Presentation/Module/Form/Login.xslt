<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:output method="xml" indent="yes" omit-xml-declaration="yes" />

    <xsl:template match="/form">
        <xsl:if test="@failure">
            <p>Incorrect user/pass combination.</p>
        </xsl:if>

        <form action='./login/authenticate' method='post'>
            Email:
            <xsl:choose>
                <xsl:when test="@email-value">
                    <input type='input' name='email' value='{@email-value}'/>
                </xsl:when>
                <xsl:otherwise>
                    <input type='input' name='email'/>
                </xsl:otherwise>
            </xsl:choose>
            <br/>

            Password: <input type='password' name='password'/>

            <xsl:choose>
                <xsl:when test="@remember-value &gt; 0">
                    <input type='checkbox' name='remember' checked='{@remember-value}'/>
                </xsl:when>
                <xsl:otherwise>
                    <input type='checkbox' name='remember' />
                </xsl:otherwise>
            </xsl:choose>

            <input type='submit'/>
        </form>
    </xsl:template>

</xsl:stylesheet>

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:output method="xml" indent="yes" omit-xml-declaration="yes" />

    <xsl:template match="/form">
        <xsl:if test="@failure">
            <p>Incorrect user/pass combination.</p>
        </xsl:if>

        <form action='./authenticate' method='post'>
            Email: <input type='input' name='email'/> <br/>
            Password: <input type='password' name='password'/><input type='checkbox' name='remember'/><input type='submit'/>
        </form>
    </xsl:template>

</xsl:stylesheet>

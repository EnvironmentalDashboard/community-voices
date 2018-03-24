<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:output method="xml" indent="yes" omit-xml-declaration="yes" />

    <xsl:template match="/form">

        <form action='./register/authenticate' method='post'>
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

            Password: <input type='password' name='password'/><br/>
            Confirm Password: <input type='password' name='confirmPassword' /><br />

            First Name: <input type='input' name='firstName' /><br />
            Last Name: <input type='input' name='lastName' /><br />

            <input type='submit'/>
        </form>
    </xsl:template>

</xsl:stylesheet>

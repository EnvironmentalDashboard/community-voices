<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

    <xsl:template match="/form">
        <div class="row" style="padding:15px;">
            <div class="col-12">
                <xsl:if test="@failure">
                    <p>Label missing.</p>
                </xsl:if>

                <form method='post' style="max-width:400px;margin: 0 auto" action="edit">

                    <div class="form-group">
                        <label for="label">Label</label>
                        <input type="text" name="label" id="label" class="form-control">
                            <xsl:if test="domain/contentCategory != ''">
                                <xsl:attribute name="value">
                                    <xsl:value-of select="domain/contentCategory/label" />
                                </xsl:attribute>
                            </xsl:if>
                        </input>
                    </div>

                    <div class="form-group">
                        <label for="color">Color</label>
                        <input type="color" name="color" id="color" class="form-control">
                            <xsl:if test="domain/contentCategory != ''">
                                <xsl:attribute name="value">
                                    <xsl:value-of select="domain/contentCategory/color" />
                                </xsl:attribute>
                            </xsl:if>
                        </input>
                    </div>

                    <input type='submit' class='btn btn-primary' />
                </form>
            </div>
        </div>
    </xsl:template>

</xsl:stylesheet>

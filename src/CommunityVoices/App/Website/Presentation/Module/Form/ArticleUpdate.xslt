<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

    <xsl:template match="/form">
        <div class="row" style="padding:15px;">
            <div class="col-12">
                <xsl:if test="@failure">
                    <p>Author missing.</p>
                </xsl:if>

                <form method='post' style="max-width:400px;margin: 0 auto" action="edit/authenticate">

                    <div class="form-group">
                        <label for="text">Enter article below</label>
                        <textarea name="text" id="text" cols="25" rows="20" class="form-control">
                            <xsl:value-of select="domain/article/text"/>
                        </textarea>
                      </div>

                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type='text' name='title' id='title' class='form-control'>
                            <xsl:attribute name="value"><xsl:value-of select="domain/article/title"/></xsl:attribute>
                        </input>
                    </div>

                    <div class="form-group">
                        <label for="author">Author</label>
                        <input type='text' name='author' id='author' class='form-control'>
                            <xsl:attribute name="value"><xsl:value-of select="domain/article/author"/></xsl:attribute>
                        </input>
                    </div>

                    <div class="form-group">
                        <label for="dateRecorded">Date Recorded</label>
                        <input type='text' name='dateRecorded' id='dateRecorded' class='form-control'>
                            <xsl:attribute name="value"><xsl:value-of select="domain/article/dateRecorded"/></xsl:attribute>
                        </input>
                    </div>

                    <input type='hidden' name='id'>
                        <xsl:attribute name="value"><xsl:value-of select="domain/article/id"/></xsl:attribute>
                    </input>

                    <input type='submit' class='btn btn-primary' />
                </form>
            </div>
        </div>
    </xsl:template>

</xsl:stylesheet>

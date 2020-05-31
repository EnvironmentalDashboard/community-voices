<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

    <xsl:template match="/form">
        <div class="row" style="padding:15px;">
            <div class="col-12">
                <xsl:if test="@failure">
                    <p>Label missing.</p>
                </xsl:if>

                <form method='post' id="ccform" style="max-width:400px;margin: 0 auto" enctype='multipart/form-data'>
                    <xsl:attribute name="action">
                        <xsl:choose>
                            <xsl:when test="domain/contentCategory != ''">
                                <xsl:text>/community-voices/api/content-categories/</xsl:text><xsl:value-of select="domain/contentCategory/id" /><xsl:text>/edit</xsl:text>
                            </xsl:when>
                            <xsl:otherwise>
                                <xsl:text>/community-voices/api/content-categories/new</xsl:text>
                            </xsl:otherwise>
                        </xsl:choose>
                    </xsl:attribute>

                    <div class="custom-file">
                      <label for="file" class="custom-file-label">Image</label>
                      <input class="custom-file-input" id="file" type='file' name='file' accept='.jpg, .jpeg, .png' />
                      <xsl:if test="domain/contentCategory/image != ''">
                          <p>Upload a file only if you would like to change the current image.</p>
                      </xsl:if>
                    </div>

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
                        <br />
                        <input type="color" name="color" id="color">
                            <xsl:if test="domain/contentCategory != ''">
                                <xsl:attribute name="value">
                                    <xsl:value-of select="domain/contentCategory/color" />
                                </xsl:attribute>
                            </xsl:if>
                        </input>
                    </div>
                </form>

                <xsl:if test="domain/contentCategory != ''">
                    <form action="/community-voices/content-categories/{domain/contentCategory/id}/delete" method="POST" style="max-width:400px;margin: 0 auto" id="delete-form">
                      <div id="alert"></div>
                    </form>
                </xsl:if>

                <div class="btn-group" role="group" aria-label="Content Category actions" style="max-width: 400px; margin: 0 auto; display: flex; flex-direction: row; justify-content: left">
                    <input type='submit' form="ccform" class='btn btn-primary' />
                    <xsl:if test="domain/contentCategory != ''">
                        <input type="submit" form="delete-form" value="Delete content category" class="btn btn-danger" onclick="return confirm('Are you sure?')" />
                    </xsl:if>
                </div>
            </div>
        </div>
    </xsl:template>

</xsl:stylesheet>

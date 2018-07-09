<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  version="1.0">

  <xsl:output method="xml" indent="yes" omit-xml-declaration="yes" />

  <xsl:variable name="isManager" select="package/identity/user/role = 'manager'
    or package/identity/user/role = 'administrator'"/>

  <xsl:template match="/package">
    <div class="row" style="padding:15px;">
      <div class="col-12">

          <xsl:for-each select="domain/articleCollection/article">

            <xsl:if test="$isManager or status = 'approved'">

              <ul class="list-unstyled">
                <li class="media">
                  <img class="mr-3" src="https://environmentaldashboard.org/cv/uploads/{image}" alt="{title}" style="max-width:200px" />
                  <div class="media-body">
                    <h5 class="mt-0 mb-1"><xsl:value-of select='title' /> &#160;&#160;&#160;<small class="text-muted"><xsl:value-of select='author' /></small></h5>
                    <p><a class="btn btn-primary" href='articles/{id}'>Read more</a></p>
                  </div>
                </li>
              </ul>

            </xsl:if>

          </xsl:for-each>

      </div>
    </div>

  </xsl:template>

</xsl:stylesheet>

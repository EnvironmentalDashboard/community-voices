<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  version="1.0">

  <xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

  <xsl:variable name="isManager" select="package/identity/user/role = 'manager'
    or package/identity/user/role = 'administrator'"/>

    <xsl:template match="/package">
      <nav class="navbar navbar-light bg-light">
        <div class="float-left">
          <a class="navbar-brand" href="#">Images</a>
          <a class="btn btn-outline-primary" href="new">+ Add image</a>
        </div>
        <div class="btn-group float-right">
          <xsl:choose>
            <xsl:when test="number(domain/prevId) = domain/prevId">
              <a class="btn btn-outline-primary btn-sm" href="/cv/images/{domain/prevId}">&#171; Previous image</a>
            </xsl:when>
            <xsl:otherwise>
              <a href="#" class="btn btn-outline-primary disabled btn-sm" disabled="disabled">&#171; Previous image</a>
            </xsl:otherwise>
          </xsl:choose>
          <xsl:choose>
            <xsl:when test="number(domain/nextId) = domain/nextId">
              <a class="btn btn-outline-primary btn-sm" href="/cv/images/{domain/nextId}">Next image &#187;</a>
            </xsl:when>
            <xsl:otherwise>
              <a class="btn btn-outline-primary disabled btn-sm" href="#" disabled="disabled">Next image &#187;</a>
            </xsl:otherwise>
          </xsl:choose>
        </div>
      </nav>
      <xsl:choose><xsl:when test="$isManager or domain/image/status = 'approved'">
      <div class="row" style="padding:15px;">
        <div class="col-sm-8">
          <div class="card mb-3">
            <img class="card-img-top">
              <xsl:attribute name="src">https://environmentaldashboard.org/cv/uploads/<xsl:value-of select='domain/image/id' /></xsl:attribute>
              <xsl:attribute name="alt"><xsl:value-of select='domain/image/title' /></xsl:attribute>
            </img>
            <div class="card-body">
              <h3 class="card-title"><xsl:value-of select='domain/image/title' /></h3>
              <h6 class="card-subtitle mb-2 text-muted">
                <xsl:value-of select='domain/image/photographer' />
                <xsl:if test="domain/image/organization != ''">, <xsl:value-of select='domain/image/organization' /></xsl:if>
              </h6>
              <p class="card-text"><xsl:value-of select='domain/image/description' /></p>
              <p class="card-text"><small class="text-muted">Created <xsl:value-of select='domain/image/dateCreated' /></small></p>
            </div>
            <xsl:if test="$isManager">
              <div class="card-footer text-muted">
                <p class='mt-0 mb-0'>
                  Uploader:
                  <xsl:value-of select='domain/image/addedBy/user/firstName' />
                  <xsl:text> </xsl:text>
                  <xsl:value-of select='domain/image/addedBy/user/lastName' />
                </p>
                <p class='mt-0 mb-0'>
                  Status:
                  <xsl:value-of select='domain/image/status' />
                </p>
                <p class='mt-0 mb-0'>
                  <xsl:for-each select="domain/image/tagCollection/groupCollection/group">
                    <xsl:value-of select="label"></xsl:value-of>, 
                  </xsl:for-each>
                </p>
                <p class='mt-0 mb-0'>
                  <a>
                    <xsl:attribute name="href">./images/<xsl:value-of select='domain/image/id'/>/edit</xsl:attribute>
                    Edit
                  </a>
                </p>
              </div>
            </xsl:if>
          </div>
        </div>
        <div class="col-sm-4">
          <xsl:choose>
            <xsl:when test="domain/slideId != ''">
              <h4>Content featuring this image</h4>
              <p><iframe src="http://environmentaldashboard.org/cv/slides/{domain/slideId}" class="img-fluid" alt=""></iframe></p>
            </xsl:when>
            <xsl:otherwise>
              <p>This image is not used in any slides</p>
              <p><a href="/cv/slides/new?prefill_image={domain/image/id}" class="btn btn-primary btn-block">Create one</a></p>
            </xsl:otherwise>
          </xsl:choose>
        </div>
      </div>

    </xsl:when>
      <xsl:otherwise>
        <p class="text-center">Unauthorized Content</p>
      </xsl:otherwise>

    </xsl:choose>
  </xsl:template>

</xsl:stylesheet>

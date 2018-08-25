<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  version="1.0">

  <xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

  <xsl:variable name="isManager" select="package/identity/user/role = 'manager'
    or package/identity/user/role = 'administrator'"/>

    <xsl:template match="/package">
      <nav class="navbar navbar-light bg-light" style="justify-content:initial">
        <a class="navbar-brand" href="/community-voices/" style="color:#28a745;font-family:'Multicolore',sans-serif">Community Voices</a>
        <div class="float-left">
          <ul class="navbar-nav" style="width:initial">
            <li class="nav-item mr-2">
              <a class="nav-link" href="./articles">Articles</a>
            </li>
            <li class="nav-item mr-2">
              <a class="nav-link" href="./slides">Slides</a>
            </li>
            <li class="nav-item mr-2 active">
              <a class="nav-link" href="./images">Images <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item mr-2">
              <a class="nav-link" href="./quotes">Quotes</a>
            </li>
          </ul>
        </div>
        <xsl:choose>
          <xsl:when test="$isManager">
            <a class="btn btn-outline-primary btn-sm mr-2" href="new" style="margin-left:auto">+ Add image</a>
          </xsl:when>
          <xsl:otherwise>
            <div style="margin-left:auto"></div>
          </xsl:otherwise>
        </xsl:choose>
        <div class="btn-group float-right">
          <xsl:choose>
            <xsl:when test="number(domain/prevId) = domain/prevId">
              <a class="btn btn-outline-primary btn-sm" href="/community-voices/images/{domain/prevId}">&#171; Previous image</a>
            </xsl:when>
            <xsl:otherwise>
              <a href="#" class="btn btn-outline-primary disabled btn-sm" disabled="disabled">&#171; Previous image</a>
            </xsl:otherwise>
          </xsl:choose>
          <xsl:choose>
            <xsl:when test="number(domain/nextId) = domain/nextId">
              <a class="btn btn-outline-primary btn-sm" href="/community-voices/images/{domain/nextId}">Next image &#187;</a>
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
              <xsl:attribute name="src">https://environmentaldashboard.org/community-voices/uploads/<xsl:value-of select='domain/image/id' /></xsl:attribute>
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
              <p class="card-text">
                <xsl:for-each select="domain/image/tagCollection/groupCollection/group">
                  <span class="badge badge-primary mr-1"><xsl:value-of select="label"></xsl:value-of></span>
                </xsl:for-each>
              </p>
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
                    <xsl:attribute name="href">./<xsl:value-of select='domain/image/id'/>/edit</xsl:attribute>
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
              <a href='https://environmentaldashboard.org/community-voices/slides/{domain/slideId}'>
                <div class="embed-responsive embed-responsive-16by9 mb-4">
                  <iframe class="embed-responsive-item" style="pointer-events: none;" src="https://environmentaldashboard.org/community-voices/slides/{domain/slideId}"></iframe>
                </div>
              </a>
              <p>
                <form action="{domain/image/id}/unpair/{domain/slideId}" method="POST">
                  <input type="submit" value="Unpair image from slide" class="btn btn-danger btn-sm btn-block" />
                </form>
              </p>
            </xsl:when>
            <xsl:otherwise>
              <p>This image is not used in any slides</p>
              <p><a href="/community-voices/slides/new?prefill_image={domain/image/id}" class="btn btn-primary btn-block">Create one</a></p>
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

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  version="1.0">

  <xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

  <xsl:variable name="isManager" select="package/identity/user/role = 'manager'
    or package/identity/user/role = 'administrator'"/>
  <xsl:variable name="search" select="package/domain/search"/>
  <xsl:variable name="tags" select="package/domain/tags"/>
  <xsl:variable name="authors" select="package/domain/authors"/>
  <xsl:variable name="order" select="package/domain/order"/>

  <xsl:template match="/package">
    <nav class="navbar navbar-light bg-light" style="justify-content:initial">
      <a class="navbar-brand" href="/cv/" style="color:#28a745;font-family:'Multicolore',sans-serif">Community Voices</a>
      <ul class="navbar-nav" style="width:initial">
        <li class="nav-item mr-2 active">
          <a class="nav-link" href="./articles">Articles <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item mr-2">
          <a class="nav-link" href="./slides">Slides</a>
        </li>
        <li class="nav-item mr-2">
          <a class="nav-link" href="./images">Images</a>
        </li>
        <li class="nav-item mr-2">
          <a class="nav-link" href="./quotes">Quotes</a>
        </li>
      </ul>
      <xsl:if test="$isManager">
        <a class="btn btn-outline-primary" href="./articles/new" style="margin-left:auto">+ Add article</a>
      </xsl:if>
    </nav>

    <div class="row" style="padding:15px;">
      <div class="col-sm-3">
        <div class="card bg-light mb-3">
          <form action="" method="GET">
            <div class="card-body">
              <div class="form-group">
                <label for="search">Search</label>
                <input type="text" class="form-control" name="search" id="search" placeholder="Enter search terms" value="{$search}" />
              </div>
              <div class="form-group">
                <p class="mb-0">Tags</p>
                <div style="overflow-y:scroll;width:100%;height: 145px;border:none" id='sorted-tags'>
                  <xsl:for-each select="domain/groupCollection/group">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="tags[]" id="tag{id}">
                        <xsl:if test="contains($tags, concat(',', id, ','))">
                          <xsl:attribute name="checked">checked</xsl:attribute>
                        </xsl:if>
                        <xsl:attribute name="value"><xsl:value-of select='id' /></xsl:attribute>
                      </input>
                      <label class="form-check-label">
                        <xsl:attribute name="for">tag<xsl:value-of select='id' /></xsl:attribute>
                        <xsl:value-of select="label"></xsl:value-of>
                      </label>
                    </div>
                  </xsl:for-each>
                </div>
              </div>
              <div class="form-group">
                <p class="mb-0">Author</p>
                <div style="overflow-y:scroll;width:100%;height: 145px;border:none" id='sorted-authors'>
                  <xsl:for-each select="domain/authorCollection/author">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="authors[]">
                        <xsl:attribute name="id">author<xsl:value-of select="position()"></xsl:value-of></xsl:attribute>
                        <xsl:if test="contains($authors, concat(',', ., ','))">
                          <xsl:attribute name="checked">checked</xsl:attribute>
                        </xsl:if>
                        <xsl:attribute name="value"><xsl:value-of select='.' /></xsl:attribute>
                      </input>
                      <label class="form-check-label">
                        <xsl:attribute name="for">author<xsl:value-of select='position()' /></xsl:attribute>
                        <xsl:value-of select="."></xsl:value-of>
                      </label>
                    </div>
                  </xsl:for-each>
                </div>
              </div>
              <div class="form-group">
                <label for="order">Order by</label>
                <select class="form-control" id="order" name="order">
                  <option value="date_recorded_desc">
                    <xsl:if test="$order = 'date_recorded_desc'">
                      <xsl:attribute name="selected">selected</xsl:attribute>
                    </xsl:if>
                    Newest first
                  </option>
                  <option value="date_recorded_asc">
                    <xsl:if test="$order = 'date_recorded_asc'">
                      <xsl:attribute name="selected">selected</xsl:attribute>
                    </xsl:if>
                    Oldest first
                  </option>
                  <option value="photographer_desc">
                    <xsl:if test="$order = 'author_desc'">
                      <xsl:attribute name="selected">selected</xsl:attribute>
                    </xsl:if>
                    Author
                  </option>
                </select>
              </div>
            </div>
            <div class="card-footer bg-transparent"><button type="button" onclick="this.parentNode.parentNode.reset()" class="btn btn-secondary mr-2">Reset</button> <button type="submit" class="btn btn-primary">Search</button></div>
          </form>
        </div>
      </div>
      <div class="col-sm-9">

          <xsl:for-each select="domain/articleCollection/article">

            <xsl:if test="$isManager or status = 'approved'">

              <ul class="list-unstyled">
                <li class="media">
                  <img class="mr-3" src="https://environmentaldashboard.org/cv/uploads/{image}" alt="{title}" style="width:200px" />
                  <div class="media-body">
                    <h5 class="mt-0 mb-1">
                      <xsl:value-of select='title' />
                      <xsl:if test="author != ''">
                        &#160;<small class="text-muted">Interviewed by <xsl:value-of select='author' /></small>
                      </xsl:if>
                    </h5>
                    <p><a class="btn btn-primary" href='articles/{id}'>Read more</a></p>
                  </div>
                </li>
              </ul>

            </xsl:if>

          </xsl:for-each>

      </div>
    </div>

    <div class="row" style="padding:15px;">
      <div class="col-12">
        <xsl:copy-of select="domain/div"></xsl:copy-of>
      </div>
    </div>

  </xsl:template>

</xsl:stylesheet>

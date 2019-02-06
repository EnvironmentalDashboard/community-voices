<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  version="1.0">

  <xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

  <xsl:variable name="isManager" select="package/identity/user/role = 'manager'
    or package/identity/user/role = 'administrator'"/>
  <xsl:variable name="search" select="package/domain/search"/>
  <xsl:variable name="status" select="package/domain/status"/>
  <xsl:variable name="tags" select="package/domain/tags"/>
  <xsl:variable name="attributions" select="package/domain/attributions"/>
  <xsl:variable name="subattributions" select="package/domain/subattributions"/>
  <xsl:variable name="order" select="package/domain/order"/>
  <xsl:variable name="unused" select="package/domain/unused"/>

  <xsl:template match="/package">

    <nav class="navbar navbar-light bg-light" style="justify-content:initial">
      <a class="navbar-brand" href="/community-voices/" style="color:#28a745;font-family:'Multicolore',sans-serif">Community Voices</a>
      <ul class="navbar-nav" style="width:initial">
        <li class="nav-item mr-2">
          <a class="nav-link" href="/community-voices/articles">Articles</a>
        </li>
        <li class="nav-item mr-2">
          <a class="nav-link" href="/community-voices/slides">Slides</a>
        </li>
        <li class="nav-item mr-2">
          <a class="nav-link" href="/community-voices/images">Images</a>
        </li>
        <li class="nav-item mr-2 active">
          <a class="nav-link" href="/community-voices/quotes">Quotes <span class="sr-only">(current)</span></a>
        </li>
      </ul>
      <div style="margin-left:auto">
        <xsl:choose>
          <xsl:when test="identity/user/id &gt; 0">
            <a class="btn btn-outline-primary mr-2" href="/community-voices/logout">Logout <xsl:value-of select="identity/user/firstName" /></a>
            <!-- <a>
              <xsl:attribute name="href">user/<xsl:value-of select="identity/user/id" /></xsl:attribute>
              View Account
            </a> -->
          </xsl:when>
          <xsl:otherwise>
            <div class="btn-group">
              <a class="btn btn-outline-primary" href="/community-voices/login">Login</a>
              <a class="btn btn-outline-primary" href="/community-voices/register">Register</a>
            </div>
          </xsl:otherwise>
        </xsl:choose>
        <xsl:if test="$isManager">
          <a class="btn btn-outline-primary" href="/community-voices/quotes/new">+ Add quote</a>
        </xsl:if>
      </div>
    </nav>

    <div class="row" style="padding:15px;">
      <div class="col-sm-3">
        <div class="card bg-light mb-3">
          <form action="" method="GET">
            <div class="card-header bg-transparent">
              <button type="button" onclick="this.parentNode.parentNode.reset()" class="btn btn-secondary mr-2">Reset</button>
              <button class="btn btn-primary" type="submit">Search</button>
            </div>
            <div class="card-body">
              <div class="form-group">
                <label for="search">Search</label>
                <input type="text" class="form-control" name="search" id="search" placeholder="Enter search terms" value="{$search}" />
              </div>
              <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" id="unused" name="unused" value="1">
                  <xsl:if test="$unused = '1'">
                    <xsl:attribute name="checked">checked</xsl:attribute>
                  </xsl:if>
                </input>
                <label class="form-check-label" for="unused">
                  Show only unpaired quotes
                </label>
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
                <p class="mb-0">Attribution</p>
                <div style="overflow-y:scroll;width:100%;height: 145px;border:none" id='sorted-attribution'>
                  <xsl:for-each select="domain/attributionCollection/attribution">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="attributions[]">
                        <xsl:attribute name="id">attribution<xsl:value-of select="position()"></xsl:value-of></xsl:attribute>
                        <xsl:if test="contains($attributions, concat(',', ., ','))">
                          <xsl:attribute name="checked">checked</xsl:attribute>
                        </xsl:if>
                        <xsl:attribute name="value"><xsl:value-of select='.' /></xsl:attribute>
                      </input>
                      <label class="form-check-label">
                        <xsl:attribute name="for">attribution<xsl:value-of select='position()' /></xsl:attribute>
                        <xsl:value-of select="."></xsl:value-of>
                      </label>
                    </div>
                  </xsl:for-each>
                </div>
              </div>
              <div class="form-group">
                <p class="mb-0">Sub-attribution</p>
                <div style="overflow-y:scroll;width:100%;height: 145px;border:none" id='sorted-subattribution'>
                  <xsl:for-each select="domain/subattributionCollection/subattribution">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="attributions[]">
                        <xsl:attribute name="id">subattribution<xsl:value-of select="position()"></xsl:value-of></xsl:attribute>
                        <xsl:if test="contains($subattributions, concat(',', ., ','))">
                          <xsl:attribute name="checked">checked</xsl:attribute>
                        </xsl:if>
                        <xsl:attribute name="value"><xsl:value-of select='.' /></xsl:attribute>
                      </input>
                      <label class="form-check-label">
                        <xsl:attribute name="for">subattribution<xsl:value-of select='position()' /></xsl:attribute>
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
                    <xsl:if test="$order = 'attribution_desc'">
                      <xsl:attribute name="selected">selected</xsl:attribute>
                    </xsl:if>
                    Attribution
                  </option>
                </select>
              </div>
              <xsl:if test="$isManager">
                <div class="form-group">
                  <label for="status">Status</label>
                  <select class="form-control" id="status" name="status">
                    <option value="approved,pending,rejected">
                      <xsl:if test="$status = ',approved,pending,rejected,'">
                        <xsl:attribute name="selected">selected</xsl:attribute>
                      </xsl:if>
                      All
                    </option>
                    <option value="approved">
                      <xsl:if test="$status = ',approved,'">
                        <xsl:attribute name="selected">selected</xsl:attribute>
                      </xsl:if>
                      Approved
                    </option>
                    <option value="pending">
                      <xsl:if test="$status = ',pending,'">
                        <xsl:attribute name="selected">selected</xsl:attribute>
                      </xsl:if>
                      Pending
                    </option>
                    <option value="rejected">
                      <xsl:if test="$status = ',rejected,'">
                        <xsl:attribute name="selected">selected</xsl:attribute>
                      </xsl:if>
                      Rejected
                    </option>
                  </select>
                </div>
              </xsl:if>
            </div>
            <div class="card-footer bg-transparent"><button type="button" onclick="this.parentNode.parentNode.reset()" class="btn btn-secondary mr-2">Reset</button> <button type="submit" class="btn btn-primary">Search</button></div>
          </form>
        </div>
      </div>
      <div class="col-sm-9">
        <div class="card">
          <div class="card-header">Quotes</div>
          <ul class="list-group list-group-flush">

            <xsl:for-each select="domain/quoteCollection/quote">
              <xsl:if test="$isManager or status = 'approved'">

                <li class="list-group-item">
                  <xsl:choose>
                    <xsl:when test="$isManager">
                        <blockquote class="blockquote mb-0">
                          <p contenteditable="true" id="text{id}"><xsl:value-of select="text"></xsl:value-of></p>
                          <footer class="blockquote-footer">
                            <xsl:value-of select="attribution"></xsl:value-of>
                            <xsl:if test="subAttribution != '' and attribution != subAttribution">
                              <xsl:if test="attribution != ''">, </xsl:if>
                              <xsl:value-of select='subAttribution'></xsl:value-of>
                            </xsl:if>
                          </footer>
                        </blockquote>
                      <div class="mt-2">
                        <a class="btn btn-outline-primary btn-sm d-inline mr-2 save-quote-changes" href="#" data-id="{id}">Save changes</a>
                        <a class="btn btn-outline-secondary btn-sm d-inline mr-2" href="quotes/{id}/edit">Edit meta data</a>
                        <xsl:choose>
                          <xsl:when test="relatedSlide = ''">
                            <a data-action="quotes/{id}/delete/authenticate" class="btn btn-outline-danger btn-sm d-inline delete-btn" href="#">Delete quote</a>
                          </xsl:when>
                          <xsl:otherwise>
                            <a data-action="quotes/{id}/unpair/{relatedSlide}" class="btn btn-outline-warning btn-sm d-inline unpair-btn" href="#">Unpair slide</a>
                          </xsl:otherwise>
                        </xsl:choose>
                      </div>
                    </xsl:when>
                    <xsl:otherwise>
                      <a href='quotes/{id}' style="color: inherit; text-decoration: inherit;">
                        <blockquote class="blockquote mb-0">
                          <p><xsl:value-of select="text"></xsl:value-of></p>
                          <footer class="blockquote-footer">
                            <xsl:value-of select="attribution"></xsl:value-of>
                            <xsl:if test="subAttribution != '' and attribution != subAttribution">
                              <xsl:if test="attribution != ''">, </xsl:if>
                              <xsl:value-of select='subAttribution'></xsl:value-of>
                            </xsl:if>
                          </footer>
                        </blockquote>
                      </a>
                    </xsl:otherwise>
                  </xsl:choose>
                </li>

              </xsl:if>
            </xsl:for-each>
          </ul>
        </div>
      </div>
    </div>
    <div class="row" style="padding:15px;">
      <div class="col-12">
        <!-- <xsl:value-of select="domain/count"></xsl:value-of> -->
        <xsl:copy-of select="domain/div"></xsl:copy-of>
      </div>
    </div>

  </xsl:template>

</xsl:stylesheet>

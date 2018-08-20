<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">
    <xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

    <xsl:variable name="search" select="package/domain/search"/>
    <xsl:variable name="quotetags" select="package/domain/quotetags"/>
    <xsl:variable name="imagetags" select="package/domain/imagetags"/>
    <xsl:variable name="photographers" select="package/domain/photographers"/>
    <xsl:variable name="orgs" select="package/domain/orgs"/>
    <xsl:variable name="attributions" select="package/domain/attributions"/>

    <xsl:template match="/form">
      <nav class="navbar navbar-light bg-light" style="justify-content:initial">
        <a class="navbar-brand" href="/cv/" style="color:#28a745;font-family:'Multicolore',sans-serif">Community Voices</a>
        <ul class="navbar-nav" style="width:initial">
          <li class="nav-item mr-2">
            <a class="nav-link" href="/cv/articles">Articles</a>
          </li>
          <li class="nav-item mr-2 active">
            <a class="nav-link" href="/cv/slides">Slides <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item mr-2">
            <a class="nav-link" href="/cv/images">Images</a>
          </li>
          <li class="nav-item mr-2">
            <a class="nav-link" href="/cv/quotes">Quotes</a>
          </li>
        </ul>
      </nav>
      <div class="row" style="padding:15px;">
        <div class="col-12">
        <h2 class="mb-4">Create a slide</h2>
        <div class="row">
          <div class="col-sm-3">
            <ul class="nav flex-column nav-pills mb-4">
              <li class="nav-item">
                <a class="nav-link active" href="#" id="quote-btn">Select a quote</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#" id="img-btn">Select an image</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#" id="cc-btn">Select a content category</a>
              </li>
            </ul>
            <div class="card bg-light mb-3">
              <div class="card-header bg-transparent">Search Quotes</div>
              <form class="p-2" action="" method="GET" id="filter-quotes">
                <div class="form-group">
                  <input type="text" class="form-control" id="search-quotes" placeholder="Search" />
                </div>

                <div class="form-group">
                  <p class="mb-0">Tags</p>
                  <div style="overflow-y:scroll;width:100%;height: 145px;border:none">
                    <xsl:for-each select="domain/groupCollection/group">
                      <div class="form-check">
                        <input class="form-check-input qtag-check" type="checkbox" name="quotetags[]" id="quotetag{id}">
                          <xsl:if test="contains($quotetags, concat(',', id, ','))">
                            <xsl:attribute name="checked">checked</xsl:attribute>
                          </xsl:if>
                          <xsl:attribute name="value"><xsl:value-of select='id' /></xsl:attribute>
                        </input>
                        <label class="form-check-label">
                          <xsl:attribute name="for">quotetag<xsl:value-of select='id' /></xsl:attribute>
                          <xsl:value-of select="label"></xsl:value-of>
                        </label>
                      </div>
                    </xsl:for-each>
                  </div>
                </div>

                <div class="form-group">
                  <p class="mb-0">Attribution</p>
                  <div style="overflow-y:scroll;width:100%;height: 145px;border:none">
                    <xsl:for-each select="domain/attributionCollection/attribution">
                      <div class="form-check">
                        <input class="form-check-input attr-check" type="checkbox" name="attributions[]">
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

                <button type="submit" class="btn btn-primary mb-2">Search</button>
              </form>
            </div>
            <div class="card bg-light mb-3" style="display:none">
              <div class="card-header bg-transparent">Search Images</div>
              <form class="p-2" action="" method="GET" id="filter-images">
                <div class="form-group">
                  <input type="text" class="form-control mb-2 mr-sm-2" id="search-images" placeholder="Search" />
                </div>

                <div class="form-group">
                  <p class="mb-0">Tags</p>
                  <div style="overflow-y:scroll;width:100%;height: 145px;border:none">
                    <xsl:for-each select="domain/groupCollection/group">
                      <div class="form-check">
                        <input class="form-check-input itag-check" type="checkbox" name="imagetags[]" id="imagetag{id}">
                          <xsl:if test="contains($imagetags, concat(',', id, ','))">
                            <xsl:attribute name="checked">checked</xsl:attribute>
                          </xsl:if>
                          <xsl:attribute name="value"><xsl:value-of select='id' /></xsl:attribute>
                        </input>
                        <label class="form-check-label">
                          <xsl:attribute name="for">imagetag<xsl:value-of select='id' /></xsl:attribute>
                          <xsl:value-of select="label"></xsl:value-of>
                        </label>
                      </div>
                    </xsl:for-each>
                  </div>
                </div>

                <div class="form-group">
                  <p class="mb-0">Photographer</p>
                  <div style="overflow-y:scroll;width:100%;height: 145px;border:none">
                    <xsl:for-each select="domain/PhotographerCollection/photographer">
                      <div class="form-check">
                        <input class="form-check-input photo-check" type="checkbox" name="photographers[]">
                          <xsl:attribute name="id">photographer<xsl:value-of select="position()"></xsl:value-of></xsl:attribute>
                          <xsl:if test="contains($photographers, concat(',', ., ','))">
                            <xsl:attribute name="checked">checked</xsl:attribute>
                          </xsl:if>
                          <xsl:attribute name="value"><xsl:value-of select='.' /></xsl:attribute>
                        </input>
                        <label class="form-check-label">
                          <xsl:attribute name="for">photographer<xsl:value-of select='position()' /></xsl:attribute>
                          <xsl:value-of select="."></xsl:value-of>
                        </label>
                      </div>
                    </xsl:for-each>
                  </div>
                </div>

                <div class="form-group">
                  <p class="mb-0">Organization</p>
                  <div style="overflow-y:scroll;width:100%;height: 145px;border:none">
                    <xsl:for-each select="domain/OrgCollection/org">
                      <div class="form-check">
                        <input class="form-check-input org-check" type="checkbox" name="orgs[]">
                          <xsl:attribute name="id">org<xsl:value-of select="position()"></xsl:value-of></xsl:attribute>
                          <xsl:if test="contains($orgs, concat(',', ., ','))">
                            <xsl:attribute name="checked">checked</xsl:attribute>
                          </xsl:if>
                          <xsl:attribute name="value"><xsl:value-of select='.' /></xsl:attribute>
                        </input>
                        <label class="form-check-label">
                          <xsl:attribute name="for">org<xsl:value-of select='position()' /></xsl:attribute>
                          <xsl:value-of select="."></xsl:value-of>
                        </label>
                      </div>
                    </xsl:for-each>
                  </div>
                </div>

                <button type="submit" class="btn btn-primary mb-2">Search</button>
              </form>
            </div>
          </div>
          <div class="col-sm-9">
            <div class="mb-4"><div class="embed-responsive embed-responsive-16by9">
              <xsl:choose>
                <xsl:when test="domain/slide != ''">
                  <iframe class="embed-responsive-item" id="preview" src="https://environmentaldashboard.org/cv/slides/{domain/slide/id}"></iframe>
                </xsl:when>
                <xsl:otherwise>
                  <iframe class="embed-responsive-item" id="preview" src="https://environmentaldashboard.org/cv/slides/12"></iframe>
                </xsl:otherwise>
              </xsl:choose>
            </div></div>
            <div class="form-group">
              <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="crop" />
                <label class="custom-control-label" for="crop">Crop image</label>
              </div>
            </div>
            <div>
              <div id="ajax-quote" style="min-height:400px">
                <div class="selectables"></div>
                <p class="mt-2"><a id="prev-quote" href="" class="btn btn-sm btn-outline-primary">&#8592; Previous page</a> <a id="next-quote" href="" class="btn btn-sm btn-outline-primary float-right">Next page &#8594;</a></p>
              </div>
            </div>
            <div>
              <div style="display:none;min-height:400px" id="ajax-image">
                <div class="selectables"></div>
                <p class="mt-2"><a id="prev-image" href="" class="btn btn-sm btn-outline-primary">&#8592; Previous page</a> <a id="next-image" href="" class="btn btn-sm btn-outline-primary float-right">Next page &#8594;</a></p>
              </div>
            </div>
            <div><div style="display:none" id="content-categories">
              <div class="card-columns">
                <div class="card bg-dark text-white">
                  <img class="card-img" src="https://environmentaldashboard.org/cv_slides/categorybars/heritage.png" data-id="4" alt="Card image" />
                </div>
                <div class="card bg-dark text-white">
                  <img class="card-img" src="https://environmentaldashboard.org/cv_slides/categorybars/nature_photos.png" data-id="5" alt="Card image" />
                </div>
                <div class="card bg-dark text-white">
                  <img class="card-img" src="https://environmentaldashboard.org/cv_slides/categorybars/neighbors.png" data-id="6" alt="Card image" />
                </div>
                <div class="card bg-dark text-white">
                  <img class="card-img" src="https://environmentaldashboard.org/cv_slides/categorybars/next-generation.png" data-id="3" alt="Card image" />
                </div>
                <div class="card bg-dark text-white">
                  <img class="card-img" src="https://environmentaldashboard.org/cv_slides/categorybars/our-downtown.png" data-id="2" alt="Card image" />
                </div>
                <div class="card bg-dark text-white">
                  <img class="card-img" src="https://environmentaldashboard.org/cv_slides/categorybars/serving-our-community.png" data-id="1" alt="Card image" />
                </div>
              </div>
            </div></div>

            <!-- keep outside form because value not needed on server -->
            <xsl:if test="domain/slide != ''">
              <input type="hidden" id="slide_text" value="{domain/slide/quote/quote/text}"/>
              <input type="hidden" id="slide_attr" value="{domain/slide/quote/quote/attribution}"/>
              <input type="hidden" id="slide_image" value="{domain/slide/image/image/id}"/>
              <input type="hidden" id="slide_cc" value="{domain/slide/contentCategory/contentCategory/id}"/>
            </xsl:if>
            <form action='edit/authenticate' method='post' class="d-inline">
              <input type="hidden" id="crop_x" name="crop_x" value="0"/>
              <input type="hidden" id="crop_y" name="crop_y" value="0" />
              <input type="hidden" id="crop_width" name="crop_width" value="0" />
              <input type="hidden" id="crop_height" name="crop_height" value="0" />
              <input type="hidden" name="image_id" value="{domain/slide/image/image/id}" />
              <input type="hidden" name="quote_id" value="{domain/slide/quote/quote/id}"/>
              <input type="hidden" name="content_category" value="{domain/slide/contentCategory/contentCategory/id}"/>
              <xsl:choose>
                <xsl:when test="domain/slide != ''">
                  <div class="form-group">
                    <input type="text" name="probability" placeholder="Probability" class="form-control" value="{domain/slide/probability}" />
                  </div>
                  <div class="form-group">
                    <input type="text" name="decay_percent" placeholder="Decay percent" class="form-control" value="{domain/slide/decayPercent}" />
                  </div>
                  <div class="form-group">
                    <input type="text" name="decay_start" placeholder="Decay start" class="form-control" value="{domain/slide/decayStart}" />
                  </div>
                  <div class="form-group">
                    <input type="text" name="decay_end" placeholder="Decay end" class="form-control" value="{domain/slide/decayEnd}" />
                  </div>
                  <div class="form-group">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" class="custom-control-input" id="approve" name="approve" value="1">
                        <xsl:if test="domain/slide/status = 'approved'">
                          <xsl:attribute name="checked">checked</xsl:attribute>
                        </xsl:if>
                      </input>
                      <label class="custom-control-label" for="approve">Approve</label>
                    </div>
                  </div>
                  <p class='mb-0'>Select screens for display:</p>
                  <div class="form-group">
                    <xsl:variable name="selectedLocs" select="domain/selectedLocs" />
                    <xsl:for-each select="domain/locCollection/location">
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="screen{id}" name="screens[]" value="{id}">
                          <xsl:if test="contains($selectedLocs, concat(',', id, ','))">
                            <xsl:attribute name="checked">checked</xsl:attribute>
                          </xsl:if>
                        </input>
                        <label class="custom-control-label" for="screen{id}">
                          <xsl:value-of select="label" />
                        </label>
                      </div>
                    </xsl:for-each>
                  </div>
                  <input type='submit' value="Update slide" class="btn btn-primary" />
                </xsl:when>
                <xsl:otherwise>
                  <div class="form-group">
                    <input type="text" name="probability" placeholder="Probability" class="form-control" />
                  </div>
                  <div class="form-group">
                    <input type="text" name="decay_percent" placeholder="Decay percent" class="form-control" />
                  </div>
                  <div class="form-group">
                    <input type="text" name="decay_start" placeholder="Decay start" class="form-control" />
                  </div>
                  <div class="form-group">
                    <input type="text" name="decay_end" placeholder="Decay end" class="form-control" />
                  </div>
                  <p class='mb-0'>Select screens for display:</p>
                  <div class="form-group">
                    <xsl:for-each select="domain/locCollection/location">
                      <div class="custom-control custom-checkbox" data-end_use="{end_use}">
                        <input type="checkbox" class="custom-control-input" id="screen{id}" name="screens[]" value="{id}" checked="checked" />
                        <label class="custom-control-label" for="screen{id}">
                          <xsl:value-of select="label" />
                        </label>
                      </div>
                    </xsl:for-each>
                  </div>
                  <input type='submit' value="Create slide" class="btn btn-primary" />
                </xsl:otherwise>
              </xsl:choose>
            </form>
            <xsl:if test="domain/slide != ''">
              <form action="delete/authenticate" method="POST" class="d-inline" id="delete-form">
                <input type="submit" value="Delete slide" class="btn btn-danger" />
              </form>
            </xsl:if>

          </div>
        </div>
      </div>
    </div>
    </xsl:template>

</xsl:stylesheet>

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">
    <xsl:output method="xml" indent="yes" omit-xml-declaration="yes" />

    <xsl:variable name="search" select="package/domain/search"/>
    <xsl:variable name="quotetags" select="package/domain/quotetags"/>
    <xsl:variable name="imagetags" select="package/domain/imagetags"/>
    <xsl:variable name="photographers" select="package/domain/photographers"/>
    <xsl:variable name="orgs" select="package/domain/orgs"/>
    <xsl:variable name="attributions" select="package/domain/attributions"/>

    <xsl:template match="/form">
        <div class="row" style="padding:15px;">
          <div class="col-12">
        <style>
          /* Temporary CSS block until better location found */
          .selectables, #list-view, #gallery-view {cursor:pointer}
          .card-columns .card:hover {border-color:#21a7df}
        </style>
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
                  <div style="overflow-y:scroll;width:100%;height: 130px;border:none">
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
                  <div style="overflow-y:scroll;width:100%;height: 130px;border:none">
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
                  <div style="overflow-y:scroll;width:100%;height: 130px;border:none">
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
                  <div style="overflow-y:scroll;width:100%;height: 130px;border:none">
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
                  <div style="overflow-y:scroll;width:100%;height: 130px;border:none">
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

                <div class="form-check mb-2">
                  <input class="form-check-input" type="checkbox" id="image-unused" />
                  <label class="form-check-label" for="image-unused">
                    Show only unpaired images
                  </label>
                </div>
                <button type="submit" class="btn btn-primary mb-2">Search</button>
              </form>
            </div>
          </div>
          <div class="col-sm-9">
            <h2 style="margin-bottom:-10px">Preview</h2>
            <svg height="1080" width="1920" style="width:100%;height:auto" viewBox="0 0 100 50" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><rect width="100%" height="100%" style="fill:rgb(0,0,0)" /><g id="render"></g></svg>
            <div>
              <div id="ajax-quotes" style="min-height:400px">
                <div class="selectables"></div>
              </div>
              <p class="mt-2"><a id="prev-quote" href="" class="btn btn-sm btn-outline-primary">&#8592; Previous page</a> <a id="next-quote" href="" class="btn btn-sm btn-outline-primary float-right">Next page &#8594;</a></p>
            </div>
            <div>
              <div style="display:none;min-height:400px" id="ajax-images">
                <div class="selectables"></div>
              </div>
              <p class="mt-2"><a id="prev-image" href="" class="btn btn-sm btn-outline-primary">&#8592; Previous page</a> <a id="next-image" href="" class="btn btn-sm btn-outline-primary float-right">Next page &#8594;</a></p>
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

          </div>
        </div>
        
        <form action='./slides/new/authenticate' method='post'>
          <input type="hidden" name="image_id"/>
          <input type="hidden" name="quote_id"/>
          <input type="hidden" name="content_category"/>
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
          <input type='submit' value="Create slide" class="btn btn-primary" />
        </form>
      </div>
    </div>
    </xsl:template>

</xsl:stylesheet>

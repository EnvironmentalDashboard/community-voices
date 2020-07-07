<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

  <xsl:import href="../Component/Navbar.xslt" />
  <xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

  <xsl:variable name="isManager" select="package/identity/user/role = 'manager'
    or package/identity/user/role = 'administrator'" />

  <xsl:template name="carousel-selector">
    <xsl:param name="data-cc" />
    <xsl:param name="background-color" />
    <xsl:param name="label" />
    <xsl:param name="image-src" />

    <div style="display: flex; flex-direction: column; width: 130px" class="carousel-selection-flex-item">
        <div style="display: flex; justify-content: center; align-content: center; background-color: {$background-color}; border-radius: 10px; height: 105px; width: 130px">
            <img data-cc="{$data-cc}" src="{$image-src}" class="selector-img" style="cursor: pointer; margin: auto; max-width: 130px; max-height: 95px" />
        </div>

        <div style="text-align:center; font-weight:bold"><xsl:value-of select="$label" /></div>
    </div>
  </xsl:template>

  <xsl:template match="/package">

    <xsl:call-template name="navbar" />

    <div class="row pb-0" style="padding:15px;">
      <div class="col-12">
        <div id="carouselIndicators" class="carousel slide" data-ride="carousel" data-interval="7000">
          <div class="carousel-inner">
            <xsl:for-each select="domain/slideCollection/slide">
              <xsl:variable name="i" select="position()" />
              <xsl:choose>
                <xsl:when test="$i = 1">
                  <a href="/community-voices/slides/{id}/edit"><div class="carousel-item active">
                    <div class="embed-responsive embed-responsive-16by9 mb-4">
                      <iframe class="embed-responsive-item" id="slide{$i}" style="pointer-events: none;" src="/community-voices/slides/{id}"></iframe>
                    </div>
                </div></a>
                </xsl:when>
                <xsl:otherwise>
                  <div class="carousel-item">
                    <div class="embed-responsive embed-responsive-16by9 mb-4">
                      <iframe class="embed-responsive-item" id="slide{$i}" style="pointer-events: none;" src="/community-voices/slides/{id}"></iframe>
                    </div>
                  </div>
                </xsl:otherwise>
              </xsl:choose>
            </xsl:for-each>
        </div>
          <a class="carousel-control-prev" href="#carouselIndicators" role="button" data-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="sr-only">Previous</span>
            </a>
          <a class="carousel-control-next" href="#carouselIndicators" role="button" data-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="sr-only">Next</span>
            </a>
        </div>
      </div>
    </div>

    <div class="row" style="padding: 15px">
      <div style="display: flex; flex-wrap: wrap; padding: 0px 15px; width: 100%" id="carousel-selection-flex-container">
          <xsl:for-each select="domain/contentCategoryCollection/contentCategory">
            <xsl:call-template name="carousel-selector">
              <xsl:with-param name="data-cc">
                <xsl:value-of select="id" />
              </xsl:with-param>
              <xsl:with-param name="background-color">
                <xsl:value-of select="color" />
              </xsl:with-param>
              <xsl:with-param name="label">
                <xsl:value-of select="label" />
              </xsl:with-param>
              <xsl:with-param name="image-src">
                /community-voices/uploads/<xsl:value-of select="image/image/id" />
              </xsl:with-param>
            </xsl:call-template>
          </xsl:for-each>

          <xsl:comment>
            @config
          </xsl:comment>
          <xsl:call-template name="carousel-selector">
            <xsl:with-param name="data-cc">
              <xsl:text>rand</xsl:text>
            </xsl:with-param>
            <xsl:with-param name="background-color">
              #CA4D46
            </xsl:with-param>
            <xsl:with-param name="label">
              Random
            </xsl:with-param>
            <xsl:with-param name="image-src">
              <xsl:text>/community-voices/public/images/random_icon.svg</xsl:text>
            </xsl:with-param>
          </xsl:call-template>
      </div>
    </div>

    <div class="row mb-5" style="padding: 15px">
      <form action="/community-voices/slides" method="GET" style="width:100%;padding:15px" id="search-form">
        <h4 class="mb-2">Looking for more content?</h4>
        <div class="input-group input-group-lg">
          <input name="search" type="text" class="form-control" aria-label="Search Community Voices" placeholder="Search slides, images, or quotes" style="background: url(/community-voices/public/images/search.svg) no-repeat left 1rem center;background-size: 20px 20px;padding-left: 3rem" />
          <div class="input-group-append">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="dropdown-btn">Slides</button>
            <div class="dropdown-menu" id="searchables">
              <a class="dropdown-item" data-action="/community-voices/slides" href="#">Slides</a>
              <a class="dropdown-item" data-action="/community-voices/images" href="#">Images</a>
              <a class="dropdown-item" data-action="/community-voices/quotes" href="#">Quotes</a>
              <a class="dropdown-item" data-action="/community-voices/articles" href="#">Articles</a>
            </div>
          </div>
          <button type="submit" class="btn btn-outline-primary form-control" style="max-width:15%; min-width: 100px">Search</button>
        </div>
      </form>
    </div>



  </xsl:template>

</xsl:stylesheet>

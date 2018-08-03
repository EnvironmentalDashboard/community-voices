<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

  <xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

  <xsl:variable name="isManager" select="package/identity/user/role = 'manager'
    or package/identity/user/role = 'administrator'" />

  <xsl:template match="/package">

      <div class="row" style="padding:15px;">
        <div class="col-12">
          <div id="carouselIndicators" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
              <xsl:for-each select="domain/slideCollection/slide">
                <xsl:variable name="i" select="position()" />
                <xsl:choose>
                  <xsl:when test="$i = 1">
                    <div class="carousel-item active">
                      <div class="embed-responsive embed-responsive-16by9 mb-4">
                        <iframe class="embed-responsive-item" id="slide{$i}" style="pointer-events: none;" src="https://environmentaldashboard.org/cv/slides/{id}"></iframe>
                      </div>
                    </div>
                  </xsl:when>
                  <xsl:otherwise>
                    <div class="carousel-item">
                      <div class="embed-responsive embed-responsive-16by9 mb-4">
                        <iframe class="embed-responsive-item" id="slide{$i}" style="pointer-events: none;" src="https://environmentaldashboard.org/cv/slides/{id}"></iframe>
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
      
      <div class="row mb-3" style="padding: 15px">
        <div class="col"><img data-cc="1" class="img-fluid selector-img" src="https://environmentaldashboard.org/community-voices/slider-images/serving-our-community.png" style="cursor:pointer" /></div>
        <div class="col"><img data-cc="2" class="img-fluid selector-img" src="https://environmentaldashboard.org/community-voices/slider-images/our-downtown.png" style="cursor:pointer" /></div>
        <div class="col"><img data-cc="3" class="img-fluid selector-img" src="https://environmentaldashboard.org/community-voices/slider-images/next-generation.png" style="cursor:pointer" /></div>
        <div class="col"><img data-cc="6" class="img-fluid selector-img" src="https://environmentaldashboard.org/community-voices/slider-images/neighbors.png" style="cursor:pointer" /></div>
        <div class="col"><img data-cc="5" class="img-fluid selector-img" src="https://environmentaldashboard.org/community-voices/slider-images/nature_photos.png" style="cursor:pointer" /></div>
        <div class="col"><img data-cc="4" class="img-fluid selector-img" src="https://environmentaldashboard.org/community-voices/slider-images/heritage.png" style="cursor:pointer" /></div>
        <div class="col"><img data-cc="rand" class="img-fluid selector-img" src="https://environmentaldashboard.org/community-voices/slider-images/random.png" style="cursor:pointer" /></div>
      </div>

      <div class="row mb-5" style="padding: 15px">
        <form action="/cv/slides" method="GET" style="width:100%;padding:15px" id="search-form">
          <h4 class="mb-2">Looking for more content?</h4>
          <div class="input-group input-group-lg">
            <input name="search" type="text" class="form-control" aria-label="Search Community Voices" placeholder="Search slides, images, or quotes" style="background: url(https://environmentaldashboard.org/cv/public/search.svg) no-repeat left 1rem center;background-size: 20px 20px;padding-left: 3rem" />
            <div class="input-group-append">
              <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="dropdown-btn">Slides</button>
              <div class="dropdown-menu" id="searchables">
                <a class="dropdown-item" data-action="/cv/slides" href="#">Slides</a>
                <a class="dropdown-item" data-action="/cv/images" href="#">Images</a>
                <a class="dropdown-item" data-action="/cv/quotes" href="#">Quotes</a>
                <a class="dropdown-item" data-action="/cv/articles" href="#">Articles</a>
              </div>
            </div>
            <button type="submit" class="btn btn-outline-primary form-control" style="max-width:15%">Search</button>
          </div>
        </form>
      </div>



  </xsl:template>

</xsl:stylesheet>

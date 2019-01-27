<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

  <xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

  <xsl:variable name="isManager" select="package/identity/user/role = 'manager'
    or package/identity/user/role = 'administrator'" />

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
        <li class="nav-item mr-2">
          <a class="nav-link" href="/community-voices/quotes">Quotes</a>
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
      </div>
    </nav>

    <div class="row pb-0" style="padding:15px;">
      <div class="col-12">
        <div id="carouselIndicators" class="carousel slide" data-ride="carousel" data-interval="7000">
          <div class="carousel-inner">
            <xsl:for-each select="domain/slideCollection/slide">
              <xsl:variable name="i" select="position()" />
              <xsl:choose>
                <xsl:when test="$i = 1">
                  <div class="carousel-item active">
                    <div class="embed-responsive embed-responsive-16by9 mb-4">
                      <iframe class="embed-responsive-item" id="slide{$i}" style="pointer-events: none;" src="https://environmentaldashboard.org/community-voices/slides/{id}"></iframe>
                    </div>
                  </div>
                </xsl:when>
                <xsl:otherwise>
                  <div class="carousel-item">
                    <div class="embed-responsive embed-responsive-16by9 mb-4">
                      <iframe class="embed-responsive-item" id="slide{$i}" style="pointer-events: none;" src="https://environmentaldashboard.org/community-voices/slides/{id}"></iframe>
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

    <div class="row mb-3 pt-0" style="padding: 15px">
      <div class="col"><img data-cc="1" class="img-fluid selector-img" src="https://environmentaldashboard.org/community-voices/public/images/serving-our-community.png" style="cursor:pointer" /></div>
      <div class="col"><img data-cc="2" class="img-fluid selector-img" src="https://environmentaldashboard.org/community-voices/public/images/our-downtown.png" style="cursor:pointer" /></div>
      <div class="col"><img data-cc="3" class="img-fluid selector-img" src="https://environmentaldashboard.org/community-voices/public/images/next-generation.png" style="cursor:pointer" /></div>
      <div class="col"><img data-cc="6" class="img-fluid selector-img" src="https://environmentaldashboard.org/community-voices/public/images/neighbors.png" style="cursor:pointer" /></div>
      <div class="col"><img data-cc="5" class="img-fluid selector-img" src="https://environmentaldashboard.org/community-voices/public/images/nature_photos.png" style="cursor:pointer" /></div>
      <div class="col"><img data-cc="4" class="img-fluid selector-img" src="https://environmentaldashboard.org/community-voices/public/images/heritage.png" style="cursor:pointer" /></div>
      <div class="col"><img data-cc="rand" class="img-fluid selector-img" src="https://environmentaldashboard.org/community-voices/public/images/random.png" style="cursor:pointer" /></div>
    </div>

    <div class="row mb-5" style="padding: 15px">
      <form action="/community-voices/slides" method="GET" style="width:100%;padding:15px" id="search-form">
        <h4 class="mb-2">Looking for more content?</h4>
        <div class="input-group input-group-lg">
          <input name="search" type="text" class="form-control" aria-label="Search Community Voices" placeholder="Search slides, images, or quotes" style="background: url(https://environmentaldashboard.org/community-voices/public/images/search.svg) no-repeat left 1rem center;background-size: 20px 20px;padding-left: 3rem" />
          <div class="input-group-append">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="dropdown-btn">Slides</button>
            <div class="dropdown-menu" id="searchables">
              <a class="dropdown-item" data-action="/community-voices/slides" href="#">Slides</a>
              <a class="dropdown-item" data-action="/community-voices/images" href="#">Images</a>
              <a class="dropdown-item" data-action="/community-voices/quotes" href="#">Quotes</a>
              <a class="dropdown-item" data-action="/community-voices/articles" href="#">Articles</a>
            </div>
          </div>
          <button type="submit" class="btn btn-outline-primary form-control" style="max-width:15%">Search</button>
        </div>
      </form>
    </div>



  </xsl:template>

</xsl:stylesheet>

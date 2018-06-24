<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

  <xsl:output method="xml" indent="yes" omit-xml-declaration="yes" />

  <xsl:variable name="isManager" select="package/identity/user/role = 'manager'
    or package/identity/user/role = 'administrator'" />

  <xsl:template match="/package">

      <div class="row" style="padding:15px;">
        <div class="col-12">
          <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
              <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
              <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
              <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
              <xsl:for-each select="domain/slideCollection/slide">
                <xsl:variable name="i" select="position()" />
                <xsl:choose>
                  <xsl:when test="$i = 1">
                    <div class="carousel-item active">
                      <svg height="1080" width="1920" style="width:100%;height:auto" viewBox="0 0 100 50" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" class="d-block w-100">
                          <rect width="100%" height="100%" style="fill:rgb(0,0,0)" />
                          <g id="render">
                            <image x="10" y="10" width="35%">
                              <xsl:attribute name="xlink:href">/community-voices/uploads/<xsl:value-of select='image' /></xsl:attribute>
                            </image>
                            <text x="50%" y="45%" fill="#fff" font-size="4px"><xsl:value-of select='quote/quote/text' /></text>
                          </g>
                        </svg>
                    </div>
                  </xsl:when>
                  <xsl:otherwise>
                    <div class="carousel-item">
                      <svg height="1080" width="1920" style="width:100%;height:auto" viewBox="0 0 100 50" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" class="d-block w-100">
                          <rect width="100%" height="100%" style="fill:rgb(0,0,0)" />
                          <g id="render">
                            <image x="10" y="10" width="35%">
                              <xsl:attribute name="xlink:href">/community-voices/uploads/<xsl:value-of select='image' /></xsl:attribute>
                            </image>
                            <text x="50%" y="45%" fill="#fff" font-size="4px"><xsl:value-of select='quote/quote/text' /></text>
                          </g>
                        </svg>
                    </div>
                  </xsl:otherwise>
                </xsl:choose>
              </xsl:for-each>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
              </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
              </a>
          </div>
        </div>
      </div>
      <div class="row text-center" style="padding:15px;">
        <div class="col-sm-4">
          <h3>Quotes</h3>
        </div>
        <div class="col-sm-4">
          <h3>Images</h3>
        </div>
        <div class="col-sm-4">
          <h3>Slides</h3>
        </div>
      </div>

  </xsl:template>

</xsl:stylesheet>

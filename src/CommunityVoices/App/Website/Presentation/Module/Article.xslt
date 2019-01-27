<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	version="1.0">

	<xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

	<xsl:variable name="isManager" select="package/identity/user/role = 'manager'
		or package/identity/user/role = 'administrator'"/>

		<xsl:template match="/package">

			<nav class="navbar navbar-light bg-light" style="justify-content:initial">
	      <a class="navbar-brand" href="/community-voices/" style="color:#28a745;font-family:'Multicolore',sans-serif">Community Voices</a>
	      <ul class="navbar-nav" style="width:initial">
	        <li class="nav-item mr-2 active">
	          <a class="nav-link" href="/community-voices/articles">Articles <span class="sr-only">(current)</span></a>
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
	      <xsl:if test="$isManager">
	        <a style="margin-left:auto" class="btn btn-outline-primary">
	        	<xsl:attribute name="href">/community-voices/articles/<xsl:value-of select='domain/article/id'/>/edit</xsl:attribute>
						Edit
	        </a>
	      </xsl:if>
	    </nav>

			<div class="row" style="padding:15px;">
        <div class="col-12">
					<xsl:choose><xsl:when test="$isManager or domain/article/status = 'approved'">
						<h1 class="text-center"><xsl:value-of select='domain/article/title' /></h1>
						<h5 class="text-muted text-center">Interview by: <xsl:value-of select="domain/article/author" /></h5>
						<h5 class="text-muted text-center"><xsl:value-of select="domain/article/dateRecorded" /></h5>
						<div style="column-count:2; column-gap: 5vw;padding:4vw;">
							<div class="embed-responsive embed-responsive-4by3">
								<div id="introCarouselIndicators" class="carousel slide embed-responsive-item" data-ride="carousel" data-interval="10000">
								  <!-- <ol class="carousel-indicators">
								    <li data-target="#introCarouselIndicators" data-slide-to="0" class="active"></li>
								    <li data-target="#introCarouselIndicators" data-slide-to="1"></li>
								    <li data-target="#introCarouselIndicators" data-slide-to="2"></li>
								  </ol> -->
								  <div class="carousel-inner">
								    <div class="carousel-item active">
									    <img class="d-block w-100" src="https://environmentaldashboard.org/community-voices/uploads/{domain/article/image}" alt="{title}" />
								    </div>
								    <xsl:for-each select="domain/relatedSlides/media_id">
								    	<div class="carousel-item">
								    		<div class="embed-responsive embed-responsive-16by9">
												  <iframe class="embed-responsive-item" src="https://environmentaldashboard.org/community-voices/slides/{.}"></iframe>
												</div>
									    </div>
										</xsl:for-each>
								  </div>
								  <a class="carousel-control-prev" href="#introCarouselIndicators" role="button" data-slide="prev">
								    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
								    <span class="sr-only">Previous</span>
								  </a>
								  <a class="carousel-control-next" href="#introCarouselIndicators" role="button" data-slide="next">
								    <span class="carousel-control-next-icon" aria-hidden="true"></span>
								    <span class="sr-only">Next</span>
								  </a>
								</div>
							</div>
							<xsl:value-of select='domain/article/attribution' />
							<xsl:value-of select='domain/article/html' />
						</div>

							<!-- <p> Tags: TODO </p> -->


					</xsl:when>

					<xsl:otherwise>
						Unauthorized Content
					</xsl:otherwise>

				</xsl:choose>
			</div>
		</div>

	</xsl:template>

</xsl:stylesheet>

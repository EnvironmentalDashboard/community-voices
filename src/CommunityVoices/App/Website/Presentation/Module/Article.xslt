<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	version="1.0">

	<xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

	<xsl:variable name="isManager" select="package/identity/user/role = 'manager'
		or package/identity/user/role = 'administrator'"/>

		<xsl:template match="/package">
			
			<div class="row" style="padding:15px;">
        <div class="col-12">
					<a class="btn btn-primary mb-3" href="/cv/articles">&#x2190; Go back</a>

					<xsl:choose><xsl:when test="$isManager or domain/article/status = 'approved'">
						<h1 class="text-center"><xsl:value-of select='domain/article/title' /></h1>
						<h5 class="text-muted text-center">Interview by: <xsl:value-of select="domain/article/author" /></h5>
						<h5 class="text-muted text-center"><xsl:value-of select="domain/article/dateRecorded" /></h5>
						<div style="column-count:2; column-gap: 5vw;padding:4vw;">
							<div class="embed-responsive embed-responsive-16by9">
								<div id="introCarouselIndicators" class="carousel slide embed-responsive-item" data-ride="carousel" data-interval="10000">
								  <!-- <ol class="carousel-indicators">
								    <li data-target="#introCarouselIndicators" data-slide-to="0" class="active"></li>
								    <li data-target="#introCarouselIndicators" data-slide-to="1"></li>
								    <li data-target="#introCarouselIndicators" data-slide-to="2"></li>
								  </ol> -->
								  <div class="carousel-inner">
								    <div class="carousel-item active">
									    <img class="d-block w-100" src="https://environmentaldashboard.org/cv/uploads/{domain/article/image}" alt="{title}" />
								    </div>
								    <xsl:for-each select="domain/relatedSlides/media_id">
								    	<div class="carousel-item">
								    		<div class="embed-responsive embed-responsive-16by9">
												  <iframe class="embed-responsive-item" src="https://environmentaldashboard.org/cv/slides/{.}"></iframe>
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
							<xsl:copy-of select='domain/article/html' />
						</div>

	          <xsl:if test="$isManager">
	            <p>Status: <xsl:value-of select='domain/article/status' /></p>
	            <p>
								Uploader:
								<xsl:value-of select='domain/article/addedBy/user/firstName' />
								<xsl:text> </xsl:text>
								<xsl:value-of select='domain/article/addedBy/user/lastName' />
							</p>
							<p>
								<a>
				            <xsl:attribute name="href">./<xsl:value-of select='domain/article/id'/>/edit</xsl:attribute>
										Edit
				        </a>
							</p>
	          </xsl:if>

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

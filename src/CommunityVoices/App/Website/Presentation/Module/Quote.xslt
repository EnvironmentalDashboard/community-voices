<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	version="1.0">

	<xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

	<xsl:variable name="isManager" select="package/identity/user/role = 'manager'
		or package/identity/user/role = 'administrator'"/>

		<xsl:template match="/package">

      <nav class="navbar navbar-light bg-light" style="justify-content:initial">
        <a class="navbar-brand" href="/community-voices/" style="color:#28a745;font-family:'Multicolore',sans-serif">Community Voices</a>
        <div class="float-left">
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
        </div>
        <xsl:choose>
          <xsl:when test="$isManager">
            <a class="btn btn-outline-primary btn-sm mr-2" href="new" style="margin-left:auto">+ Add quote</a>
          </xsl:when>
          <xsl:otherwise>
            <div style="margin-left:auto"></div>
          </xsl:otherwise>
        </xsl:choose>
        <div class="btn-group float-right">
          <xsl:choose>
            <xsl:when test="number(domain/previous/quote/id) = domain/previous/quote/id">
              <a class="btn btn-outline-primary btn-sm" href="/community-voices/quotes/{domain/previous/quote/id}">&#171; Previous quote</a>
            </xsl:when>
            <xsl:otherwise>
              <a href="#" class="btn btn-outline-primary disabled btn-sm" disabled="disabled">&#171; Previous quote</a>
            </xsl:otherwise>
          </xsl:choose>
          <xsl:choose>
            <xsl:when test="number(domain/next/quote/id) = domain/next/quote/id">
              <a class="btn btn-outline-primary btn-sm" href="/community-voices/quotes/{domain/next/quote/id}">Next quote &#187;</a>
            </xsl:when>
            <xsl:otherwise>
              <a class="btn btn-outline-primary disabled btn-sm" href="#" disabled="disabled">Next quote &#187;</a>
            </xsl:otherwise>
          </xsl:choose>
        </div>
      </nav>
			<xsl:choose><xsl:when test="$isManager or domain/quote/status = 'approved'">
				<div class="row" style="padding:15px;">
					<div>
						<xsl:choose>
	            <xsl:when test="domain/slideId = ''">
	              <xsl:attribute name="class">col-sm-10</xsl:attribute>
	            </xsl:when>
	            <xsl:otherwise>
	              <xsl:attribute name="class">col-sm-8</xsl:attribute>
	            </xsl:otherwise>
	          </xsl:choose>
						<div class="card mb-5">
		          <div class="card-body">
		            <blockquote class="blockquote mb-0">
		              <p>
						  <xsl:if test="domain/quote/quotationMarks != ''">
							  <xsl:text>&#8220;</xsl:text>
						  </xsl:if>
						  <xsl:value-of select='domain/quote/text' />
						  <xsl:if test="domain/quote/quotationMarks != ''">
							  <xsl:text>&#8221;</xsl:text>
						  </xsl:if>
					  </p>
		              <footer class='blockquote-footer'>
		                <cite>
		                  <xsl:attribute name="title"><xsl:value-of select='domain/quote/attribution' /></xsl:attribute>
		                  <xsl:value-of select='domain/quote/attribution' />
		                  <xsl:if test="domain/quote/subAttribution != '' and domain/quote/attribution != domain/quote/subAttribution">
                        <xsl:if test="domain/quote/attribution != ''">, </xsl:if>
                        <xsl:value-of select='domain/quote/subAttribution'></xsl:value-of>
                      </xsl:if>
		                </cite>
		              </footer>
		            </blockquote>
		          </div>
		          <div class="card-footer text-muted">
		          	<p>Added <xsl:value-of select='domain/quote/dateCreated' /></p>
		          	<p class="card-text">
	                <xsl:for-each select="domain/quote/tagCollection/groupCollection/group">
	                  <span class="badge badge-primary mr-1"><xsl:value-of select="label"></xsl:value-of></span>
	                </xsl:for-each>
	              </p>
			          <xsl:if test="$isManager">
			            <p>Status: <xsl:value-of select='domain/quote/status' /></p>
			            <p>
										Uploader:
										<xsl:value-of select='domain/quote/addedBy/user/firstName' />
										<xsl:text> </xsl:text>
										<xsl:value-of select='domain/quote/addedBy/user/lastName' />
									</p>
									<p>
										<a>
						            <xsl:attribute name="href">/community-voices/quotes/<xsl:value-of select='domain/quote/id'/>/edit</xsl:attribute>
												Edit
						        </a>
									</p>
									<xsl:if test="domain/quote/contentCategoryCollection/groupCollection/group != ''">
										<p>
											Potential Content Categories:
											<xsl:for-each select="domain/quote/contentCategoryCollection/groupCollection/group">
												<xsl:value-of select="label"></xsl:value-of>
												<xsl:if test="position() != last()">
													<xsl:text>, </xsl:text>
												</xsl:if>
											</xsl:for-each>
										</p>
									</xsl:if>
									<xsl:if test="domain/quote/tagCollection/groupCollection/group != ''">
										<p>
											Tags:
											<xsl:for-each select="domain/quote/tagCollection/groupCollection/group">
												<xsl:value-of select="label"></xsl:value-of>
												<xsl:if test="position() != last()">
													<xsl:text>, </xsl:text>
												</xsl:if>
											</xsl:for-each>
										</p>
									</xsl:if>
			          </xsl:if>
		          </div>
		        </div>
		      </div>
		      <div>
		      	<xsl:choose>
	            <xsl:when test="domain/slideId != ''">
	            	<xsl:attribute name="class">col-sm-4</xsl:attribute>
	              <h4>Content featuring this quote</h4>
	              <a href='https://environmentaldashboard.org/community-voices/slides/{domain/slideId}'>
	                <div class="embed-responsive embed-responsive-16by9 mb-4">
	                  <iframe class="embed-responsive-item" style="pointer-events: none;" src="https://environmentaldashboard.org/community-voices/slides/{domain/slideId}"></iframe>
	                </div>
	              </a>
	              <p>
	              	<form action="{domain/quote/id}/unpair/{domain/slideId}" method="POST">
	                  <input type="submit" value="Unpair quote from slide" class="btn btn-danger btn-sm btn-block" />
	                </form>
	              </p>
	            </xsl:when>
	            <xsl:otherwise>
	            	<xsl:attribute name="class">col-sm-2</xsl:attribute>
	              <p>This quote is not used in any slides.</p>
				  <xsl:if test="$isManager">
	              	<p><a href="/community-voices/slides/new?prefill_quote={domain/quote/id}" class="btn btn-primary btn-block">Create one</a></p>
				</xsl:if>
	            </xsl:otherwise>
	          </xsl:choose>
		      </div>
		    </div>


			</xsl:when>

			<xsl:otherwise>
				Unauthorized Content
			</xsl:otherwise>

		</xsl:choose>

	</xsl:template>

</xsl:stylesheet>

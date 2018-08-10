<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	version="1.0">

	<xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

	<xsl:variable name="isManager" select="package/identity/user/role = 'manager'
		or package/identity/user/role = 'administrator'"/>

		<xsl:template match="/package">
			
			<nav class="navbar navbar-light bg-light">
        <div class="float-left">
          <a class="navbar-brand" href="#">Quotes</a>
          <a class="btn btn-outline-primary" href="new">+ Add quote</a>
        </div>
        <div class="btn-group float-right">
          <xsl:choose>
            <xsl:when test="number(domain/prevId) = domain/prevId">
              <a class="btn btn-outline-primary btn-sm" href="/cv/quotes/{domain/prevId}">&#171; Previous quote</a>
            </xsl:when>
            <xsl:otherwise>
              <a href="#" class="btn btn-outline-primary disabled btn-sm" disabled="disabled">&#171; Previous quote</a>
            </xsl:otherwise>
          </xsl:choose>
          <xsl:choose>
            <xsl:when test="number(domain/nextId) = domain/nextId">
              <a class="btn btn-outline-primary btn-sm" href="/cv/quotes/{domain/nextId}">Next quote &#187;</a>
            </xsl:when>
            <xsl:otherwise>
              <a class="btn btn-outline-primary disabled btn-sm" href="#" disabled="disabled">Next quote &#187;</a>
            </xsl:otherwise>
          </xsl:choose>
        </div>
      </nav>
			<xsl:choose><xsl:when test="$isManager or domain/quote/status = 'approved'">
				<div class="row" style="padding:15px;">
					<div class="col-sm-8">
						<div class="card mb-5">
		          <div class="card-body">
		            <blockquote class="blockquote mb-0">
		              <p><xsl:value-of select='domain/quote/text' /></p>
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
						            <xsl:attribute name="href">./<xsl:value-of select='domain/quote/id'/>/edit</xsl:attribute>
												Edit
						        </a>
									</p>
									<p>
										<xsl:for-each select="domain/quote/tagCollection/groupCollection/group">
											<xsl:value-of select="label"></xsl:value-of>, 
										</xsl:for-each>
									</p>
			          </xsl:if>
		          </div>
		        </div>
		      </div>
		      <div class="col-sm-4">
		      	<xsl:choose>
	            <xsl:when test="domain/slideId != ''">
	              <h4>Content featuring this quote</h4>
	              <a href='https://environmentaldashboard.org/cv/slides/{domain/slideId}'>
	                <div class="embed-responsive embed-responsive-16by9 mb-4">
	                  <iframe class="embed-responsive-item" style="pointer-events: none;" src="https://environmentaldashboard.org/cv/slides/{domain/slideId}"></iframe>
	                </div>
	              </a>
	              <p>
	              	<form action="{domain/quote/id}/unpair/{domain/slideId}" method="POST">
	                  <input type="submit" value="Unpair quote from slide" class="btn btn-danger btn-sm btn-block" />
	                </form>
	              </p>
	            </xsl:when>
	            <xsl:otherwise>
	              <p>This quote is not used in any slides.</p>
	              <p><a href="/cv/slides/new?prefill_quote={domain/quote/id}" class="btn btn-primary btn-block">Create one</a></p>
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

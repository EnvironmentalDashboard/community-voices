<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	version="1.0">

	<xsl:output method="xml" indent="yes" omit-xml-declaration="yes" />

	<xsl:variable name="isManager" select="package/identity/user/role = 'manager'
		or package/identity/user/role = 'administrator'"/>

	<xsl:variable name="search" select="package/domain/search"/>
	<xsl:variable name="tags" select="package/domain/tags"/>
	<xsl:variable name="photographers" select="package/domain/photographers"/>
	<xsl:variable name="orgs" select="package/domain/orgs"/>

	<xsl:template match="/package">

    <nav class="navbar navbar-light bg-light">
    	<a class="navbar-brand" href="#">Images</a>
    	<a class="btn btn-outline-primary active mr-auto" href="#">Newest first</a>
      <a class="btn btn-outline-primary" href="./images/new">+ Add image</a>
    </nav>

		<div class="row" style="padding:15px;">
			<div class="col-sm-3">
				<div class="card bg-light mb-3">
          <div class="card-header bg-transparent">Search Images</div>
          <form action="" method="GET">
	          <div class="card-body">
	        		<div class="form-group">
	        			<label for="search">Search</label>
	        			<input type="text" class="form-control" name="search" id="search" placeholder="Enter search terms" value="{$search}" />
	        		</div>
							<div class="form-group">
						    <label for="tags">Tags</label>
						    <select multiple="" class="form-control" id="tags" name="tags[]">
						      <xsl:for-each select="domain/groupCollection/group">
	                  <option>
	                  	<xsl:if test="contains($tags, concat(',', id, ','))">
	                  		<xsl:attribute name="selected">selected</xsl:attribute>
	                  	</xsl:if>
	                  	<xsl:attribute name="data-test"><xsl:value-of select="/domain"></xsl:value-of></xsl:attribute>
	                  	<xsl:attribute name="value"><xsl:value-of select='id' /></xsl:attribute>
	                    <xsl:value-of select="label"></xsl:value-of>
	                  </option>
	                </xsl:for-each>
						    </select>
						  </div>
							<div class="form-group">
						    <label for="photographers">Photographer</label>
						    <select multiple="" class="form-control" id="photographers" name="photographers[]">
						      <xsl:for-each select="domain/allPhotographers/photographer">
						    		<option value="{.}">
						    			<xsl:if test="contains($photographers, concat(',', ., ','))">
	                  		<xsl:attribute name="selected">selected</xsl:attribute>
	                  	</xsl:if>
						    			<xsl:value-of select="."></xsl:value-of>
						    		</option>
									</xsl:for-each>
						    </select>
						  </div>
						  <div class="form-group">
						    <label for="orgs">Organization</label>
						    <select multiple="" class="form-control" id="orgs" name="orgs[]">
						      <xsl:for-each select="domain/allOrgs/org">
						    		<option value="{.}">
						    			<xsl:if test="contains($orgs, concat(',', ., ','))">
	                  		<xsl:attribute name="selected">selected</xsl:attribute>
	                  	</xsl:if>
						    			<xsl:value-of select="."></xsl:value-of>
						    		</option>
									</xsl:for-each>
						    </select>
						  </div>
	          </div>
	          <div class="card-footer bg-transparent"><button type="button" id="reset" class="btn btn-secondary">Reset</button> <button type="submit" class="btn btn-primary">Search</button></div>
          </form>
          <!-- <xsl:value-of select="domain/qs"></xsl:value-of> -->
          <xsl:for-each select="domain/qs">
          	<xsl:value-of select="."></xsl:value-of>
          </xsl:for-each>
          <xsl:value-of select="domain/test"></xsl:value-of>
        </div>
			</div>
      <div class="col-sm-9">
			<div class="card-columns">

				<xsl:for-each select="domain/imageCollection/image">

					<xsl:if test="$isManager or status = 'approved'">

						<div class="card">
							<a href="images/{id}">
								<img>
									<xsl:attribute name="src">./uploads/<xsl:value-of select='id' /></xsl:attribute>
									<xsl:attribute name="alt"><xsl:value-of select='title' /></xsl:attribute>
									<xsl:attribute name="class">card-img-top</xsl:attribute>
								</img>
							</a>
							<div class="card-body">
								<blockquote class="blockquote mb-0">
									<h5><xsl:value-of select='title' /></h5>
									<p><xsl:value-of select='description' /></p>
									<footer class='blockquote-footer'>
		                <small class='text-muted'>
		                  <cite>
		                  	<xsl:attribute name="title"><xsl:value-of select='photographer' /></xsl:attribute>
		                  	<xsl:value-of select='photographer' />
		                  	<xsl:if test="organization != ''">, <xsl:value-of select='organization' /></xsl:if>
		                  </cite>
		                </small>
		              </footer>
								</blockquote>
							</div>
							<div class="card-footer text-muted">
								<p class='mt-0 mb-0'><xsl:value-of select='dateTaken' /></p>
								<xsl:if test="$isManager">
									<p class='mt-0 mb-0'>Status: <xsl:value-of select='status' /></p>
								</xsl:if>
							</div>

						</div>

					</xsl:if>

				</xsl:for-each>

			</div>
		</div>
	</div>
	<div class="row" style="padding:15px;">
		<div class="col-12">
			<!-- <xsl:value-of select="domain/count"></xsl:value-of> -->
			<xsl:copy-of select="domain/div"></xsl:copy-of>
		</div>
	</div>

	</xsl:template>

</xsl:stylesheet>

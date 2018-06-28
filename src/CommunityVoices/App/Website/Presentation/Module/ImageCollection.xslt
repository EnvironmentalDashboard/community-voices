<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	version="1.0">

	<xsl:output method="xml" indent="yes" omit-xml-declaration="yes" />

	<xsl:variable name="isManager" select="package/identity/user/role = 'manager'
		or package/identity/user/role = 'administrator'"/>

	<xsl:template match="/package">

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <a class="btn btn-primary btn-outline-primary mr-2" href="./images/new">+ Add image</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#subNavbar" aria-controls="subNavbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="subNavbar">
      	<!-- <a href="./images/new" class="btn btn-primary btn-outline-primary">+ Add image</a> -->
        <div class="btn-group" role="group" aria-label="Sort results">
				  <a class="btn btn-outline-primary active" href="#">Newest first</a>
				  <div class="btn-group" role="group">
				    <button id="btnGroupDrop1" type="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				      Photographer
				    </button>
				    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
				    	<xsl:for-each select="domain/allPhotographers/photographer">
				    		<button class="dropdown-item photographer-toggle" data-photographer="{.}">
				    			<xsl:value-of select="."></xsl:value-of>
				    		</button>
							</xsl:for-each>
				    </div>
				  </div>
				  <a class="btn btn-outline-primary" href="#">Organization: A-Z</a>
				</div>
        <!-- <form class="form-inline pull-right" style="min-width: 28%"> -->
        <form class="form-inline pull-right" style="position: absolute; right: 16px;">
          <input class="form-control mr-sm-2" type="search" placeholder="Search images" aria-label="Search" />
          <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Search</button>
        </form>
      </div>
    </nav>

		<div class="row" style="padding:15px;">
      <div class="col-12">
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

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	version="1.0">

	<xsl:output method="xml" indent="yes" omit-xml-declaration="yes" />

	<xsl:variable name="isManager" select="package/identity/user/role = 'manager'
		or package/identity/user/role = 'administrator'"/>

	<xsl:variable name="search" select="package/domain/search"/>
	<xsl:variable name="tags" select="package/domain/tags"/>
	<xsl:variable name="photographers" select="package/domain/photographers"/>
	<xsl:variable name="orgs" select="package/domain/orgs"/>
  <xsl:variable name="order" select="package/domain/order"/>
  <xsl:variable name="unused" select="package/domain/unused"/>

	<xsl:template match="/package">
<style>
  /* TODO: MOVE */
.image {
  position: relative;
  background: #6c757d;
}

.image img {
  opacity: 1;
  display: block;
  width: 100%;
  height: auto;
  transition: .5s ease;
  backface-visibility: hidden;
}

.image svg {
  transition: .5s ease;
  opacity: 0;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%);
  text-align: center;
}

.image:hover img {
  opacity: 0.3;
}

.image:hover svg {
  opacity: 1;
}
</style>
    <nav class="navbar navbar-light bg-light">
    	<a class="navbar-brand" href="#">Images</a>
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
                <p class="mb-0">Tags</p>
                <div style="overflow-y:scroll;width:100%;height: 130px;border:none" id='sorted-tags'>
                  <xsl:for-each select="domain/groupCollection/group">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="tags[]" id="tag{id}">
                        <xsl:if test="contains($tags, concat(',', id, ','))">
                          <xsl:attribute name="checked">checked</xsl:attribute>
                        </xsl:if>
                        <xsl:attribute name="value"><xsl:value-of select='id' /></xsl:attribute>
                      </input>
                      <label class="form-check-label">
                        <xsl:attribute name="for">tag<xsl:value-of select='id' /></xsl:attribute>
                        <xsl:value-of select="label"></xsl:value-of>
                      </label>
                    </div>
                  </xsl:for-each>
                </div>
              </div>

              <div class="form-group">
                <p class="mb-0">Photographer</p>
                <div style="overflow-y:scroll;width:100%;height: 130px;border:none" id='sorted-photographers'>
                  <xsl:for-each select="domain/photographerCollection/photographer">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="photographers[]">
                        <xsl:attribute name="id">photographer<xsl:value-of select="position()"></xsl:value-of></xsl:attribute>
                        <xsl:if test="contains($photographers, concat(',', ., ','))">
                          <xsl:attribute name="checked">checked</xsl:attribute>
                        </xsl:if>
                        <xsl:attribute name="value"><xsl:value-of select='.' /></xsl:attribute>
                      </input>
                      <label class="form-check-label">
                        <xsl:attribute name="for">photographer<xsl:value-of select='position()' /></xsl:attribute>
                        <xsl:value-of select="."></xsl:value-of>
                      </label>
                    </div>
                  </xsl:for-each>
                </div>
              </div>

              <div class="form-group">
                <p class="mb-0">Organization</p>
                <div style="overflow-y:scroll;width:100%;height: 130px;border:none" id="sorted-orgs">
                  <xsl:for-each select="domain/orgCollection/org">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="orgs[]">
                        <xsl:attribute name="id">org<xsl:value-of select="position()"></xsl:value-of></xsl:attribute>
                        <xsl:if test="contains($orgs, concat(',', ., ','))">
                          <xsl:attribute name="checked">checked</xsl:attribute>
                        </xsl:if>
                        <xsl:attribute name="value"><xsl:value-of select='.' /></xsl:attribute>
                      </input>
                      <label class="form-check-label">
                        <xsl:attribute name="for">org<xsl:value-of select='position()' /></xsl:attribute>
                        <xsl:value-of select="."></xsl:value-of>
                      </label>
                    </div>
                  </xsl:for-each>
                </div>
              </div>

              <div class="form-group">
                <label for="order">Order by</label>
                <select class="form-control" id="order" name="order">
                  <option value="date_taken_desc">
                    <xsl:if test="$order = 'date_taken_desc'">
                      <xsl:attribute name="selected">selected</xsl:attribute>
                    </xsl:if>
                    Newest first
                  </option>
                  <option value="date_taken_asc">
                    <xsl:if test="$order = 'date_taken_asc'">
                      <xsl:attribute name="selected">selected</xsl:attribute>
                    </xsl:if>
                    Oldest first
                  </option>
                  <option value="photographer_desc">
                    <xsl:if test="$order = 'photographer_desc'">
                      <xsl:attribute name="selected">selected</xsl:attribute>
                    </xsl:if>
                    Photographer
                  </option>
                </select>
              </div>

              <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" id="unused" name="unused">
                  <xsl:if test="$unused = 'on'">
                    <xsl:attribute name="checked">checked</xsl:attribute>
                  </xsl:if>
                </input>
                <label class="form-check-label" for="unused">
                  Show only unpaired images
                </label>
              </div>

	          </div>
	          <div class="card-footer bg-transparent"><button type="button" onclick="this.parentNode.parentNode.reset()" class="btn btn-secondary mr-2">Reset</button> <button type="submit" class="btn btn-primary">Search</button></div>
          </form>
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
  							<div class="image">
                  <img>
                    <xsl:attribute name="src">https://environmentaldashboard.org/cv/uploads/<xsl:value-of select='id' /></xsl:attribute>
                    <xsl:attribute name="alt"><xsl:value-of select='title' /></xsl:attribute>
                    <xsl:attribute name="class">card-img</xsl:attribute>
                  </img>
                  <svg width="100" height="100" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1152 1376v-160q0-14-9-23t-23-9h-96v-512q0-14-9-23t-23-9h-320q-14 0-23 9t-9 23v160q0 14 9 23t23 9h96v320h-96q-14 0-23 9t-9 23v160q0 14 9 23t23 9h448q14 0 23-9t9-23zm-128-896v-160q0-14-9-23t-23-9h-192q-14 0-23 9t-9 23v160q0 14 9 23t23 9h192q14 0 23-9t9-23zm640 416q0 209-103 385.5t-279.5 279.5-385.5 103-385.5-103-279.5-279.5-103-385.5 103-385.5 279.5-279.5 385.5-103 385.5 103 279.5 279.5 103 385.5z" fill="#fff"/></svg>
                </div>
							</a>
              <xsl:choose>
                <xsl:when test="$isManager">
                  <div class="card-footer text-muted">
                    <p class='mt-0 mb-0'>Title: <xsl:value-of select='title' /></p>
                    <p class='mt-0 mb-0'>Description: <xsl:value-of select='description' /></p>
                    <p class='mt-0 mb-0'>Photographer: <xsl:value-of select='photographer' /></p>
                    <p class='mt-0 mb-0'>Organization: <xsl:value-of select='organization' /></p>
                    <p class='mt-0 mb-0'>Date taken: <xsl:value-of select='dateTaken' /></p>
                    <p class='mt-0 mb-0'>Status: <xsl:value-of select='status' /></p>
                  </div>
                </xsl:when>
                <xsl:otherwise>
                  <div class="card-footer text-muted">
                    <p class='mt-0 mb-0'>Source: 
                      <xsl:value-of select='photographer' />
                      <xsl:if test="organization != '' and photographer != organization">
                        <xsl:if test="photographer != ''">, </xsl:if>
                        <xsl:value-of select='organization'></xsl:value-of>
                      </xsl:if>
                    </p>
                    <!-- <p class='mt-0 mb-0'>Organization: <xsl:value-of select='organization' /></p> -->
                  </div>
                </xsl:otherwise>
              </xsl:choose>

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

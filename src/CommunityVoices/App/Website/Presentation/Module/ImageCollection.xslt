<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	version="1.0">

	<xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

	<xsl:variable name="isManager" select="package/identity/user/role = 'manager'
		or package/identity/user/role = 'administrator'"/>

	<xsl:variable name="search" select="package/domain/search"/>
	<xsl:variable name="tags" select="package/domain/tags"/>
	<xsl:variable name="photographers" select="package/domain/photographers"/>
	<xsl:variable name="orgs" select="package/domain/orgs"/>
  <xsl:variable name="order" select="package/domain/order"/>
  <xsl:variable name="unused" select="package/domain/unused"/>
  <xsl:variable name="allTags" select="package/domain/groupCollection/group" />

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

              <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" id="unused" name="unused" value="1">
                  <xsl:if test="$unused = '1'">
                    <xsl:attribute name="checked">checked</xsl:attribute>
                  </xsl:if>
                </input>
                <label class="form-check-label" for="unused">
                  Show only unpaired images
                </label>
              </div>

	        		<div class="form-group">
                <p class="mb-0">Tags</p>
                <div style="overflow-y:scroll;width:100%;height: 145px;border:none" id='sorted-tags'>
                  <xsl:for-each select="$allTags">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="tags[]" id="globalTag{id}">
                        <xsl:if test="contains($tags, concat(',', id, ','))">
                          <xsl:attribute name="checked">checked</xsl:attribute>
                        </xsl:if>
                        <xsl:attribute name="value"><xsl:value-of select='id' /></xsl:attribute>
                      </input>
                      <label class="form-check-label">
                        <xsl:attribute name="for">globalTag<xsl:value-of select='id' /></xsl:attribute>
                        <xsl:value-of select="label"></xsl:value-of>
                      </label>
                    </div>
                  </xsl:for-each>
                </div>
              </div>

              <div class="form-group">
                <p class="mb-0">Photographer</p>
                <div style="overflow-y:scroll;width:100%;height: 145px;border:none" id='sorted-photographers'>
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
                <div style="overflow-y:scroll;width:100%;height: 145px;border:none" id="sorted-orgs">
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
              <div class="card-footer text-muted">
                <xsl:choose>
                  <xsl:when test="$isManager">
                    <form action="images/{id}/edit/authenticate" method="POST">
                      <div class="form-group mb-1">
                        <a class="btn btn-outline-secondary btn-sm btn-block" data-toggle="collapse" href="#collapse{id}" role="button" aria-expanded="false" aria-controls="collapse{id}">Toggle tags</a>
                        <div class="collapse" id="collapse{id}">
                          <xsl:variable name="curTagString" select="selectedTagString" />
                          <xsl:variable name="curId" select="id" />
                          <xsl:for-each select="$allTags">
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="tags[]" id="{$curId}tag{id}">
                                <xsl:attribute name="value"><xsl:value-of select='id' /></xsl:attribute>
                                <xsl:if test="contains($curTagString, concat(',', id, ','))">
                                  <xsl:attribute name="checked">checked</xsl:attribute>
                                </xsl:if>
                              </input>
                              <label class="form-check-label">
                                <xsl:attribute name="for"><xsl:value-of select='$curId' />tag<xsl:value-of select='id' /></xsl:attribute>
                                <xsl:value-of select="label"></xsl:value-of>
                              </label>
                            </div>
                          </xsl:for-each>
                        </div>
                      </div>
                      <div class="form-group mb-1">
                        <label class="mb-0" for="title{id}">Title</label>
                        <input type="text" name="title" id="title{id}" class="form-control form-control-sm">
                          <xsl:attribute name="value"><xsl:value-of select="title"></xsl:value-of></xsl:attribute>
                        </input>
                      </div>
                      <div class="form-group mb-1">
                        <label class="mb-0" for="description{id}">Description</label>
                        <input type="text" name="description" id="description{id}" class="form-control form-control-sm">
                          <xsl:attribute name="value"><xsl:value-of select="description"></xsl:value-of></xsl:attribute>
                        </input>
                      </div>
                      <div class="form-group mb-1">
                        <label class="mb-0" for="dateTaken{id}">Date taken</label>
                        <input type="text" name="dateTaken" id="dateTaken{id}" class="form-control form-control-sm">
                          <xsl:attribute name="value"><xsl:value-of select="dateTaken"></xsl:value-of></xsl:attribute>
                        </input>
                      </div>
                      <div class="form-group mb-1">
                        <label class="mb-0" for="photographer{id}">Photographer</label>
                        <input type="text" name="photographer" id="photographer{id}" class="form-control form-control-sm">
                          <xsl:attribute name="value"><xsl:value-of select="photographer"></xsl:value-of></xsl:attribute>
                        </input>
                      </div>
                      <div class="form-group mb-1">
                        <label class="mb-0" for="org{id}">Organization</label>
                        <input type="text" name="organization" id="org{id}" class="form-control form-control-sm">
                          <xsl:attribute name="value"><xsl:value-of select="organization"></xsl:value-of></xsl:attribute>
                        </input>
                      </div>
                      <input type='submit' class="btn btn-primary mt-2 btn-sm btn-block" value="Update"></input>
                    </form>
                  </xsl:when>
                  <xsl:otherwise>
                    <p class='mt-0 mb-0'>Source: 
                      <xsl:value-of select='photographer' />
                      <xsl:if test="organization != '' and photographer != organization">
                        <xsl:if test="photographer != ''">, </xsl:if>
                        <xsl:value-of select='organization'></xsl:value-of>
                      </xsl:if>
                    </p>
                  </xsl:otherwise>
                </xsl:choose>
              </div>

						</div>

					</xsl:if>

				</xsl:for-each>

			</div>
		</div>
	</div>
	<div class="row" style="padding:15px;">
		<div class="col-12">
			<xsl:copy-of select="domain/div"></xsl:copy-of>
		</div>
	</div>

	</xsl:template>

</xsl:stylesheet>

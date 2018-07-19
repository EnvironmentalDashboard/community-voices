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
  <xsl:variable name="attributions" select="package/domain/attributions"/>


	<xsl:template match="/package">

		<nav class="navbar navbar-light bg-light">
      <a class="navbar-brand" href="#">Slides</a>
      <a class="btn btn-outline-primary" href="./slides/new">+ Add slide</a>
    </nav>

		<div class="row" style="padding:15px;">
			<div class="col-sm-3">
				<div class="card bg-light mb-3">
          <div class="card-header bg-transparent">Search Slides</div>
          <form action="" method="GET">
	          <div class="card-body">
	        		<div class="form-group">
	        			<label for="search">Search</label>
	        			<input type="text" class="form-control" name="search" id="search" placeholder="Enter search terms" value="{$search}" />
	        		</div>

	        		<div class="form-group">
                <p class="mb-0">Tags</p>
                <div style="overflow-y:scroll;width:100%;height: 130px;border:none">
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
                <div style="overflow-y:scroll;width:100%;height: 130px;border:none">
                  <xsl:for-each select="domain/PhotographerCollection/photographer">
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
                <p class="mb-0">Image attribution</p>
                <div style="overflow-y:scroll;width:100%;height: 130px;border:none">
                  <xsl:for-each select="domain/OrgCollection/org">
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
                <p class="mb-0">Quote attribution</p>
                <div style="overflow-y:scroll;width:100%;height: 130px;border:none">
                  <xsl:for-each select="domain/attributionCollection/attribution">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="attributions[]">
                        <xsl:attribute name="id">attribution<xsl:value-of select="position()"></xsl:value-of></xsl:attribute>
                        <xsl:if test="contains($attributions, concat(',', ., ','))">
                          <xsl:attribute name="checked">checked</xsl:attribute>
                        </xsl:if>
                        <xsl:attribute name="value"><xsl:value-of select='.' /></xsl:attribute>
                      </input>
                      <label class="form-check-label">
                        <xsl:attribute name="for">attribution<xsl:value-of select='position()' /></xsl:attribute>
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
                </select>
              </div>

	          </div>
	          <div class="card-footer bg-transparent"><button type="button" id="reset" class="btn btn-secondary">Reset</button> <button type="submit" class="btn btn-primary">Search</button></div>
          </form>
          <xsl:for-each select="domain/qs">
          	<xsl:value-of select="."></xsl:value-of>
          </xsl:for-each>
          <xsl:value-of select="domain/test"></xsl:value-of>
        </div>
			</div>
      <div class="col-sm-9">
      	<xsl:for-each select="domain/slideCollection/slide">
      		<xsl:if test="$isManager or status = 'approved'">
		      	<a href= "slides/{id}"/>

							<img src="https://environmentaldashboard.org/cv/slides/{id}" class="img-fluid mb-5" />

							<!-- <p>
								<xsl:value-of select='image/image/id' />,
								<xsl:value-of select='quote/quote/text' />,
								<xsl:value-of select='contentCategory/contentCategory/id' />
							</p> -->

						<a/>
					</xsl:if>
				</xsl:for-each>
      </div>
		</div>
		<div class="row" style="padding:15px;">
      <div class="col-12">
        <xsl:copy-of select="domain/div"></xsl:copy-of>
      </div>
    </div>


	</xsl:template>

</xsl:stylesheet>

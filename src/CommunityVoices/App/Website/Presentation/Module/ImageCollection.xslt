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
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action='images/new/authenticate' method='post' enctype='multipart/form-data'>
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Upload images</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&#215;</span>
              </button>
            </div>
            <div class="modal-body">

              <div class="custom-file mb-2">
                <label for="file" class="custom-file-label">File</label>
                <input class="custom-file-input" id="file" type='file' name='file[]' accept='.jpg, .jpeg, .png' multiple="" />
              </div>

              <div class="form-group">
                <label for="title">Title</label>
                <input class="form-control" id="title" type='text' name='title' />
              </div>

              <div class="form-group">
                <label for="description">Description</label>
                <input class="form-control" id="description" type='text' name='description' />
              </div>

              <div class="form-group">
                <label for="dateTaken">Date Taken</label>
                <input class="form-control" id="dateTaken" type='text' name='dateTaken' />
              </div>

              <div class="form-group">
                <label for="photographer">Photographer</label>
                <input class="form-control" id="photographer" type='text' name='photographer' />
              </div>

              <div class="form-group">
                <label for="organization">Organization</label>
                <input class="form-control" id="organization" type='text' name='organization' />
              </div>

              <div class="form-group">
                <p class="mb-0">Tags</p>
                <div style="overflow-y:scroll;width:100%;height: 145px;border:none">
                  <xsl:for-each select="domain/groupCollection/group">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="tags[]" id="tag{id}">
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
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Upload</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <nav class="navbar navbar-light bg-light">
    	<form action="/cv/slides" method="GET" id="search-form" class="d-inline" style="width:70%">
        <div class="input-group input-group">
          <div class="input-group-append">
            <button class="btn btn-outline-secondary dropdown-toggle rounded-left" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="dropdown-btn">Images</button>
            <div class="dropdown-menu" id="searchables" style="left:initial">
              <a class="dropdown-item" data-action="/cv/slides" href="#">Slides</a>
              <a class="dropdown-item" data-action="/cv/quotes" href="#">Quotes</a>
              <a class="dropdown-item" data-action="/cv/articles" href="#">Articles</a>
            </div>
          </div>
          <input name="search" type="text" class="form-control" aria-label="Search Community Voices" placeholder="Search slides, images, or quotes" style="background: url(https://environmentaldashboard.org/cv/public/search.svg) no-repeat left 1rem center;background-size: 20px 20px;padding-left: 3rem;min-width:40%" value="{$search}" />
          <button type="submit" class="btn btn-outline-primary form-control" style="max-width:15%">Search</button>
        </div>
      </form>
      <a class="btn btn-outline-primary" href="./images/new" data-toggle="modal" data-target="#exampleModal">+ Add image</a>
    </nav>

		<div class="row" style="padding:15px;">
			<div class="col-sm-3">
				<div class="card bg-light mb-3">
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
                  <option value="id_desc">
                    <xsl:if test="$order = 'id_desc'">
                      <xsl:attribute name="selected">selected</xsl:attribute>
                    </xsl:if>
                    Newest first (date uploaded)
                  </option>
                  <option value="id_asc">
                    <xsl:if test="$order = 'id_asc'">
                      <xsl:attribute name="selected">selected</xsl:attribute>
                    </xsl:if>
                    Oldest first (date uploaded)
                  </option>
                  <option value="date_taken_desc">
                    <xsl:if test="$order = 'date_taken_desc'">
                      <xsl:attribute name="selected">selected</xsl:attribute>
                    </xsl:if>
                    Newest first (date taken)
                  </option>
                  <option value="date_taken_asc">
                    <xsl:if test="$order = 'date_taken_asc'">
                      <xsl:attribute name="selected">selected</xsl:attribute>
                    </xsl:if>
                    Oldest first (date taken)
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
        <xsl:choose>
          <xsl:when test="$isManager">
            <div class="alert alert-dismissible fade show d-none" role="alert" id="alert">
              <span id="alert-content"></span>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&#215;</span>
              </button>
            </div>
            <div class="table-responsive">
              <table class="table" style="max-width:100%">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Thumbnail</th>
                    <th scope="col">Tag</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Date Taken</th>
                    <th scope="col">Photographer</th>
                    <th scope="col">Organization</th>
                    <th scope="col">Approved</th>
                    <th scope="col">&#160;</th>
                    <th scope="col">&#160;</th>
                  </tr>
                </thead>
                <tbody>
                  <xsl:for-each select="domain/imageCollection/image">
                    <tr>
                      <th scope="row"><xsl:value-of select="id" /></th>
                      <form action="images/{id}/edit/authenticate" method="POST" class="edit-form" id="edit-form{id}">
                      </form>
                      <td>
                        <a href="images/{id}">
                          <img class="img-fluid" style="max-width:200px">
                            <xsl:attribute name="src">https://environmentaldashboard.org/cv/uploads/<xsl:value-of select='id' />?max_width=200</xsl:attribute>
                            <xsl:attribute name="alt"><xsl:value-of select='title' /></xsl:attribute>
                          </img>
                        </a>
                      </td>
                      <td>
                        <div style="overflow-y:scroll;width:100%;height: 145px;border:none">
                        <xsl:variable name="curTagString" select="selectedTagString" />
                        <xsl:variable name="curId" select="id" />
                        <xsl:for-each select="$allTags">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="tags[]" id="{$curId}tag{id}" form="edit-form{id}">
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
                      </td>
                      <td style="min-width:200px">
                        <div class="form-group mb-1">
                          <label class="sr-only" for="title{id}">Title</label>
                          <input type="text" name="title" id="title{id}" class="form-control" form="edit-form{id}">
                            <xsl:attribute name="value"><xsl:value-of select="title"></xsl:value-of></xsl:attribute>
                          </input>
                        </div>
                      </td>
                      <td style="min-width:250px">
                        <div class="form-group mb-1">
                          <label class="sr-only" for="description{id}">Description</label>
                          <textarea name="description" id="description{id}" class="form-control" form="edit-form{id}">
                            <xsl:value-of select="description"></xsl:value-of>
                          </textarea>
                        </div>
                      </td>
                      <td style="min-width:200px">
                        <div class="form-group mb-1">
                          <label class="sr-only" for="dateTaken{id}">Date taken</label>
                          <input type="text" name="dateTaken" id="dateTaken{id}" class="form-control" form="edit-form{id}" style="min-width:190px">
                            <xsl:attribute name="value"><xsl:value-of select="dateTaken"></xsl:value-of></xsl:attribute>
                          </input>
                        </div>
                      </td>
                      <td style="min-width:200px">
                        <div class="form-group mb-1">
                          <label class="sr-only" for="photographer{id}">Photographer</label>
                          <input type="text" name="photographer" id="photographer{id}" class="form-control" form="edit-form{id}">
                            <xsl:attribute name="value"><xsl:value-of select="photographer"></xsl:value-of></xsl:attribute>
                          </input>
                        </div>
                      </td>
                      <td style="min-width:200px">
                        <div class="form-group mb-1">
                          <label class="sr-only" for="org{id}">Organization</label>
                          <input type="text" name="organization" id="org{id}" class="form-control" form="edit-form{id}">
                            <xsl:attribute name="value"><xsl:value-of select="organization"></xsl:value-of></xsl:attribute>
                          </input>
                        </div>
                      </td>
                      <td>
                        <div class="form-group mb-1">
                          <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="approve" name="approve" value="1" form="edit-form{id}">
                              <xsl:if test="status = 'approved'">
                                <xsl:attribute name="checked">checked</xsl:attribute>
                              </xsl:if>
                            </input>
                            <label class="custom-control-label" for="approve">Approve</label>
                          </div>
                        </div>
                      </td>
                      <td>
                        <input type='submit' class="btn btn-primary mt-2 btn-block" value="Update" form="edit-form{id}"></input>
                      </td>
                      <td>
                        <form action="images/{id}/delete/authenticate" method="POST" class="delete-form">
                          <input type="submit" value="Delete" class="btn btn-danger mt-2" />
                        </form>
                      </td>
                    </tr>
                  </xsl:for-each>
                </tbody>
              </table>
            </div>
          </xsl:when>
          <xsl:otherwise>
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
                      <p class='mt-0 mb-0'>Source: 
                        <xsl:value-of select='photographer' />
                        <xsl:if test="organization != '' and photographer != organization">
                          <xsl:if test="photographer != ''">, </xsl:if>
                          <xsl:value-of select='organization'></xsl:value-of>
                        </xsl:if>
                      </p>
                    </div>

                  </div>

                </xsl:if>

              </xsl:for-each>

            </div>
          </xsl:otherwise>
        </xsl:choose>
  		</div>
  	</div>
  	<div class="row" style="padding:15px;">
  		<div class="col-12">
  			<xsl:copy-of select="domain/div"></xsl:copy-of>
  		</div>
  	</div>

	</xsl:template>

</xsl:stylesheet>

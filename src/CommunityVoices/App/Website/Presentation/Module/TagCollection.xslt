<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  version="1.0">

  <xsl:import href="../Component/Navbar.xslt" />
  <xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

  <xsl:variable name="isManager" select="package/identity/user/role = 'manager'
      or package/identity/user/role = 'administrator'"/>

  <xsl:template match="/package">
      <xsl:if test="$isManager">
        <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <form action='tags/new' method='post' enctype='multipart/form-data'>
                <div class="modal-header">
                  <h5 class="modal-title" id="createModalLabel">Upload Tags</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&#215;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="form-group">
                    <label for="title">Text</label>
                    <input class="form-control" id="text" type='text' name='text' />
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
        <!-- /modal -->
        <div class="alert alert-dismissible fade show d-none" role="alert" id="alert" style="top: 20px;right: 15%;left: 15%;width: 70%;position:fixed;z-index:9999">
          <span id="alert-content"></span>
          <button type="button" class="close" aria-label="Close" onclick="$(this).closest('.alert').addClass('d-none')">
            <span aria-hidden="true">&#215;</span>
          </button>
        </div>
      </xsl:if>
      <xsl:call-template name="navbar">
          <xsl:with-param name="active">
              Tags
          </xsl:with-param>
          <xsl:with-param name="rightButtons">
              <xsl:if test="$isManager">
                <a class="btn btn-outline-primary mr-2" href="/community-voices/tags/new" data-toggle="modal" data-target="#createModal">+ Add tag</a>
              </xsl:if>

              <xsl:call-template name="userButtons" />
          </xsl:with-param>
      </xsl:call-template>

      <div class="col-sm-9">
          <div class="card">
              <div class="card-header">Tags</div>
              <ul class="list-group list-group-flush">
                <xsl:for-each select="domain/tagCollection/tag">
                    <li class="list-group-item">
                        <blockquote class="blockquote mb-0">
                        <xsl:value-of select="label"></xsl:value-of>
                        </blockquote>
                        <xsl:if test="$isManager">
                            <div class="mt-2">
                                <form action="/community-voices/tags/{id}/delete" method="POST" class="delete-form">
                                  <button class="btn btn-danger" type="submit">Delete Tag</button>
                                </form>
                            </div>
                        </xsl:if>
                    </li>

                </xsl:for-each>
              </ul>
          </div>
      </div>

  </xsl:template>
</xsl:stylesheet>

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

    <xsl:template match="/package">

      <div class="row" style="padding:15px;">
        <div class="col-12">

          <form action='new/authenticate' method='post' enctype='multipart/form-data' style="max-width:400px;margin: 0 auto">

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
                <label for="organization">Organization</label>
                <input class="form-control" id="organization" type='text' name='organization' />
              </div>

              <div class="form-group">
                <label for="photographer">Photographer</label>
                <input class="form-control" id="photographer" type='text' name='photographer' />
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

              <input type='submit' class="btn btn-primary" />
          </form>
        </div>
      </div>
    </xsl:template>

</xsl:stylesheet>

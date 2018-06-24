<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:output method="xml" indent="yes" omit-xml-declaration="yes" />

    <xsl:template match="/form">

      <div class="row" style="padding:15px;">
        <div class="col-12">

          <form action='./images/new/authenticate' method='post' enctype='multipart/form-data' style="max-width:400px;margin: 0 auto">

              <div class="custom-file">
                <label for="file" class="custom-file-label">File</label>
                <input class="custom-file-input" id="file" type='file' name='file' accept='.jpg, .jpeg, .png' />
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

              Approve:
              <xsl:choose>
                <xsl:when test="@approve-value &gt; 0">
                    <input type='checkbox' name='approved' checked='{@approve-value}'/>
                </xsl:when>
                <xsl:otherwise>
                    <input type='checkbox' name='approved' />
                </xsl:otherwise>
              </xsl:choose>
              <br/>

              <input type='submit' class="btn btn-primary" />
          </form>
        </div>
      </div>
    </xsl:template>

</xsl:stylesheet>

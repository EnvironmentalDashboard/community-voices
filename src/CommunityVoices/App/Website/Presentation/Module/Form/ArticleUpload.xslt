<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

    <xsl:template match="/form">
      <div class="row" style="padding:15px;">
        <div class="col-12">
          <xsl:if test="@failure">
              <p>Author missing.</p>
          </xsl:if>

          <form action='./articles/new/authenticate' method='post'>

              <div class="form-group">
                <label for="text">Enter article below</label>
                <textarea name="text" id="text" cols="25" rows="20" class="form-control"></textarea>
              </div>

              <div class="form-group">
                <label for="author">Author</label>
                  <xsl:choose>
                      <xsl:when test='@text'>
                          <input type='text' class="form-control" id='author' name='author' value='{@text}' style='max-width: 200px' />
                      </xsl:when>
                      <xsl:otherwise>
                          <input type='text' class="form-control" id='author' name='author' style='max-width: 200px'  />
                      </xsl:otherwise>
                  </xsl:choose>
                  </div>

              <div class="form-group">
                <label for="dateRecorded">Date Recorded</label>
                <input class="form-control" id="dateRecorded" type='text' name='dateRecorded' style='max-width: 200px' />
              </div>

              Approve:
              <xsl:choose>
                <xsl:when test="@approve-value &gt; 0">
                    <input type='checkbox' name='approved' checked='{@approve-value}' />
                </xsl:when>
                <xsl:otherwise>
                    <input type='checkbox' name='approved' />
                </xsl:otherwise>
              </xsl:choose>
              <br/>

              <input type='submit' class='btn btn-primary' />
          </form>
        </div>
      </div>
    </xsl:template>

</xsl:stylesheet>

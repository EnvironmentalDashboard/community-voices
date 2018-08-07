<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:output method="html" indent="yes" omit-xml-declaration="yes" />
    <xsl:variable name="selectedTags" select="/form/domain/selectedTags" />

    <xsl:template match="/form">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.4.1/cropper.min.css" />

      <div class="row" style="padding:15px;">
        <div class="col-12">

          <div class="mb-2">
            <img src="https://environmentaldashboard.org/cv/uploads/{domain/image/id}" alt="{domain/image/title}" id="cropper-img" style="width:80%;margin:0 auto;" class="mt-2 d-block" />
          </div>

          <div style="max-width:400px;margin: 0 auto">
            <div class="form-group mb-1">
              <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="crop-checkbox" onchange="enable_cropper();" />
                <label class="custom-control-label" for="crop-checkbox">Crop image</label>
              </div>
            </div>
          </div>

          <form method='post' enctype='multipart/form-data' style="max-width:400px;margin: 0 auto" action="edit/authenticate">

              <input type="hidden" id="crop_x" name="crop_x" value="0"/>
              <input type="hidden" id="crop_y" name="crop_y" value="0" />
              <input type="hidden" id="crop_width" name="crop_width" value="0" />
              <input type="hidden" id="crop_height" name="crop_height" value="0" />

              <div class="form-group">
                <label for="title">Title</label>
                <input class="form-control" id="title" type='text' name='title'>
                  <xsl:attribute name="value"><xsl:value-of select="domain/image/title"/></xsl:attribute>
                </input>
              </div>

              <div class="form-group">
                <label for="description">Description</label>
                <input class="form-control" id="description" type='text' name='description'>
                  <xsl:attribute name="value"><xsl:value-of select="domain/image/description"/></xsl:attribute>
                </input>
              </div>

              <div class="form-group">
                <label for="dateTaken">Date Taken</label>
                <input class="form-control" id="dateTaken" type='text' name='dateTaken'>
                  <xsl:attribute name="value"><xsl:value-of select="domain/image/dateCreated"/></xsl:attribute>
                </input>
              </div>

              <div class="form-group">
                <label for="photographer">Photographer</label>
                <input class="form-control" id="photographer" type='text' name='photographer'>
                  <xsl:attribute name="value"><xsl:value-of select="domain/image/photographer"/></xsl:attribute>
                </input>
              </div>

              <div class="form-group">
                <label for="organization">Organization</label>
                <input class="form-control" id="organization" type='text' name='organization'>
                  <xsl:attribute name="value"><xsl:value-of select="domain/image/organization"/></xsl:attribute>
                </input>
              </div>

              <div class="form-group">
                <p class="mb-0">Tags</p>
                <div style="overflow-y:scroll;width:100%;height: 145px;border:none">
                  <xsl:for-each select="domain/groupCollection/group">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="tags[]" id="tag{id}">
                        <xsl:attribute name="value"><xsl:value-of select='id' /></xsl:attribute>
                        <xsl:if test="contains($selectedTags, concat(',', id, ','))">
                          <xsl:attribute name="checked">checked</xsl:attribute>
                        </xsl:if>
                      </input>
                      <label class="form-check-label">
                        <xsl:attribute name="for">tag<xsl:value-of select='id' /></xsl:attribute>
                        <xsl:value-of select="label"></xsl:value-of>
                      </label>
                    </div>
                  </xsl:for-each>
                </div>
              </div>

              <!-- <xsl:value-of select='domain/groupCollection'></xsl:value-of> -->
              <!-- <xsl:value-of select='domain/image/tagCollection/groupCollection'></xsl:value-of> -->

              <div class="form-group mb-1">
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" id="approve" name="approve" value="1">
                    <xsl:if test="domain/image/status = 'approved'">
                      <xsl:attribute name="checked">checked</xsl:attribute>
                    </xsl:if>
                  </input>
                  <label class="custom-control-label" for="approve">Approve</label>
                </div>
              </div>

              <input type='submit' class="btn btn-primary" />
          </form>
        </div>
      </div>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.4.1/cropper.min.js"></script>
      <script>
        const image = document.getElementById("cropper-img");
        const crop_x = document.getElementById("crop_x");
        const crop_y = document.getElementById("crop_y");
        const crop_height = document.getElementById("crop_height");
        const crop_width = document.getElementById("crop_width");
        const checkbox = document.getElementById("crop-checkbox");
        var cropper;
        function enable_cropper() {
          console.log(cropper);
          if (checkbox.checked) {
            cropper = new Cropper(image, {checkCrossOrigin: false, viewMode: 1, crop(event) {
              crop_x.value = event.detail.x;
              crop_y.value = event.detail.y;
              crop_width.value = event.detail.width;
              crop_height.value = event.detail.height;
            }});
          } else if (cropper !== undefined) {
            cropper.destroy();
            crop_x.value = 0;
            crop_y.value = 0;
            crop_width.value = 0;
            crop_height.value = 0;
          }
        }
        enable_cropper();
      </script>
    </xsl:template>

</xsl:stylesheet>

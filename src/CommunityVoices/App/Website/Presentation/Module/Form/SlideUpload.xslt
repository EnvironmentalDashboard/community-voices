<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">
    <xsl:output method="xml" indent="yes" omit-xml-declaration="yes" />

    <xsl:template match="/form">
        <div class="row" style="padding:15px;">
          <div class="col-12">
        <style>
          /* Temporary CSS block until better location found */
          .selectables, #list-view, #gallery-view {cursor:pointer}
          .card-columns .card:hover {border-color:#21a7df}
        </style>
        <h2 class="mb-4">Create a slide</h2>
        <div class="row">
          <div class="col-sm-3">
            <ul class="nav flex-column nav-pills mb-4">
              <li class="nav-item">
                <a class="nav-link active" href="#" id="quote-btn">Quotes</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#" id="img-btn">Images</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#" id="cc-btn">Content Categories</a>
              </li>
            </ul>
            <form action='./slides/new/authenticate' method='post'>
              <input type="hidden" name="image_id"/>
              <input type="hidden" name="quote_id"/>
              <input type="hidden" name="content_category"/>
              <div class="form-group">
                <input type="text" name="probability" placeholder="Probability" class="form-control" />
              </div>
              <div class="form-group">
                <input type="text" name="decay_percent" placeholder="Decay percent" class="form-control" />
              </div>
              <div class="form-group">
                <input type="text" name="decay_start" placeholder="Decay start" class="form-control" />
              </div>
              <div class="form-group">
                <input type="text" name="decay_end" placeholder="Decay end" class="form-control" />
              </div>
              <input type='submit' class="btn btn-primary" />
            </form>
          </div>
          <div class="col-sm-9">
            <div><div id="ajax-quotes" style="min-height:400px">
              <h2 class="mb-0">Select a quote</h2>
              <form class="form-inline float-right" action="" method="GET" id="filter-quotes">
                <label class="sr-only" for="search-quotes">Search quotes</label>
                <input type="text" class="form-control mb-2 mr-sm-2" id="search-quotes" placeholder="Search" />
                <label class="sr-only" for="quote-tags">Tags</label>
                <select class="form-control" id="quote-tags" style="max-width:150px">
                  <option value="">Select tag</option>
                  <xsl:for-each select="domain/groupCollection/group">
                    <option>
                      <xsl:attribute name="value"><xsl:value-of select='id' /></xsl:attribute>
                      <xsl:value-of select="label"></xsl:value-of>
                    </option>
                  </xsl:for-each>
                </select>
                <label class="sr-only" for="quote-attributions">Attribution</label>
                <select class="form-control" id="quote-attributions" style="max-width:150px">
                  <option value="">Select attribution</option>
                  <xsl:for-each select="domain/attributionCollection/attribution">
                    <option>
                      <xsl:attribute name="value"><xsl:value-of select='.' /></xsl:attribute>
                      <xsl:value-of select="."></xsl:value-of>
                    </option>
                  </xsl:for-each>
                </select>
                <button type="submit" class="btn btn-primary mb-2">Submit</button>
              </form>
              <p class="mt-5"><a id="prev-quote" href="" class="btn btn-sm btn-outline-primary">&#8592; Previous page</a> <a id="next-quote" href="" class="btn btn-sm btn-outline-primary float-right">&#8594; Next page</a></p>
              <p class="mb-2 mt-0 d-inline">Select view:
                <svg id="list-view" width="20" height="20" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg" style="position:relative;top:5px;fill:#21a7df"><path d="M832 1024v384q0 52-38 90t-90 38h-512q-52 0-90-38t-38-90v-384q0-52 38-90t90-38h512q52 0 90 38t38 90zm0-768v384q0 52-38 90t-90 38h-512q-52 0-90-38t-38-90v-384q0-52 38-90t90-38h512q52 0 90 38t38 90zm896 768v384q0 52-38 90t-90 38h-512q-52 0-90-38t-38-90v-384q0-52 38-90t90-38h512q52 0 90 38t38 90zm0-768v384q0 52-38 90t-90 38h-512q-52 0-90-38t-38-90v-384q0-52 38-90t90-38h512q52 0 90 38t38 90z"/></svg>
                <svg id="gallery-view" width="20" height="20" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg" style="position:relative;top:5px;left:5px;fill:#333"><path d="M256 1312v192q0 13-9.5 22.5t-22.5 9.5h-192q-13 0-22.5-9.5t-9.5-22.5v-192q0-13 9.5-22.5t22.5-9.5h192q13 0 22.5 9.5t9.5 22.5zm0-384v192q0 13-9.5 22.5t-22.5 9.5h-192q-13 0-22.5-9.5t-9.5-22.5v-192q0-13 9.5-22.5t22.5-9.5h192q13 0 22.5 9.5t9.5 22.5zm0-384v192q0 13-9.5 22.5t-22.5 9.5h-192q-13 0-22.5-9.5t-9.5-22.5v-192q0-13 9.5-22.5t22.5-9.5h192q13 0 22.5 9.5t9.5 22.5zm1536 768v192q0 13-9.5 22.5t-22.5 9.5h-1344q-13 0-22.5-9.5t-9.5-22.5v-192q0-13 9.5-22.5t22.5-9.5h1344q13 0 22.5 9.5t9.5 22.5zm-1536-1152v192q0 13-9.5 22.5t-22.5 9.5h-192q-13 0-22.5-9.5t-9.5-22.5v-192q0-13 9.5-22.5t22.5-9.5h192q13 0 22.5 9.5t9.5 22.5zm1536 768v192q0 13-9.5 22.5t-22.5 9.5h-1344q-13 0-22.5-9.5t-9.5-22.5v-192q0-13 9.5-22.5t22.5-9.5h1344q13 0 22.5 9.5t9.5 22.5zm0-384v192q0 13-9.5 22.5t-22.5 9.5h-1344q-13 0-22.5-9.5t-9.5-22.5v-192q0-13 9.5-22.5t22.5-9.5h1344q13 0 22.5 9.5t9.5 22.5zm0-384v192q0 13-9.5 22.5t-22.5 9.5h-1344q-13 0-22.5-9.5t-9.5-22.5v-192q0-13 9.5-22.5t22.5-9.5h1344q13 0 22.5 9.5t9.5 22.5z"/></svg>
              </p>
              <div class="selectables"></div>
            </div></div>
            <div><div style="display:none;min-height:400px" id="ajax-images">
              <h2 class="mb-4">Select an image</h2>
              <p><a id="prev-image" href="" class="btn btn-outline-primary">&#8592; Previous page</a> <a id="next-image" href="" class="btn btn-outline-primary float-right">&#8594; Next page</a></p>
              <div class="selectables"></div>
            </div></div>
            <div><div style="display:none" id="content-categories">
              <h2 class="mb-4">Select a content category</h2>
              <div class="card-columns">
                <div class="card bg-dark text-white">
                  <img class="card-img" src="https://environmentaldashboard.org/cv_slides/categorybars/heritage.png" data-id="4" alt="Card image" />
                </div>
                <div class="card bg-dark text-white">
                  <img class="card-img" src="https://environmentaldashboard.org/cv_slides/categorybars/nature_photos.png" data-id="5" alt="Card image" />
                </div>
                <div class="card bg-dark text-white">
                  <img class="card-img" src="https://environmentaldashboard.org/cv_slides/categorybars/neighbors.png" data-id="6" alt="Card image" />
                </div>
                <div class="card bg-dark text-white">
                  <img class="card-img" src="https://environmentaldashboard.org/cv_slides/categorybars/next-generation.png" data-id="3" alt="Card image" />
                </div>
                <div class="card bg-dark text-white">
                  <img class="card-img" src="https://environmentaldashboard.org/cv_slides/categorybars/our-downtown.png" data-id="2" alt="Card image" />
                </div>
                <div class="card bg-dark text-white">
                  <img class="card-img" src="https://environmentaldashboard.org/cv_slides/categorybars/serving-our-community.png" data-id="1" alt="Card image" />
                </div>
              </div>
            </div></div>

          </div>
        </div>

        <!-- <div class="mb-5 mt-5"><div id="accordion" class="mb-5">
          <div class="card">
            <div class="card-header" id="headingOne">
              <h5 class="mb-0">
                <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#pick-quote" aria-expanded="true" aria-controls="pick-quote">
                  Select a quote
                </button>
              </h5>
            </div>

            <div id="pick-quote" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
              <div class="card-body">
                <div class="card-columns" id="ajax-quotes"> -->
                  <!-- <div class="card p-3">
                    <blockquote class="blockquote mb-0 card-body">
                      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                      <footer class="blockquote-footer">
                        <small class="text-muted">
                          Someone famous in <cite title="Source Title">Source Title</cite>
                        </small>
                      </footer>
                    </blockquote>
                  </div> -->
                <!-- </div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header" id="headingTwo">
              <h5 class="mb-0">
                <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#pick-image" aria-expanded="false" aria-controls="pick-image">
                  Select an image
                </button>
              </h5>
            </div>
            <div id="pick-image" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
              <div class="card-body">
                <div class="card-columns" id="ajax-images"> -->
                  <!-- <div class="card bg-dark text-white">
                    <img class="card-img" src=".../100px270/#55595c:#373a3c/text:Card image" alt="Card image" />
                    <div class="card-img-overlay">
                      <h5 class="card-title">Card title</h5>
                      <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                      <p class="card-text">Last updated 3 mins ago</p>
                    </div>
                  </div> -->
                <!-- </div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header" id="headingThree">
              <h5 class="mb-0">
                <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#pick-category" aria-expanded="false" aria-controls="pick-category">
                  Select a content category
                </button>
              </h5>
            </div>
            <div id="pick-category" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
              <div class="card-body">
                <div class="card-columns">
                  <div class="card bg-dark text-white">
                    <img class="card-img" src="https://environmentaldashboard.org/cv_slides/categorybars/heritage.png" alt="Card image" />
                  </div>
                  <div class="card bg-dark text-white">
                    <img class="card-img" src="https://environmentaldashboard.org/cv_slides/categorybars/nature_photos.png" alt="Card image" />
                  </div>
                  <div class="card bg-dark text-white">
                    <img class="card-img" src="https://environmentaldashboard.org/cv_slides/categorybars/neighbors.png" alt="Card image" />
                  </div>
                  <div class="card bg-dark text-white">
                    <img class="card-img" src="https://environmentaldashboard.org/cv_slides/categorybars/next-generation.png" alt="Card image" />
                  </div>
                  <div class="card bg-dark text-white">
                    <img class="card-img" src="https://environmentaldashboard.org/cv_slides/categorybars/our-downtown.png" alt="Card image" />
                  </div>
                  <div class="card bg-dark text-white">
                    <img class="card-img" src="https://environmentaldashboard.org/cv_slides/categorybars/serving-our-community.png" alt="Card image" />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div></div> -->
        
        <h2 class="mt-2" style="margin-bottom:-10px">Preview</h2>
        <svg height="1080" width="1920" style="width:100%;height:auto" viewBox="0 0 100 50" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><rect width="100%" height="100%" style="fill:rgb(0,0,0)" /><g id="render"></g></svg>
      </div>
    </div>
    </xsl:template>

</xsl:stylesheet>

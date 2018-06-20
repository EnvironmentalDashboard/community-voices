<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:output method="xml" indent="yes" omit-xml-declaration="yes" />

    <xsl:template match="/form">

        <form action='./images/new/authenticate' method='post'>

            <div id="accordion">
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
                    <div class="card-columns" id="ajax-quotes">
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
                    </div>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header" id="headingTwo">
                  <h5 class="mb-0">
                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#pick-img" aria-expanded="false" aria-controls="pick-img">
                      Select an image
                    </button>
                  </h5>
                </div>
                <div id="pick-img" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                  <div class="card-body">
                    <div class="card-columns" id="ajax-images">
                      <!-- <div class="card bg-dark text-white">
                        <img class="card-img" src=".../100px270/#55595c:#373a3c/text:Card image" alt="Card image" />
                        <div class="card-img-overlay">
                          <h5 class="card-title">Card title</h5>
                          <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                          <p class="card-text">Last updated 3 mins ago</p>
                        </div>
                      </div> -->
                    </div>
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
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                  </div>
                </div>
              </div>
            </div>

            <input type='submit' class="btn btn-primary" />
        </form>
    </xsl:template>

</xsl:stylesheet>

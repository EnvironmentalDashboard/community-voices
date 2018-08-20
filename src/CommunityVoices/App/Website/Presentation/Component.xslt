<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:template name="common-header">
      <div class="row">
        <div class="col banner-col">
          <a href="/"><img src="https://environmentaldashboard.org/images/banner.jpg" alt="" class="img-fluid" /></a>
        </div>
      </div>
      <nav class="navbar sticky-top navbar-expand-lg navbar-dark" style="background: #21a7df;padding-top: 0px;padding-bottom: 0px">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
          <ul class="navbar-nav">
            <li class="nav-item dropdown" id="hover1">
              <a class="nav-link dropdown-toggle" href="/cwd" id="navbarDropdownMenuLink1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Citywide Dashboard
            </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink1" id="hover_target1">
                <a class="dropdown-item" href="/cwd">Citywide View</a>
                <a class="dropdown-item" href="http://buildingdashboard.net/oberlincity/#/oberlincity/cityelectricity/">Electricity</a>
                <a class="dropdown-item" href="http://buildingdashboard.net/oberlincity/#/oberlincity/citywateruse">Water Flow</a>
                <a class="dropdown-item" href="http://buildingdashboard.net/oberlincity/#/oberlincity/citywaterquality">Water Quality</a>
                <a class="dropdown-item" href="http://buildingdashboard.net/oberlin/#/oberlin/spearpoint">10 Acre Solar Array</a>
                <a class="dropdown-item" href="/gauges-explained">Gauges explained</a>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="/building-dashboard-explained" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Building Dashboard
            </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">
                <a class="dropdown-item" href="/building-dashboard-explained">Building Dashboard Explained</a>

                <div class="dropdown">
                  <a class="dropdown-item dropdown-toggle" href="#" id="navbarDropdownMenuLink3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Oberlin City</a>
                  <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink3">
                    <li><a class="dropdown-item" href="https://buildingos.com/s/oberlincity/storyboard314/?chapterId=1390">Eastwood Elementary</a></li>
                    <li><a class="dropdown-item" href="https://buildingos.com/s/oberlincity/storyboard314/?chapterId=1391">Prospect Elementary</a></li>
                    <li><a class="dropdown-item" href="https://buildingos.com/s/oberlincity/storyboard314/?chapterId=1392">Langston Middle School</a></li>
                    <li><a class="dropdown-item" href="https://buildingos.com/s/oberlincity/storyboard314/?chapterId=1393">Oberlin High School</a></li>
                    <li><a class="dropdown-item" href="https://buildingos.com/s/oberlincity/storyboard314/?chapterId=1394">Langston Board Office</a></li>
                    <li><a class="dropdown-item" href="https://buildingos.com/s/oberlincity/storyboard314/?chapterId=1395">Oberlin Public Library</a></li>
                  </ul>
                </div>

                <div class="dropdown">
                  <a class="dropdown-item dropdown-toggle" href="#" id="navbarDropdownMenuLink4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Oberlin College</a>
                  <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink4">
                    <li><a class="dropdown-item" href="https://buildingos.com/reports/dashboards/282f6022666d11e7a61b525400d1fc46">College Dormitories</a></li>
                    <li><a class="dropdown-item" href="https://palmer.buildingos.com/reports/dashboards/9cb16078634111e7985c525400e84168">AJLC</a></li>
                    <li><a class="dropdown-item" href="https://buildingos.com/reports/dashboards/bc9d7c1a664c11e784ef525400d1fc46">Bosworth</a></li>
                    <li><a class="dropdown-item" href="https://buildingos.com/reports/dashboards/75d11a74664e11e78654525400ac4414">Cox Administration</a></li>
                    <li><a class="dropdown-item" href="https://buildingos.com/reports/dashboards/12ef3f22634b11e79136525400ac67dc">Alumni Office</a></li>
                  </ul>
                </div>
                <a href="https://palmer.buildingos.com/reports/dashboards/c59fde5ec0db11e7aff5525400391da3" class="dropdown-item" target="_blank">Toledo Public Schools</a>
              </div>
            </li>
            <!-- <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink5" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Community Voices
            </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink5">
                <a class="dropdown-item" href="/cv/landing">Home</a>
                <a class="dropdown-item" href="/cv/images">Images</a>
                <a class="dropdown-item" href="/cv/quotes">Quotes</a>
                <a class="dropdown-item" href="/cv/slides">Slides</a>
                <a class="dropdown-item" href="/cv/articles">Articles</a>
                <xsl:choose>
                  <xsl:when test="identity/user/id &gt; 0">
                    <a class="dropdown-item" href="/cv/logout">Logout <xsl:value-of select="identity/user/firstName" /></a>
                    <a class="dropdown-item">
                      <xsl:attribute name="href">user/<xsl:value-of select="identity/user/id" /></xsl:attribute>
                      View Account
                    </a>
                  </xsl:when>
                  <xsl:otherwise>
                    <a class="dropdown-item" href="/cv/login">Login</a>
                    <a class="dropdown-item" href="/cv/register">Register</a>
                  </xsl:otherwise>
                </xsl:choose>
              </div>
            </li> -->
            <li class="nav-item">
              <a class="nav-link" href="/cv/">Community Voices</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/calendar">Events Calendar</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              More
            </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink6" style="left: -250%">
                <a class="dropdown-item" href="/resources-explained">Resources Explained</a>
                <a class="dropdown-item" href="/story-of-dashboard">Story of Dashboard</a>
                <a class="dropdown-item" href="/bring-dashboard-to-your-community">Bring Dashboard to Your Community</a>

                <div class="dropdown">
                  <a class="dropdown-item dropdown-toggle" href="#" id="navbarDropdownMenuLink7" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Instructor Toolkit</a>
                  <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink7">
                    <li><a class="dropdown-item" href="/edresources">About</a></li>
                    <li><a class="dropdown-item" href="/edresources/searchedresources">Search K12</a></li>
                    <!-- <li><a class="dropdown-item" href="/edresources/workshops">Teacher Workshop</a></li> -->
                  </ul>
                </div>

                <div class="dropdown">
                  <a class="dropdown-item dropdown-toggle" href="#" id="navbarDropdownMenuLink8" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">About Us</a>
                  <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink8">
                    <li><a class="dropdown-item" href="/mission">Mission</a></li>
                    <li><a class="dropdown-item" href="/meet-the-team">Meet the Team</a></li>
                    <!-- <li><a class="dropdown-item" href="#">In the News</a></li> -->
                  </ul>
                </div>

              </div>
            </li>
          </ul>
        </div>
      </nav>
    </xsl:template>

    <xsl:template name="common-footer">
      <div class="row text-center justify-content-md-center">
        <div class="col-3 col-md-1">
          <img src="https://environmentaldashboard.org/images/uploads/2015/08/sf1-300x227.jpg" alt="" class="img-fluid grow" />
        </div>
        <div class="col-3 col-md-1">
          <img src="https://environmentaldashboard.org/images/uploads/2015/08/op1-300x162.jpg" alt="" class="img-fluid grow" />
        </div>
        <div class="col-3 col-md-1">
          <img src="https://environmentaldashboard.org/images/uploads/2015/08/glpf1-297x300.jpg" alt="" class="img-fluid grow" />
        </div>
        <div class="col-3 col-md-1">
          <img src="https://environmentaldashboard.org/images/uploads/2015/07/ob-300x300.jpg" alt="" class="img-fluid grow" />
        </div>
        <div class="col-3 col-md-1">
          <img src="https://environmentaldashboard.org/images/uploads/2015/07/epa-300x300.jpg" alt="" class="img-fluid grow" />
        </div>
        <div class="col-3 col-md-1">
          <img src="https://environmentaldashboard.org/images/uploads/2015/07/glca-300x300.jpg" alt="" class="img-fluid grow" />
        </div>
        <div class="col-3 col-md-1">
          <img src="https://environmentaldashboard.org/images/uploads/2015/08/lucid-300x252.jpg" alt="" class="img-fluid grow" />
        </div>
        <div class="col-3 col-md-1">
          <img src="https://environmentaldashboard.org/images/uploads/2015/07/oc-300x300.jpg" alt="" class="img-fluid grow" />
        </div>
      </div>
      <div class="row">
        <div class="col">
          <p class="text-muted text-center" style="margin-top: 30px">Oberlin College 2018 | <a href="mailto:dashboard@oberlin.edu" style="color: #6c757d; text-decoration: underline;">Contact Us</a></p>
        </div>
      </div>
      <div class="row">
        <div class="col text-center" style="padding-bottom: 50px">
          <a href="#" class="btn btn-primary mr-2" style="height: 35px;width: 35px;padding: 5px"><img src="https://environmentaldashboard.org/images/facebook-f.svg" alt="Environmental Dashboard on Facebook" style="height: 100%" /></a>
          <a href="#" class="btn btn-primary" style="height: 35px;width: 35px;padding: 5px"><img src="https://environmentaldashboard.org/images/twitter.svg" style="height: 100%" alt="Environmental Dashboard on Twitter" /></a>
        </div>
      </div>

    </xsl:template>


</xsl:stylesheet>

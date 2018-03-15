<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	version="1.0">

	<xsl:output method="xml" indent="yes" omit-xml-declaration="yes" />

	<xsl:variable name="isManager" select="package/identity/user/role = 'manager'
		or package/identity/user/role = 'administrator'"/>

		<xsl:template match="/package">

				<div class="middle">

          <div id="cvSlider" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
              <li data-target="#cvSlider" data-slide-to="0" class="active"></li>
              <li data-target="#cvSlider" data-slide-to="1"></li>
              <li data-target="#cvSlider" data-slide-to="2"></li>
              <li data-target="#cvSlider" data-slide-to="3"></li>
              <li data-target="#cvSlider" data-slide-to="4"></li>
            </ol>
            <div class="carousel-inner">
              <?php $i = 0; foreach ($urls[$galleries[0]] as $url) {
                echo ($i++ === 0) ? '<div class="carousel-item active">' : '<div class="carousel-item">';
                echo "<img class='d-block w-100' src='{$url}' alt='Slide {$i}' id='slide{$i}'></div>";
              } ?>
            </div>
            <a class="carousel-control-prev" href="#cvSlider" role="button" data-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#cvSlider" role="button" data-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="sr-only">Next</span>
            </a>
          </div>

				</div>

	</xsl:template>

</xsl:stylesheet>

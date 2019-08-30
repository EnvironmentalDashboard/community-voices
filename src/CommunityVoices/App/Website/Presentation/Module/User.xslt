<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

	<xsl:import href="../Component/Navbar.xslt" />
    <xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

    <xsl:template match="/package">
      <xsl:call-template name="navbar">
        <xsl:with-param name="active">
          Users
        </xsl:with-param>
      </xsl:call-template>

    	<div class="row" style="padding:15px;">
        <div class="col-12">
        	<h1>Hello, <xsl:value-of select='domain/user/firstName' /><xsl:text> </xsl:text><xsl:value-of select='domain/user/lastName' /></h1>
          <p>Role: <xsl:value-of select="domain/user/role" /></p>
        	<!-- <form action="/community-voices/register/invite" class="mt-5 form-inline needs-validation" method="POST" novalidate="" id="form">
        		<p class="mr-sm-3">Invite other users by email:</p>
        		<label class="sr-only" for="email">Email</label>
					  <input type="email" class="form-control mb-2 mr-sm-2" id="email" name="email" placeholder="Email" required="" />
					  <div class="invalid-feedback">
		          Please enter an email
		        </div>
				  	<label class="my-1 mr-2" for="role">Role</label>
					  <select class="custom-select mb-2 mr-sm-2" id="role" style="max-width:200px" name="role">
					    <option selected="selected">Choose...</option>
					    <option value="2">User</option>
					    <option value="3">Manager</option>
					    <option value="4">Admin</option>
					  </select>
				  	<button type="submit" class="btn btn-primary mb-2">Submit</button>
        	</form> -->

          <form action="/community-voices/users/{domain/user/id}" class="form-inline">
            <div class="form-group">
              <label for="role">Change role to </label>

              <select class="form-control" id="role" name="role">
                <xsl:for-each select="domain/roles/role">
                  <option value="{value}"><xsl:value-of select="name" /></option>
                </xsl:for-each>
              </select>

              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </form>
				</div>
			</div>

    </xsl:template>

</xsl:stylesheet>

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  version="1.0">

  <xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

  <xsl:template match="domain/errors/*">
      <li style="margin-bottom: 0px;"><xsl:value-of select="." /></li>
  </xsl:template>

  <xsl:template match="/form">
    <form action='/community-voices/register' method='post' class="mt-3 p-5 mx-auto needs-validation" style="max-width:800px;" novalidate="">
      <h1 class="h3 mb-3 font-weight-normal">Create an account</h1>

      <xsl:if test="domain/errors != ''">
          <div class="card" style="margin-bottom: 16px;">
              <div class="card-body">
                  <h1 class="h4 mb-4 font-weight-normal" style="margin-bottom: 0.5rem !important;">Some errors prevented registration</h1>
                  <ul style="margin-bottom: 0.5rem;">
                      <xsl:apply-templates select="domain/errors/*" />
                  </ul>
              </div>
          </div>
      </xsl:if>

      <div class="form-group row">
        <label for="email" class="col-sm-3 col-form-label">Email</label>
        <div class="col-sm-9">
            <xsl:choose>
                <xsl:when test="@email-value">
                    <input type="email" class="form-control" id="email" value="{@email-value}" name="email" required="" />
                </xsl:when>
                <xsl:otherwise>
                    <input type="email" class="form-control" id="email" name="email" required="" />
                </xsl:otherwise>
            </xsl:choose>
          <div class="invalid-feedback">
            Please provide a valid email
          </div>
          <div class="valid-feedback">
            Looks good!
          </div>
        </div>
      </div>
      <div class="form-group row">
        <label for="password" class="col-sm-3 col-form-label">Password</label>
        <div class="col-sm-9">
          <input type="password" class="form-control" id="password" name="password" required="" title="6 character minimum">
            <xsl:attribute name="pattern">.{6,}</xsl:attribute>
          </input>
          <div class="invalid-feedback">
            6 character minimum
          </div>
          <div class="valid-feedback">
            Looks good!
          </div>
        </div>
      </div>
      <div class="form-group row">
        <label for="confirmPassword" class="col-sm-3 col-form-label">Confirm Password</label>
        <div class="col-sm-9">
          <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required="" title="6 character minimum">
            <xsl:attribute name="pattern">.{6,}</xsl:attribute>
          </input>
          <div class="invalid-feedback">
            6 character minimum
          </div>
          <div class="valid-feedback">
            Looks good!
          </div>
        </div>
      </div>
      <div class="form-group row">
        <label for="firstName" class="col-sm-3 col-form-label">First Name</label>
        <div class="col-sm-9">
            <xsl:choose>
                <xsl:when test="@firstName-value">
                    <input type="text" class="form-control" id="firstName" value="{@firstName-value}" name="firstName" required="" />
                </xsl:when>
                <xsl:otherwise>
                    <input type="text" class="form-control" id="firstName" name="firstName" required="" />
                </xsl:otherwise>
            </xsl:choose>
          <div class="invalid-feedback">
            Please provide a first name
          </div>
          <div class="valid-feedback">
            Looks good!
          </div>
        </div>
      </div>
      <div class="form-group row">
        <label for="lastName" class="col-sm-3 col-form-label">Last Name</label>
        <div class="col-sm-9">
            <xsl:choose>
                <xsl:when test="@lastName-value">
                    <input type="text" class="form-control" id="lastName" value="{@lastName-value}" name="lastName" required="" />
                </xsl:when>
                <xsl:otherwise>
                    <input type="text" class="form-control" id="lastName" name="lastName" required="" />
                </xsl:otherwise>
            </xsl:choose>
          <div class="invalid-feedback">
            Please provide a last name
          </div>
          <div class="valid-feedback">
            Looks good!
          </div>
        </div>
      </div>
      <input type="hidden" name="token" value="{@token-value}"/>
      <div class="form-group row">
        <div class="col-sm-3"></div>
        <div class="col-sm-9">
          <button type="submit" class="btn btn-primary">Register</button>
        </div>
      </div>
    </form>
  </xsl:template>

</xsl:stylesheet>

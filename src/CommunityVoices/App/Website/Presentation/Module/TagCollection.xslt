<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:import href="../Component/Navbar.xslt" />
    <xsl:output method="html" indent="yes" omit-xml-declaration="yes" />

    <xsl:variable name="isManager" select="package/identity/user/role = 'manager'
        or package/identity/user/role = 'administrator'"/>

<xsl:template match="/package">
    <xsl:if test="$isManager">
        <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action='tags/new' method='post' enctype='multipart/form-data'>
                        <div class="modal-header">
                            <h5 class="modal-title" id="createModalLabel">Upload Tags</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&#215;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="title">Text</label>
                                <input class="form-control" id="text" type='text' name='text' />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /modal -->
        <div class="alert alert-dismissible fade show d-none" role="alert" id="alert" style="top: 20px;right: 15%;left: 15%;width: 70%;position:fixed;z-index:9999">
            <span id="alert-content"></span>
            <button type="button" class="close" aria-label="Close" onclick="$(this).closest('.alert').addClass('d-none')">
                <span aria-hidden="true">&#215;</span>
            </button>
        </div>
    </xsl:if>
    <xsl:call-template name="navbar">
        <xsl:with-param name="active">
            Tags
        </xsl:with-param>
        <xsl:with-param name="rightButtons">
            <xsl:if test="$isManager">
                <a class="btn btn-outline-primary mr-2" href="/community-voices/tags/new" data-toggle="modal" data-target="#createModal">+ Add tag</a>
            </xsl:if>

            <xsl:call-template name="userButtons" />
        </xsl:with-param>
    </xsl:call-template>
    <div class="container-fluid">

        <xsl:for-each select="domain/tagCollection/tag">
            <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <a href="/community-voices/quotes?tags[]={id}" class="btn btn-light" data-toggle="tooltip" data-placement="top" title="View Related Quotes">
                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-chat-left-quote-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4.414a1 1 0 0 0-.707.293L.854 15.146A.5.5 0 0 1 0 14.793V2zm7.194 2.766c.087.124.163.26.227.401.428.948.393 2.377-.942 3.706a.446.446 0 0 1-.612.01.405.405 0 0 1-.011-.59c.419-.416.672-.831.809-1.22-.269.165-.588.26-.93.26C4.775 7.333 4 6.587 4 5.667 4 4.747 4.776 4 5.734 4c.271 0 .528.06.756.166l.008.004c.169.07.327.182.469.324.085.083.161.174.227.272zM11 7.073c-.269.165-.588.26-.93.26-.958 0-1.735-.746-1.735-1.666 0-.92.777-1.667 1.734-1.667.271 0 .528.06.756.166l.008.004c.17.07.327.182.469.324.085.083.161.174.227.272.087.124.164.26.228.401.428.948.392 2.377-.942 3.706a.446.446 0 0 1-.613.01.405.405 0 0 1-.011-.59c.42-.416.672-.831.81-1.22z"/>
                                </svg>
                            </a>
                            <a href="/community-voices/images?tags[]={id}" class="btn btn-light" data-toggle="tooltip" data-placement="top" title="View Related Images">
                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-card-image" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M14.5 3h-13a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
                                    <path d="M10.648 7.646a.5.5 0 0 1 .577-.093L15.002 9.5V13h-14v-1l2.646-2.354a.5.5 0 0 1 .63-.062l2.66 1.773 3.71-3.71z"/>
                                    <path fill-rule="evenodd" d="M4.502 7a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
                                </svg>
                            </a>
                            <a href="/community-voices/slides?tags[]={id}" class="btn btn-light" data-toggle="tooltip" data-placement="top" title="View Related Slides">
                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-stickies" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M0 1.5A1.5 1.5 0 0 1 1.5 0H13a1 1 0 0 1 1 1H1.5a.5.5 0 0 0-.5.5V14a1 1 0 0 1-1-1V1.5z"/>
                                    <path fill-rule="evenodd" d="M2 3.5A1.5 1.5 0 0 1 3.5 2h11A1.5 1.5 0 0 1 16 3.5v6.086a1.5 1.5 0 0 1-.44 1.06l-4.914 4.915a1.5 1.5 0 0 1-1.06.439H3.5A1.5 1.5 0 0 1 2 14.5v-11zM3.5 3a.5.5 0 0 0-.5.5v11a.5.5 0 0 0 .5.5h6.086a.5.5 0 0 0 .353-.146l4.915-4.915A.5.5 0 0 0 15 9.586V3.5a.5.5 0 0 0-.5-.5h-11z"/>
                                    <path fill-rule="evenodd" d="M10.5 10a.5.5 0 0 0-.5.5v5H9v-5A1.5 1.5 0 0 1 10.5 9h5v1h-5z"/>
                                </svg>
                            </a>

                            <xsl:choose>
                                <xsl:when test="$isManager">
                                    <form action="/community-voices/api/tags/{id}/delete" method="POST" class="delete-form" id="delete-form{id}">
                                        <button type="submit" class="btn btn-light" data-toggle="tooltip" data-placement="top" title="Delete">
                                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5a.5.5 0 0 0-1 0v7a.5.5 0 0 0 1 0v-7z"/>
                                            </svg>
                                        </button>
                                    </form>
                                    <form action="/community-voices/api/tags/{id}/edit" method="POST" class="edit-form" id="edit-form{id}">
                                        <button type="submit" form="edit-form{id}" class="btn btn-light" data-toggle="tooltip" data-placement="top" title="Update">
                                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-clockwise" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M3.17 6.706a5 5 0 0 1 7.103-3.16.5.5 0 1 0 .454-.892A6 6 0 1 0 13.455 5.5a.5.5 0 0 0-.91.417 5 5 0 1 1-9.375.789z"/>
                                                <path fill-rule="evenodd" d="M8.147.146a.5.5 0 0 1 .707 0l2.5 2.5a.5.5 0 0 1 0 .708l-2.5 2.5a.5.5 0 1 1-.707-.708L10.293 3 8.147.854a.5.5 0 0 1 0-.708z"/>
                                            </svg>
                                        </button>
                                    </form>
                                </xsl:when>
                            </xsl:choose>

                        </div>
                    </div>
                <xsl:choose>
                    <xsl:when test="$isManager">
                        <input type="text" name="label" class="form-control" form="edit-form{id}">
                            <xsl:attribute name="value"><xsl:value-of select="label"></xsl:value-of></xsl:attribute>
                        </input>
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:value-of select="label"></xsl:value-of>
                    </xsl:otherwise>
                </xsl:choose>
            </div>
        </xsl:for-each>
    </div>
</xsl:template>
</xsl:stylesheet>

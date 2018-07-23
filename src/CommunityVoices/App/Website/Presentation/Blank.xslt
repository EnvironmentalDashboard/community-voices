<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:output method="html" doctype-system="about:legacy-compat"/>

    <xsl:template match="/domain">

    <html>
        <head>
            <meta charset="utf-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
            <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png?v=9ByOqqx0o3" />
            <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png?v=9ByOqqx0o3" />
            <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png?v=9ByOqqx0o3" />
            <link rel="manifest" href="/manifest.json?v=9ByOqqx0o3" />
            <link rel="mask-icon" href="/safari-pinned-tab.svg?v=9ByOqqx0o3" color="#00a300" />
            <link rel="shortcut icon" href="/favicon.ico?v=9ByOqqx0o3" />
            <meta name="theme-color" content="#000000" />
            <title> <xsl:value-of select="title" /> </title>
            <style>
                * { box-sizing:border-box }
                html, body { height: 100%; font-family:Comfortaa, sans-serif; }
            </style>

            <link href="https://fonts.googleapis.com/css?family=Comfortaa:400,700" rel="stylesheet" />
        </head>
        <body style="background:#000;margin:0;padding:0;">
            <xsl:value-of select="main-pane" disable-output-escaping="yes" />
        </body>
    </html>

    </xsl:template>

</xsl:stylesheet>

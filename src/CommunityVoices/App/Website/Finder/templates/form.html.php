<!doctype html>

<html>
    <head>
        <style type="text/css">
            * {
                font-family: arial;
                font-size: 14pt;
            }

            .page-wrap {
                width: 500px;
                margin: 0 auto;
            }
        </style>

        <title>Image Finder</title>
    </head>
    <body>
        <div class="page-wrap">
            <h1>Similar Image Finder</h1>
            <p>Upload an image and submit. This application will list the ten most similar images in our databases, detected via perceptual hashing.</p>
            <form method="post" action="" enctype="multipart/form-data">
                <input type="file" name="image"><input type="submit" name="submit">
            </form>
        </div>
    </body>
</html>

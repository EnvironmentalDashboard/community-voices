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

            .image {
                border: 1px solid #ccc;
                padding: 15px;
                margin: 15px 0;
            }

            .image.exact-match {
                border: 5px solid green;
                padding: 11px;
            }
        </style>

        <title>Image Finder</title>
    </head>
    <body>
        <div class="page-wrap">

            <h1>Image Matches</h1>
            <p>The following images best match your input.</p>

            <p><a href="">Match another image.</a></p>

            <?php foreach ($matches as $image): ?>

                <?php include('matches-image-element.html.php'); ?>

            <?php endforeach; ?>
        </div>
    </body>
</html>

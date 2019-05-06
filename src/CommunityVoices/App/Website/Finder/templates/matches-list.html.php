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
        </style>

        <title>Image Finder</title>
    </head>
    <body>
        <div class="page-wrap">

            <p><a href="">Match another image.</a></p>

            <?php foreach ($matches as $image): ?>

                <div class="image">
                    <p>
                        <img src="https://environmentaldashboard.org/community-voices/uploads/<?=$image->media_id ?>" width="100%">
                    <p>

                    <p>Image ID: <strong><?=$image->media_id ?></strong></p>
                    <p>Hash: <strong><?=strtolower($image->conv_hash) ?></strong></p>
                    <p>Distance: <strong><?=$image->d ?></strong></p>
                    <p><a href="https://environmentaldashboard.org/community-voices/images/<?=$image->media_id ?>" target="_blank">Open image in new tab</a></p>
                </div>

            <?php endforeach; ?>
        </div>
    </body>
</html>

<?php
//TODO: Select approved only slides
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require dirname(__DIR__) . '/App/Website/db.php';

$contentCategoryQuery = 'SELECT group_id FROM `community-voices_content-categories`';
$DEFAULT_PROBABILITY = 5;
$galleries = [];

foreach ($dbHandler->query($contentCategoryQuery) as $row) {
  $galleries[$row['group_id']] = $DEFAULT_PROBABILITY;
}

$gallery_names = array_keys($galleries);
foreach ($galleries as $gallery => $numerator) {
    if (isset($_GET[$gallery])) {
        $galleries[$gallery] = $_GET[$gallery];
    }
}
if (isset($_GET['search'])) {
  $param = "'%{$_GET['search']}%'";
  $search_quote_query = 'AND quote_id IN (SELECT `community-voices_quotes`.media_id FROM `community-voices_quotes` WHERE text LIKE '.($param).' OR attribution LIKE '.($param).' OR sub_attribution LIKE '.($param).')';
  $search_image_query = 'OR image_id IN (SELECT `community-voices_images`.media_id FROM `community-voices_images` WHERE title LIKE '.($param).' OR description LIKE '.($param).' OR photographer LIKE '.($param).' OR organization LIKE '.($param).')';
  $search_query = $search_quote_query . ' ' . $search_image_query;
} else {
  $search_query = '';
}
if (isset($_GET['tags'])) {
  $sanitized_tags = implode(',', array_map('intval', $_GET['tags']));
  $tag_query = 'AND (quote_id IN (SELECT media_id FROM `community-voices_media-group-map` WHERE group_id IN ('.$sanitized_tags.')) OR image_id IN (SELECT media_id FROM `community-voices_media-group-map` WHERE group_id IN ('.$sanitized_tags.')) )';
} else {
  $tag_query = '';
}
if (isset($_GET['attributions'])) {
  $attributions = implode("','", $_GET['attributions']);
  $attribution_query = "AND quote_id IN (SELECT media_id FROM `community-voices_quotes` WHERE attribution IN ('".($attributions)."'))";
} else {
  $attribution_query = '';
}
if (isset($_GET['photographers'])) {
  $photographers = implode("','", $_GET['photographers']);
  $photographer_query = "AND image_id IN (SELECT media_id FROM `community-voices_images` WHERE photographer IN ('".($photographers)."'))";
} else {
  $photographer_query = '';
}
if (isset($_GET['orgs'])) {
  $orgs = implode("','", $_GET['orgs']);
  $org_query = "AND image_id IN (SELECT media_id FROM `community-voices_images` WHERE organization IN ('".($orgs)."'))";
} else {
  $org_query = '';
}
if (isset($_GET['content_category'])) {
  $contentCategories = implode(',', array_map('intval', $_GET['content_category']));
  $content_category_query = 'AND content_category_id IN ('.($contentCategories).')';
} else {
  $content_category_query = '';
}

$sql = 'SELECT probability, content_category_id, media_id, quote_id, image_id FROM `community-voices_slides` WHERE probability > 0 ' . $content_category_query . $search_query . $tag_query . $attribution_query . $photographer_query . $org_query . ' ORDER BY probability DESC';

$weight_sum = array_sum($galleries);
$sorted_rows = array_fill_keys($gallery_names, []); // list of urls, each duplicated to match its prob/weight
$num_urls = 0;
foreach ($dbHandler->query($sql) as $row) {
    for ($i=0; $i < $row['probability']; $i++) {
        $sorted_rows[$row['content_category_id']][] = "/community-voices/slides/{$row['media_id']}";
    }-
    $num_urls += $row['probability'];
}
$files = [];
foreach ($galleries as $gallery => $weight) {
    shuffle($sorted_rows[$gallery]);
    $allowed_space = ($galleries[$gallery]/$weight_sum);
    $space_so_far = 0;
    foreach ($sorted_rows[$gallery] as $url) {
        $files[] = $url;
        if ($allowed_space <= (($space_so_far++)/$num_urls)) {
            break;
        }
    }
}
shuffle($files);
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png?v=9ByOqqx0o3">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png?v=9ByOqqx0o3">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png?v=9ByOqqx0o3">
    <link rel="manifest" href="/manifest.json?v=9ByOqqx0o3">
    <link rel="mask-icon" href="/safari-pinned-tab.svg?v=9ByOqqx0o3" color="#00a300">
    <link rel="shortcut icon" href="/favicon.ico?v=9ByOqqx0o3">
    <link rel="stylesheet" href="https://environmentaldashboard.org/css/bootstrap.css?v=2">
    <meta name="theme-color" content="#000000">
    <title>Environmental Dashboard</title>
    <style>
      @keyframes fadeIn {
        0% {
          display: none;
          opacity: 0;
        }
        1% {
          display: block;
          opacity: 0;
        }
        100% {
          display: block;
          opacity: 1;
        }
      }
      @keyframes fadeOut {
        0% {
          display: block;
          opacity: 1;
        }
        99% {
          display: block;
          opacity: 0;
        }
        100% {
          display: none;
          opacity: 0;
        }
      }
      .fade-in {
        -webkit-animation: fadeIn 2s linear 0s 1 normal forwards;
        animation: fadeIn 2s linear 0s 1 normal forwards;
      }
      .fade-out {
        -webkit-animation: fadeOut 2s linear 0s 1 normal forwards;
        animation: fadeOut 2s linear 0s 1 normal forwards;
      }
      iframe {
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        border: none;
      }
    </style>
  </head>
  <body style="background: #000">
    <iframe id='iframe1' src="<?php echo $files[0]; ?>"></iframe>
    <iframe id="iframe2" src="<?php echo $files[1]; ?>"></iframe>
  </body>
  <script>
    var paths = <?php echo json_encode($files); ?>;
    var images = [document.getElementById('iframe1'), document.getElementById('iframe2')];
    var current_path = 2,
        current_iframe = 1;
    setInterval(function() {
      if (current_iframe === 0) {
        console.log(current_path)
        images[current_iframe].className = 'fade-out';
        setTimeout(function() { images[0].setAttribute('src', paths[current_path++]); }, 2000);
        current_iframe = 1;
        images[current_iframe].className = 'fade-in';
        if (current_path === paths.length) {
          current_path = 0;
        }
      } else {
        console.log(current_path)
        images[current_iframe].className = 'fade-out';
        setTimeout(function() { images[1].setAttribute('src', paths[current_path++]); }, 2000);
        current_iframe = 0;
        images[current_iframe].className = 'fade-in';
        if (current_path === paths.length) {
          current_path = 0;
        }
      }
    }, <?php echo (isset($_GET['ms'])) ? $_GET['ms'] : 5000 ?>);
  </script>
</html>

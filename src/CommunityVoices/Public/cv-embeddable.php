<?php
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
$sorted_rows = array_fill_keys($gallery_names, []);
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
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Community Voices communication technology combines images and words to advance environmental, social and economic sustainability in diverse communities.">
  <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png?v=9ByOqqx0o3">
  <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png?v=9ByOqqx0o3">
  <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png?v=9ByOqqx0o3">
  <link rel="manifest" href="/manifest.json?v=9ByOqqx0o3">
  <link rel="mask-icon" href="/safari-pinned-tab.svg?v=9ByOqqx0o3" color="#00a300">
  <link rel="shortcut icon" href="/favicon.ico?v=9ByOqqx0o3">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
  <meta name="theme-color" content="#000000">
  <title>Community Voices Embeddable</title>
  <link rel="stylesheet" href="https://environmentaldashboard.org/css/bootstrap.css?v=2">
  <link rel="stylesheet" href="/community-voices/public/css/landing.css">
  <link rel="stylesheet" href="/community-voices/public/css/SinglePane.css">
  <script type="text/javascript" async="" src="https://www.google-analytics.com/analytics.js"></script><script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-65902947-1"></script><script>
              window.dataLayer = window.dataLayer || [];
              function gtag(){dataLayer.push(arguments);}
              gtag('js', new Date());
              gtag('config', 'UA-65902947-1');
              </script>
</head>
<body>
  <div class="row pb-0" style="padding:15px;"><div class="col-12"><div id="carouselIndicators" class="carousel slide" data-ride="carousel" data-interval="7000">
<div class="carousel-inner">
<div class="carousel-item active"><div class="embed-responsive embed-responsive-16by9 mb-4"><iframe class="embed-responsive-item" id="slide1" style="pointer-events: none;" src="<?php echo $files[0]; ?>"></iframe></div></div>
<div class="carousel-item"><div class="embed-responsive embed-responsive-16by9 mb-4"><iframe class="embed-responsive-item" id="slide2" style="pointer-events: none;" src="/community-voices/slides/1507"></iframe></div></div>
<div class="carousel-item"><div class="embed-responsive embed-responsive-16by9 mb-4"><iframe class="embed-responsive-item" id="slide3" style="pointer-events: none;" src="/community-voices/slides/2563"></iframe></div></div>
<div class="carousel-item"><div class="embed-responsive embed-responsive-16by9 mb-4"><iframe class="embed-responsive-item" id="slide4" style="pointer-events: none;" src="/community-voices/slides/4381"></iframe></div></div>
<div class="carousel-item"><div class="embed-responsive embed-responsive-16by9 mb-4"><iframe class="embed-responsive-item" id="slide5" style="pointer-events: none;" src="/community-voices/slides/5832"></iframe></div></div>
</div>
<a class="carousel-control-prev" href="#carouselIndicators" role="button" data-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="sr-only">Previous</span></a><a class="carousel-control-next" href="#carouselIndicators" role="button" data-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="sr-only">Next</span></a>
</div></div></div>
</body>
</html>

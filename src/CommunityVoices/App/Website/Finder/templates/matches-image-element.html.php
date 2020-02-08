<div class="image<?=($image->d === 0 ? ' exact-match' : '') ?>">
    <?php if ($image->d === 0): ?>
    <p>This is an exact match (d = 0).</p>
    <?php endif; ?>

    <p>
        <img src="/community-voices/uploads/<?=$image->media_id ?>" width="100%">
    <p>

    <p>Image ID: <strong><?=$image->media_id ?></strong></p>
    <p>Hash: <strong><?=strtolower($image->conv_hash) ?></strong></p>
    <p>Distance: <strong><?=$image->d ?></strong></p>
    <p><a href="/community-voices/images/<?=$image->media_id ?>" target="_blank">Open image in new tab</a></p>
</div>

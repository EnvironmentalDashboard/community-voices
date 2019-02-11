<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require dirname(__DIR__) . '/App/Website/db.php';

for ($i = 0; $i <= 20; $i++) {
    $statement = $dbHandler->prepare('
        SELECT
            A.media_id as first,
            B.media_id as second,
            BIT_COUNT(A.perceptual_hash ^ B.perceptual_hash) as hamming
        FROM
            `community-voices_images` A,
            `community-voices_images` B
        WHERE
            A.media_id <> B.media_id
            AND BIT_COUNT(A.perceptual_hash ^ B.perceptual_hash) = :distance  ORDER BY hamming
        LIMIT 5
    ');

    $statement->bindParam('distance', $i);
    $statement->execute();

    echo '<h1>Distance of '. $i . '</h1>';

    foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $row) {
        echo '<img src="https://environmentaldashboard.org/community-voices/uploads/'.$row['first'].'" width="30%">';
        echo '<img src="https://environmentaldashboard.org/community-voices/uploads/'.$row['second'].'" width="30%"><br>';
    }
}

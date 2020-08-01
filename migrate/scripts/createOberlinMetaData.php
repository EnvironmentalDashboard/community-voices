<?php

use CommunityVoices\Model\Mapper;
use CommunityVoices\Model\Entity;

$createOberlinMD = "CREATE TABLE `community-voices_oberlin_metadata` (
  `id` int(21) NOT NULL,
  `source_type` enum('document/publication','website','short interview','long form interview','survey') DEFAULT NULL,
  `interviewee_or_source_document` varchar(100) DEFAULT NULL,
  `organization` varchar(100) DEFAULT NULL,
  `sponsor_organization` enum('Metroparks', 'GLSC', 'Mayors Office', 'NEORSD', 'Sustainable Cleveland', 'Cleveland Water Alliance', 'Cleveland 2030') DEFAULT NULL,
  `topic` varchar(100) DEFAULT NULL,
  `interviewee_email` varchar(100) DEFAULT NULL,
  `interviewee_phone` varchar(100) DEFAULT NULL,
  `url_consent_interview` varchar(100) DEFAULT NULL,
  `t1_survey` varchar(100) DEFAULT NULL,
  `t2_survey` varchar(100) DEFAULT NULL,
  `url_transcription` varchar(100) DEFAULT NULL,
  `url_article` varchar(100) DEFAULT NULL,
  `date_article_approved` varchar(100) DEFAULT NULL,
  `url_photograph` varchar(100) DEFAULT NULL,
   PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1";

$statement = $dbHandler->prepare($createOberlinMD);
$statement->execute();

$addMetaData = "ALTER TABLE `community-voices_quotes`
                                ADD COLUMN metadata_id int(21) DEFAULT NULL";
$statement = $dbHandler->prepare($addMetaData);
$statement->execute();

$linkMetaData = "ALTER TABLE `community-voices_quotes`
                                ADD CONSTRAINT `community-voices_quotes_fk1` FOREIGN KEY (`metadata_id`) REFERENCES `community-voices_oberlin_metadata` (`id`) ON DELETE CASCADE ON UPDATE CASCADE";
$statement = $dbHandler->prepare($linkMetaData);
$statement->execute();

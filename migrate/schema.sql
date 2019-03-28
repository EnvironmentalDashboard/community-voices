--
-- Database: `community_voices`
--
CREATE DATABASE IF NOT EXISTS `community_voices` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `community_voices`;

-- --------------------------------------------------------

--
-- Table structure for table `community-voices_articles`
--

CREATE TABLE `community-voices_articles` (
  `media_id` int(21) NOT NULL,
  `image_id` int(21) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `text` text,
  `author` varchar(255) DEFAULT NULL,
  `date_recorded` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `community-voices_content-categories`
--

CREATE TABLE `community-voices_content-categories` (
  `group_id` int(21) NOT NULL,
  `media_filename` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `community-voices_groups`
--

CREATE TABLE `community-voices_groups` (
  `id` int(11) NOT NULL,
  `label` varchar(255) NOT NULL,
  `type` enum('tag','org-category','content-category') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `community-voices_identities`
--

CREATE TABLE `community-voices_identities` (
  `identity_id` int(21) NOT NULL,
  `parent_id` int(21) DEFAULT NULL,
  `account_id` int(21) DEFAULT NULL,
  `type` int(1) NOT NULL,
  `identifier` text NOT NULL,
  `fingerprint` char(96) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `hash` text,
  `created_on` int(11) NOT NULL,
  `used_on` int(11) DEFAULT NULL,
  `expires_on` int(11) DEFAULT NULL,
  `token` char(32) DEFAULT NULL,
  `token_expires_on` int(11) DEFAULT NULL,
  `token_action` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `community-voices_images`
--

CREATE TABLE `community-voices_images` (
  `media_id` int(21) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `generated_tags` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `date_taken` datetime DEFAULT NULL,
  `photographer` varchar(255) DEFAULT NULL,
  `organization` varchar(255) DEFAULT NULL,
  `exif` text,
  `crop_x` int(11) NOT NULL DEFAULT '0',
  `crop_y` int(11) NOT NULL DEFAULT '0',
  `crop_height` int(11) NOT NULL DEFAULT '0',
  `crop_width` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `community-voices_location-category-map`
--

CREATE TABLE `community-voices_location-category-map` (
  `id` int(21) NOT NULL,
  `location_id` int(21) NOT NULL,
  `group_id` int(21) NOT NULL,
  `probability` int(21) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `community-voices_locations`
--

CREATE TABLE `community-voices_locations` (
  `id` int(21) NOT NULL,
  `label` varchar(255) NOT NULL,
  `end_use` enum('city','college') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `community-voices_media`
--

CREATE TABLE `community-voices_media` (
  `id` int(21) NOT NULL,
  `added_by` int(11) DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` enum('slide','image','quote','article') NOT NULL,
  `status` enum('pending','rejected','approved') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `community-voices_media-article-map`
--

CREATE TABLE `community-voices_media-article-map` (
  `id` int(21) NOT NULL,
  `article_id` int(21) NOT NULL,
  `media_id` int(21) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `community-voices_media-group-map`
--

CREATE TABLE `community-voices_media-group-map` (
  `id` int(21) NOT NULL,
  `media_id` int(21) NOT NULL,
  `group_id` int(21) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `community-voices_media-location-map`
--

CREATE TABLE `community-voices_media-location-map` (
  `id` int(21) NOT NULL,
  `media_id` int(21) NOT NULL,
  `loc_id` int(21) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `community-voices_organization-categories`
--

CREATE TABLE `community-voices_organization-categories` (
  `group_id` int(21) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `community-voices_quotes`
--

CREATE TABLE `community-voices_quotes` (
  `media_id` int(21) NOT NULL,
  `text` text,
  `attribution` varchar(255) DEFAULT NULL,
  `sub_attribution` varchar(255) DEFAULT NULL,
  `date_recorded` datetime DEFAULT NULL,
  `public_document_link` varchar(255) DEFAULT NULL,
  `source_document_link` varchar(255) DEFAULT NULL,
  `extra_stuff` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `community-voices_slides`
--

CREATE TABLE `community-voices_slides` (
  `media_id` int(21) NOT NULL,
  `content_category_id` int(21) DEFAULT NULL,
  `image_id` int(21) DEFAULT NULL,
  `quote_id` int(21) DEFAULT NULL,
  `formatted_text` text,
  `probability` int(21) NOT NULL,
  `decay_percent` int(21) DEFAULT NULL,
  `decay_start` datetime DEFAULT NULL,
  `decay_end` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `community-voices_tags`
--

CREATE TABLE `community-voices_tags` (
  `group_id` int(21) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `community-voices_tokens`
--

CREATE TABLE `community-voices_tokens` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `role` tinyint(4) NOT NULL DEFAULT '0',
  `updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `community-voices_users`
--

CREATE TABLE `community-voices_users` (
  `id` int(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `lname` varchar(100) DEFAULT NULL,
  `fname` varchar(100) DEFAULT NULL,
  `role` enum('unverified','user','manager','admin') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `community-voices_articles`
--
ALTER TABLE `community-voices_articles`
  ADD PRIMARY KEY (`media_id`),
  ADD UNIQUE KEY `image_id` (`image_id`);

--
-- Indexes for table `community-voices_content-categories`
--
ALTER TABLE `community-voices_content-categories`
  ADD PRIMARY KEY (`group_id`);

--
-- Indexes for table `community-voices_groups`
--
ALTER TABLE `community-voices_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `community-voices_identities`
--
ALTER TABLE `community-voices_identities`
  ADD PRIMARY KEY (`identity_id`),
  ADD KEY `community-voices_identities_fk1` (`account_id`),
  ADD KEY `parent_id` (`parent_id`,`account_id`,`type`,`fingerprint`,`status`,`expires_on`,`token`,`token_expires_on`,`token_action`);

--
-- Indexes for table `community-voices_images`
--
ALTER TABLE `community-voices_images`
  ADD PRIMARY KEY (`media_id`);

--
-- Indexes for table `community-voices_location-category-map`
--
ALTER TABLE `community-voices_location-category-map`
  ADD PRIMARY KEY (`id`),
  ADD KEY `community-voices_location-category-map_fk0` (`location_id`),
  ADD KEY `community-voices_location-category-map_fk1` (`group_id`);

--
-- Indexes for table `community-voices_locations`
--
ALTER TABLE `community-voices_locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `community-voices_media`
--
ALTER TABLE `community-voices_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `community-voices_media_fk0` (`added_by`);

--
-- Indexes for table `community-voices_media-article-map`
--
ALTER TABLE `community-voices_media-article-map`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `community-voices_media-group-map`
--
ALTER TABLE `community-voices_media-group-map`
  ADD PRIMARY KEY (`id`),
  ADD KEY `community-voices_media-group-map_fk0` (`media_id`),
  ADD KEY `community-voices_media-group-map_fk1` (`group_id`);

--
-- Indexes for table `community-voices_media-location-map`
--
ALTER TABLE `community-voices_media-location-map`
  ADD PRIMARY KEY (`id`),
  ADD KEY `media_id` (`media_id`),
  ADD KEY `loc_id` (`loc_id`);

--
-- Indexes for table `community-voices_organization-categories`
--
ALTER TABLE `community-voices_organization-categories`
  ADD PRIMARY KEY (`group_id`);

--
-- Indexes for table `community-voices_quotes`
--
ALTER TABLE `community-voices_quotes`
  ADD PRIMARY KEY (`media_id`);

--
-- Indexes for table `community-voices_slides`
--
ALTER TABLE `community-voices_slides`
  ADD PRIMARY KEY (`media_id`),
  ADD UNIQUE KEY `image_id` (`image_id`,`quote_id`),
  ADD UNIQUE KEY `image_id_2` (`image_id`,`quote_id`),
  ADD KEY `community-voices_slides_fk1` (`content_category_id`),
  ADD KEY `community-voices_slides_fk2` (`image_id`),
  ADD KEY `community-voices_slides_fk3` (`quote_id`);

--
-- Indexes for table `community-voices_tags`
--
ALTER TABLE `community-voices_tags`
  ADD PRIMARY KEY (`group_id`);

--
-- Indexes for table `community-voices_tokens`
--
ALTER TABLE `community-voices_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `community-voices_users`
--
ALTER TABLE `community-voices_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `community-voices_groups`
--
ALTER TABLE `community-voices_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;
--
-- AUTO_INCREMENT for table `community-voices_identities`
--
ALTER TABLE `community-voices_identities`
  MODIFY `identity_id` int(21) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;
--
-- AUTO_INCREMENT for table `community-voices_location-category-map`
--
ALTER TABLE `community-voices_location-category-map`
  MODIFY `id` int(21) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `community-voices_locations`
--
ALTER TABLE `community-voices_locations`
  MODIFY `id` int(21) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `community-voices_media`
--
ALTER TABLE `community-voices_media`
  MODIFY `id` int(21) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4206;
--
-- AUTO_INCREMENT for table `community-voices_media-article-map`
--
ALTER TABLE `community-voices_media-article-map`
  MODIFY `id` int(21) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `community-voices_media-group-map`
--
ALTER TABLE `community-voices_media-group-map`
  MODIFY `id` int(21) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7408;
--
-- AUTO_INCREMENT for table `community-voices_media-location-map`
--
ALTER TABLE `community-voices_media-location-map`
  MODIFY `id` int(21) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17473;
--
-- AUTO_INCREMENT for table `community-voices_tokens`
--
ALTER TABLE `community-voices_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;
--
-- AUTO_INCREMENT for table `community-voices_users`
--
ALTER TABLE `community-voices_users`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `community-voices_articles`
--
ALTER TABLE `community-voices_articles`
  ADD CONSTRAINT `community-voices_articles_fk0` FOREIGN KEY (`media_id`) REFERENCES `community-voices_media` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `community-voices_articles_fk1` FOREIGN KEY (`image_id`) REFERENCES `community-voices_images` (`media_id`);

--
-- Constraints for table `community-voices_content-categories`
--
ALTER TABLE `community-voices_content-categories`
  ADD CONSTRAINT `community-voices_content-categories_fk0` FOREIGN KEY (`group_id`) REFERENCES `community-voices_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `community-voices_identities`
--
ALTER TABLE `community-voices_identities`
  ADD CONSTRAINT `community-voices_identities_fk0` FOREIGN KEY (`parent_id`) REFERENCES `community-voices_identities` (`identity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `community-voices_identities_fk1` FOREIGN KEY (`account_id`) REFERENCES `community-voices_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `community-voices_images`
--
ALTER TABLE `community-voices_images`
  ADD CONSTRAINT `community-voices_images_fk0` FOREIGN KEY (`media_id`) REFERENCES `community-voices_media` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `community-voices_location-category-map`
--
ALTER TABLE `community-voices_location-category-map`
  ADD CONSTRAINT `community-voices_location-category-map_fk0` FOREIGN KEY (`location_id`) REFERENCES `community-voices_locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `community-voices_location-category-map_fk1` FOREIGN KEY (`group_id`) REFERENCES `community-voices_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `community-voices_media`
--
ALTER TABLE `community-voices_media`
  ADD CONSTRAINT `community-voices_media_ibfk_1` FOREIGN KEY (`added_by`) REFERENCES `community-voices_users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `community-voices_media-group-map`
--
ALTER TABLE `community-voices_media-group-map`
  ADD CONSTRAINT `community-voices_media-group-map_fk0` FOREIGN KEY (`media_id`) REFERENCES `community-voices_media` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `community-voices_media-group-map_fk1` FOREIGN KEY (`group_id`) REFERENCES `community-voices_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `community-voices_organization-categories`
--
ALTER TABLE `community-voices_organization-categories`
  ADD CONSTRAINT `community-voices_organization-categories_fk0` FOREIGN KEY (`group_id`) REFERENCES `community-voices_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `community-voices_quotes`
--
ALTER TABLE `community-voices_quotes`
  ADD CONSTRAINT `community-voices_quotes_fk0` FOREIGN KEY (`media_id`) REFERENCES `community-voices_media` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `community-voices_slides`
--
ALTER TABLE `community-voices_slides`
  ADD CONSTRAINT `community-voices_slides_fk0` FOREIGN KEY (`media_id`) REFERENCES `community-voices_media` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `community-voices_slides_fk1` FOREIGN KEY (`content_category_id`) REFERENCES `community-voices_content-categories` (`group_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `community-voices_slides_fk2` FOREIGN KEY (`image_id`) REFERENCES `community-voices_images` (`media_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `community-voices_slides_fk3` FOREIGN KEY (`quote_id`) REFERENCES `community-voices_quotes` (`media_id`) ON UPDATE CASCADE;

--
-- Constraints for table `community-voices_tags`
--
ALTER TABLE `community-voices_tags`
  ADD CONSTRAINT `community-voices_tags_fk0` FOREIGN KEY (`group_id`) REFERENCES `community-voices_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Adding quotation_marks field to `community-voices_quotes`
--
ALTER TABLE `community-voices_quotes` ADD `quotation_marks` BOOLEAN NOT NULL DEFAULT TRUE AFTER `sub_attribution`;

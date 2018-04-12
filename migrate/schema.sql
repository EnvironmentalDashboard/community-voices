/**
 * Table skeletons
 */

CREATE TABLE `community-voices_content-categories` (
    `group_id` int(21) NOT NULL,
    `media_filename` varchar(255) NOT NULL
);

CREATE TABLE `community-voices_groups` (
    `id` int(11) NOT NULL,
    `label` varchar(255) NOT NULL,
    `type` enum('tag','org-category','content-category') NOT NULL
);

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
);

CREATE TABLE `community-voices_images` (
    `media_id` int(21) NOT NULL,
    `filename` varchar(255) NOT NULL,
    `generated_tags` varchar(255) DEFAULT NULL,
    `title` varchar(255) DEFAULT NULL,
    `description` text,
    `date_taken` datetime DEFAULT NULL,
    `photographer` varchar(255) DEFAULT NULL,
    `organization` varchar(255) DEFAULT NULL
);

CREATE TABLE `community-voices_location-category-map` (
    `id` int(21) NOT NULL,
    `location_id` int(21) NOT NULL,
    `group_id` int(21) NOT NULL,
    `probability` int(21) NOT NULL
);

CREATE TABLE `community-voices_locations` (
    `id` int(21) NOT NULL,
    `label` varchar(255) NOT NULL
);

CREATE TABLE `community-voices_media` (
    `id` int(21) NOT NULL,
    `added_by` int(11) NOT NULL,
    `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `type` enum('slide','image','quote') NOT NULL,
    `status` enum('pending','rejected','approved') NOT NULL
);

CREATE TABLE `community-voices_media-group-map` (
    `id` int(21) NOT NULL,
    `media_id` int(21) NOT NULL,
    `group_id` int(21) NOT NULL
);

CREATE TABLE `community-voices_organization-categories` (
    `group_id` int(21) NOT NULL
);

CREATE TABLE `community-voices_quotes` (
    `media_id` int(21) NOT NULL,
    `text` text,
    `attribution` varchar(255) DEFAULT NULL,
    `sub_attribution` varchar(255) DEFAULT NULL,
    `date_recorded` datetime DEFAULT NULL,
    `public_document_link` varchar(255) DEFAULT NULL,
    `source_document_link` varchar(255) DEFAULT NULL
);

CREATE TABLE `community-voices_slides` (
    `media_id` int(21) NOT NULL,
    `content_category_id` int(21) NOT NULL,
    `image_id` int(21) NOT NULL,
    `quote_id` int(21) NOT NULL,
    `probability` int(21) NOT NULL,
    `decay_percent` int(21) DEFAULT NULL,
    `decay_start` datetime DEFAULT NULL,
    `decay_end` datetime DEFAULT NULL
);

CREATE TABLE `community-voices_tags` (
    `group_id` int(21) NOT NULL
);

CREATE TABLE `community-voices_users` (
    `id` int(20) NOT NULL,
    `email` varchar(255) NOT NULL,
    `lname` varchar(100) DEFAULT NULL,
    `fname` varchar(100) DEFAULT NULL,
    `role` enum('unverified','user','manager','admin') NOT NULL DEFAULT 'user'
);

/**
 * Keys (primaries, unique)
 */

ALTER TABLE `community-voices_content-categories`
    ADD PRIMARY KEY (`group_id`);

ALTER TABLE `community-voices_groups`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `community-voices_identities`
    ADD PRIMARY KEY (`identity_id`),
    ADD KEY `community-voices_identities_fk1` (`account_id`),
    ADD KEY `parent_id` (`parent_id`,`account_id`,`type`,`fingerprint`,`status`,`expires_on`,`token`,`token_expires_on`,`token_action`);

ALTER TABLE `community-voices_images`
    ADD PRIMARY KEY (`media_id`);

ALTER TABLE `community-voices_location-category-map`
    ADD PRIMARY KEY (`id`),
    ADD KEY `community-voices_location-category-map_fk0` (`location_id`),
    ADD KEY `community-voices_location-category-map_fk1` (`group_id`);

ALTER TABLE `community-voices_locations`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `community-voices_media`
    ADD PRIMARY KEY (`id`),
    ADD KEY `community-voices_media_fk0` (`added_by`);

ALTER TABLE `community-voices_media-group-map`
    ADD PRIMARY KEY (`id`),
    ADD KEY `community-voices_media-group-map_fk0` (`media_id`),
    ADD KEY `community-voices_media-group-map_fk1` (`group_id`);

ALTER TABLE `community-voices_organization-categories`
    ADD PRIMARY KEY (`group_id`);

ALTER TABLE `community-voices_quotes`
    ADD PRIMARY KEY (`media_id`);

ALTER TABLE `community-voices_slides`
    ADD PRIMARY KEY (`media_id`),
    ADD UNIQUE KEY `image_id` (`image_id`,`quote_id`),
    ADD KEY `community-voices_slides_fk1` (`content_category_id`),
    ADD KEY `community-voices_slides_fk2` (`image_id`),
    ADD KEY `community-voices_slides_fk3` (`quote_id`);

ALTER TABLE `community-voices_tags`
    ADD PRIMARY KEY (`group_id`);

ALTER TABLE `community-voices_users`
    ADD PRIMARY KEY (`id`);

/**
 * Auto increments
 */

ALTER TABLE `community-voices_groups`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `community-voices_identities`
    MODIFY `identity_id` int(21) NOT NULL AUTO_INCREMENT;
ALTER TABLE `community-voices_location-category-map`
    MODIFY `id` int(21) NOT NULL AUTO_INCREMENT;
ALTER TABLE `community-voices_locations`
    MODIFY `id` int(21) NOT NULL AUTO_INCREMENT;
ALTER TABLE `community-voices_media`
    MODIFY `id` int(21) NOT NULL AUTO_INCREMENT;
ALTER TABLE `community-voices_media-group-map`
    MODIFY `id` int(21) NOT NULL AUTO_INCREMENT;
ALTER TABLE `community-voices_users`
    MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;

/**
 * Foreign key constraints
 */

ALTER TABLE `community-voices_content-categories`
    ADD CONSTRAINT `community-voices_content-categories_fk0` FOREIGN KEY (`group_id`) REFERENCES `community-voices_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `community-voices_identities`
    ADD CONSTRAINT `community-voices_identities_fk0` FOREIGN KEY (`parent_id`) REFERENCES `community-voices_identities` (`identity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `community-voices_identities_fk1` FOREIGN KEY (`account_id`) REFERENCES `community-voices_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `community-voices_images`
    ADD CONSTRAINT `community-voices_images_fk0` FOREIGN KEY (`media_id`) REFERENCES `community-voices_media` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `community-voices_location-category-map`
    ADD CONSTRAINT `community-voices_location-category-map_fk0` FOREIGN KEY (`location_id`) REFERENCES `community-voices_locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `community-voices_location-category-map_fk1` FOREIGN KEY (`group_id`) REFERENCES `community-voices_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `community-voices_media`
    ADD CONSTRAINT `community-voices_media_fk0` FOREIGN KEY (`added_by`) REFERENCES `community-voices_users` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

ALTER TABLE `community-voices_media-group-map`
    ADD CONSTRAINT `community-voices_media-group-map_fk0` FOREIGN KEY (`media_id`) REFERENCES `community-voices_media` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `community-voices_media-group-map_fk1` FOREIGN KEY (`group_id`) REFERENCES `community-voices_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `community-voices_organization-categories`
    ADD CONSTRAINT `community-voices_organization-categories_fk0` FOREIGN KEY (`group_id`) REFERENCES `community-voices_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `community-voices_quotes`
    ADD CONSTRAINT `community-voices_quotes_fk0` FOREIGN KEY (`media_id`) REFERENCES `community-voices_media` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `community-voices_slides`
    ADD CONSTRAINT `community-voices_slides_fk0` FOREIGN KEY (`media_id`) REFERENCES `community-voices_media` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `community-voices_slides_fk1` FOREIGN KEY (`content_category_id`) REFERENCES `community-voices_content-categories` (`group_id`) ON UPDATE CASCADE,
    ADD CONSTRAINT `community-voices_slides_fk2` FOREIGN KEY (`image_id`) REFERENCES `community-voices_images` (`media_id`) ON UPDATE CASCADE,
    ADD CONSTRAINT `community-voices_slides_fk3` FOREIGN KEY (`quote_id`) REFERENCES `community-voices_quotes` (`media_id`) ON UPDATE CASCADE;

ALTER TABLE `community-voices_tags`
    ADD CONSTRAINT `community-voices_tags_fk0` FOREIGN KEY (`group_id`) REFERENCES `community-voices_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

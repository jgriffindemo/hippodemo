CREATE TABLE `locations` ( 
	`id` Int( 255 ) AUTO_INCREMENT NOT NULL,
	`city` VarChar( 255 ) NULL,
	`region` VarChar( 255 ) NULL,
	`country` VarChar( 255 ) NULL,
	CONSTRAINT `unique_id` UNIQUE( `id` ) )
ENGINE = InnoDB;

CREATE TABLE `users` ( 
	`id` Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL,
	`name` VarChar( 255 ) COLLATE latin1_bin NOT NULL,
	CONSTRAINT `unique_id` UNIQUE( `id` ),
	CONSTRAINT `unique_name` UNIQUE( `name` ) )
ENGINE = InnoDB;

CREATE TABLE `users_locations` ( 
	`id` Int( 10 ) UNSIGNED AUTO_INCREMENT NOT NULL,
	`user_id` Int( 10 ) UNSIGNED NOT NULL,
	`location_id` Int( 255 ) NOT NULL,
	CONSTRAINT `unique_id` UNIQUE( `id` ) )
ENGINE = InnoDB;

alter table `users_locations`
add constraint fk_users_locations_user_id
foreign key (`user_id`) references `users`(`id`)
on update cascade
on delete cascade;

alter table `users_locations` 
add constraint fk_users_locations_locationr_id
foreign key (`location_id`) references `locations`(`id`)
on update cascade
on delete cascade;
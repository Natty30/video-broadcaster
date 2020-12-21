DROP DATABASE streams;

CREATE DATABASE streams;

USE streams;

CREATE TABLE videos(id int primary key auto_increment,
	video_extension varchar(10) not null,
	stream_url varchar(50) not null,
	stream_key varchar(75) not null,
	is_in_stream boolean not null default false,
	pid_of_stream int
);
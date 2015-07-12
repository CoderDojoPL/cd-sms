<?php 
namespace Database;


class Version20150711112810{
	
	public function up($db){
		$sql="CREATE SEQUENCE devices_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
			CREATE SEQUENCE device_tags_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
			CREATE SEQUENCE files_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
			CREATE SEQUENCE device_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
			CREATE TABLE devices (id INT NOT NULL, file_id INT DEFAULT NULL, type_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, photo VARCHAR(255) NOT NULL, dimensions VARCHAR(255) NOT NULL, weight VARCHAR(255) NOT NULL, PRIMARY KEY(id));
			CREATE INDEX IDX_11074E9A93CB796C ON devices (file_id);
			CREATE INDEX IDX_11074E9AC54C8C93 ON devices (type_id);
			CREATE TABLE device_tag (device_id INT NOT NULL, tag_id INT NOT NULL, PRIMARY KEY(device_id, tag_id));
			CREATE INDEX IDX_E9776D1A94A4C7D4 ON device_tag (device_id);
			CREATE INDEX IDX_E9776D1ABAD26311 ON device_tag (tag_id);
			CREATE TABLE device_tags (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id));
			CREATE TABLE files (id INT NOT NULL, fileName VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, size BIGINT NOT NULL, mimeType VARCHAR(255) NOT NULL, PRIMARY KEY(id));
			CREATE TABLE device_type (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id));
			ALTER TABLE devices ADD CONSTRAINT FK_11074E9A93CB796C FOREIGN KEY (file_id) REFERENCES files (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
			ALTER TABLE devices ADD CONSTRAINT FK_11074E9AC54C8C93 FOREIGN KEY (type_id) REFERENCES device_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
			ALTER TABLE device_tag ADD CONSTRAINT FK_E9776D1A94A4C7D4 FOREIGN KEY (device_id) REFERENCES devices (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
			ALTER TABLE device_tag ADD CONSTRAINT FK_E9776D1ABAD26311 FOREIGN KEY (tag_id) REFERENCES device_tags (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
			INSERT INTO device_types (id,name) VALUES(1,'Refill'),(2,'Hardware');
			INSERT INTO device_states (id, name) VALUES (1, 'Available')
			INSERT INTO device_states (id, name) VALUES (2, 'Busy')
			INSERT INTO device_states (id, name) VALUES (3, 'In service')
			INSERT INTO order_states(id,name) values(1,'Open'),(2,'In progress'),(3,'Closed');

			";

	}

	public function down($db){

	}

}
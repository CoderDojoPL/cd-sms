<?php
/**
 * Created by PhpStorm.
 * User: DrafFter
 * Date: 2015-09-01
 * Time: 21:48
 */

namespace Migrate;


use Arbor\Core\Container;
use Common\MigrateHelper;

class Version20150901214700 extends MigrateHelper
{
    /**
     * {@inheritdoc}
     */
    public function update(Container $container)
    {
        $this->beginTransaction();
        $schema = $this->createSchema();

        $functionalities = $schema->createTable('functionalities');
        $functionalities->addColumn('id', 'integer', array('autoincrement' => true));
        $functionalities->addColumn('name', 'string');
        $functionalities->addColumn('description', 'string');
        $functionalities->setPrimaryKey(array('id'));

        $roles = $schema->createTable('roles');
        $roles->addColumn('id', 'integer', array('autoincrement' => true));
        $roles->addColumn('name', 'string');
        $roles->setPrimaryKey(array('id'));

        $rolesFunctionalities = $schema->createTable('roles_functionalities');
        $rolesFunctionalities->addColumn('role_id', 'integer');
        $rolesFunctionalities->addColumn('functionality_id', 'integer');
        $rolesFunctionalities->setPrimaryKey(array('role_id', 'functionality_id'));

        $roleLogs = $schema->createTable('role_logs');
        $roleLogs->addColumn('id', 'integer');
        $roleLogs->addColumn('name', 'string');
        $roleLogs->addColumn('created_at', 'datetime');
        $roleLogs->addColumn('log_left_id', 'integer');
        $roleLogs->addColumn('log_right_id', 'integer', array('notnull' => false));
        $roleLogs->addColumn('removed', 'boolean');
        $roleLogs->setPrimaryKey(array('id', 'log_left_id'));

        $logs = $schema->getTable('logs');

        $rolesFunctionalities->addNamedForeignKeyConstraint('FK_1314141ED60322AC', $roles, array('role_id'), array('id'));
        $rolesFunctionalities->addNamedForeignKeyConstraint('FK_1314141E39EDDC8', $functionalities, array('functionality_id'), array('id'));
        $roleLogs->addNamedForeignKeyConstraint('FK_4E30B4C8DAA1F695', $logs, array('log_left_id'), array('id'));
        $roleLogs->addNamedForeignKeyConstraint('FK_4E30B4C83AC4A3EA', $logs, array('log_right_id'), array('id'));

        $users = $schema->getTable('users');
        $users->addColumn('role_id','integer', array('notnull' => false));
        $users->addNamedForeignKeyConstraint('FK_1483A5E9D60322AC', $roles, array('role_id'), array('id'));

        $usersLogs = $schema->getTable('user_logs');
        $usersLogs->addColumn('role_id','integer', array('notnull' => false));

        $this->updateSchema($schema);
        $this->commitTransaction();
    }


    /**
     * {@inheritdoc}
     */
    public function downgrade(Container $container)
    {
        $this->beginTransaction();
        $schema = $this->createSchema();

        $rolesFunctionalities = $schema->getTable('roles_functionalities');
        $roleLogs = $schema->getTable('role_logs');
        $users = $schema->getTable('users');
        $usersLogs = $schema->getTable('user_logs');


        $rolesFunctionalities->removeForeignKey('FK_1314141ED60322AC');
        $rolesFunctionalities->removeForeignKey('FK_1314141E39EDDC8');
        $roleLogs->removeForeignKey('FK_4E30B4C8DAA1F695');
        $roleLogs->removeForeignKey('FK_4E30B4C83AC4A3EA');

        $users->dropColumn('role_id');
        $usersLogs->dropColumn('role_id');


        $schema->dropTable('functionalities');
        $schema->dropTable('roles');
        $schema->dropTable('roles_functionalities');
        $schema->dropTable('role_logs');

        $this->updateSchema($schema);
        $this->commitTransaction();
    }

}
/*
CREATE TABLE functionalities (id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id));
CREATE TABLE roles (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id));
CREATE TABLE roles_functionalities (role_id INT NOT NULL, functionality_id INT NOT NULL, PRIMARY KEY(role_id, functionality_id));
CREATE INDEX IDX_1314141ED60322AC ON roles_functionalities (role_id);
CREATE INDEX IDX_1314141E39EDDC8 ON roles_functionalities (functionality_id);
CREATE TABLE role_logs (id INT NOT NULL, log_left_id INT NOT NULL, log_right_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, removed BOOLEAN NOT NULL, PRIMARY KEY(id, log_left_id));
CREATE INDEX IDX_4E30B4C8DAA1F695 ON role_logs (log_left_id);
CREATE INDEX IDX_4E30B4C83AC4A3EA ON role_logs (log_right_id);
ALTER TABLE roles_functionalities ADD CONSTRAINT FK_1314141E39EDDC8 FOREIGN KEY (functionality_id) REFERENCES functionalities (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE role_logs ADD CONSTRAINT FK_4E30B4C8DAA1F695 FOREIGN KEY (log_left_id) REFERENCES logs (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE role_logs ADD CONSTRAINT FK_4E30B4C83AC4A3EA FOREIGN KEY (log_right_id) REFERENCES logs (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE device_tags ALTER id DROP DEFAULT;
ALTER TABLE files ALTER id DROP DEFAULT;
ALTER TABLE locations ALTER id DROP DEFAULT;
ALTER TABLE orders ALTER id DROP DEFAULT;
ALTER TABLE users ADD role_id INT DEFAULT NULL;
ALTER TABLE users ALTER id DROP DEFAULT;
ALTER TABLE users ADD CONSTRAINT FK_1483A5E9D60322AC FOREIGN KEY (role_id) REFERENCES roles (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE;
CREATE INDEX IDX_1483A5E9D60322AC ON users (role_id);
ALTER TABLE device_logs DROP CONSTRAINT FK_79B61366DAA1F695;
ALTER TABLE device_logs DROP CONSTRAINT FK_79B613663AC4A3EA;
ALTER TABLE device_logs ADD CONSTRAINT FK_79B61366DAA1F695 FOREIGN KEY (log_left_id) REFERENCES logs (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE device_logs ADD CONSTRAINT FK_79B613663AC4A3EA FOREIGN KEY (log_right_id) REFERENCES logs (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE device_tag_logs DROP CONSTRAINT FK_90B420CADAA1F695;
ALTER TABLE device_tag_logs DROP CONSTRAINT FK_90B420CA3AC4A3EA;
ALTER TABLE device_tag_logs ADD CONSTRAINT FK_90B420CADAA1F695 FOREIGN KEY (log_left_id) REFERENCES logs (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE device_tag_logs ADD CONSTRAINT FK_90B420CA3AC4A3EA FOREIGN KEY (log_right_id) REFERENCES logs (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE location_logs DROP CONSTRAINT FK_708B731CDAA1F695;
ALTER TABLE location_logs DROP CONSTRAINT FK_708B731C3AC4A3EA;
ALTER TABLE location_logs ADD CONSTRAINT FK_708B731CDAA1F695 FOREIGN KEY (log_left_id) REFERENCES logs (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE location_logs ADD CONSTRAINT FK_708B731C3AC4A3EA FOREIGN KEY (log_right_id) REFERENCES logs (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE logs ALTER id DROP DEFAULT;
ALTER TABLE order_logs DROP CONSTRAINT FK_BD7EFC4BDAA1F695;
ALTER TABLE order_logs DROP CONSTRAINT FK_BD7EFC4B3AC4A3EA;
ALTER TABLE order_logs ADD CONSTRAINT FK_BD7EFC4BDAA1F695 FOREIGN KEY (log_left_id) REFERENCES logs (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE order_logs ADD CONSTRAINT FK_BD7EFC4B3AC4A3EA FOREIGN KEY (log_right_id) REFERENCES logs (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE user_logs ADD role_id INT DEFAULT NULL;
ALTER TABLE user_logs ALTER location_id SET NOT NULL;
ALTER TABLE user_logs DROP CONSTRAINT FK_8A0E8A95DAA1F695;
ALTER TABLE user_logs DROP CONSTRAINT FK_8A0E8A953AC4A3EA;
ALTER TABLE user_logs ADD CONSTRAINT FK_8A0E8A95DAA1F695 FOREIGN KEY (log_left_id) REFERENCES logs (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE user_logs ADD CONSTRAINT FK_8A0E8A953AC4A3EA FOREIGN KEY (log_right_id) REFERENCES logs (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE devices ALTER id DROP DEFAULT
*/
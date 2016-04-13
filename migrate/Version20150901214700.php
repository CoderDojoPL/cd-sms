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
        $users->addColumn('role_id', 'integer', array('notnull' => false));
        $users->addNamedForeignKeyConstraint('FK_1483A5E9D60322AC', $roles, array('role_id'), array('id'));

        $usersLogs = $schema->getTable('user_logs');
        $usersLogs->addColumn('role_id', 'integer', array('notnull' => false));

        $this->updateSchema($schema);

        $this->executeQuery("INSERT INTO log_actions(id,name) VALUES(:id,:name)", array(
            'id' => 15
        , 'name' => 'Create role.'
        ));

        $this->executeQuery("INSERT INTO log_actions(id,name) VALUES(:id,:name)", array(
            'id' => 16
        , 'name' => 'Edit role.'
        ));

        $functionalitiesData = array(
            array('name' => 'Device add', 'description' => 'Add new device'),
            array('name' => 'Device edit', 'description' => 'Edit existing device'),
            array('name' => 'Device remove', 'description' => 'Remove device'),
            array('name' => 'Location add', 'description' => 'Add new location'),
            array('name' => 'Location edit', 'description' => 'Edit existing location'),
            array('name' => 'Location remove', 'description' => 'Remove location'),
            array('name' => 'User edit', 'description' => 'Edit user data and roles'),
            array('name' => 'Role add', 'description' => 'Add new role'),
            array('name' => 'Role edit', 'description' => 'Edit existing role'),
            array('name' => 'Order list', 'description' => 'List order for devices other users'),
            array('name' => 'New order', 'description' => 'Place new order for device'),
            array('name' => 'Fetch order', 'description' => 'Fetch order for device to complete'),
            array('name' => 'Close order', 'description' => 'Confirm and close order for device'),
            array('name' => 'Report - show logs', 'description' => 'Show system logs')
        );
        for($i=0; $i<count($functionalitiesData); $i++) {
            $this->executeQuery("INSERT INTO functionalities(id,name,description) VALUES(".($i+1). ",:name,:description)", $functionalitiesData[$i]);
        }

        $this->executeQuery("INSERT INTO roles(".($this->getDriver()=='pdo_pgsql'?'id,':'')."name) VALUES(".($this->getDriver()=='pdo_pgsql'?"nextval('roles_id_seq'),":'').":name)", array(
            'name' => 'Admin'
        ));

        $this->executeQuery("INSERT INTO role_logs(id,name,log_left_id,created_at,removed) VALUES((select max(id) from roles),:name,(select min(id) from logs),now(),false)", array(
            'name' => 'Admin'
        ));

        $this->executeQuery("UPDATE users SET role_id=(SELECT max(id) FROM roles)");

        $this->executeQuery("INSERT INTO roles_functionalities(role_id,functionality_id)
            SELECT r.id,f.id
            FROM functionalities f,(SELECT MAX(id) id FROM roles) r");

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

        $this->executeQuery("DELETE FROM logs WHERE log_action_id=:id",array(
            'id'=>15
        ));

        $this->executeQuery("DELETE FROM log_actions WHERE id=:id",array(
            'id'=>15
        ));

        $this->executeQuery("DELETE FROM logs WHERE log_action_id=:id",array(
            'id'=>16
        ));

        $this->executeQuery("DELETE FROM log_actions WHERE id=:id",array(
            'id'=>16
        ));

        $this->commitTransaction();
    }

}
<?php

/**
 * m151001_114158_create_locations_tables
 *
 */
use jarrus90\Core\Console\BaseMigration;
use jarrus90\User\models\User;

/**
 * Migration to set up users tables
 */
class m151001_114227_create_users_tables extends BaseMigration {

    /**
     * Create user tables.
     */
    public function up() {
        $this->db = $this->authManager->db;

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $this->createTable($this->authManager->ruleTable, [
            'name' => $this->string(64)->notNull(),
            'data' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
                ], $tableOptions);
        $this->addPrimaryKey('name', $this->authManager->ruleTable, 'name');

        $this->createTable($this->authManager->itemTable, [
            'name' => $this->string(64)->notNull(),
            'type' => $this->integer()->notNull(),
            'description' => $this->text(),
            'rule_name' => $this->string(64),
            'data' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
                ], $tableOptions);

        $this->addPrimaryKey('name', $this->authManager->itemTable, 'name');

        $this->createIndex('idx-auth_item-type', $this->authManager->itemTable, 'type');
        $this->addForeignKey('fk-user_rbac_item_rule', $this->authManager->itemTable, 'rule_name', $this->authManager->ruleTable, 'name', 'CASCADE', 'RESTRICT');

        $this->createTable($this->authManager->itemChildTable, [
            'parent' => $this->string(64)->notNull(),
            'child' => $this->string(64)->notNull(),
                ], $tableOptions);

        $this->addPrimaryKey('pk-parent_child', $this->authManager->itemChildTable, ['parent', 'child']);

        $this->addForeignKey('fk-user_rbac_item_parent', $this->authManager->itemChildTable, 'parent', $this->authManager->itemTable, 'name', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk-user_rbac_item_child', $this->authManager->itemChildTable, 'child', $this->authManager->itemTable, 'name', 'CASCADE', 'RESTRICT');

        $this->createTable($this->authManager->assignmentTable, [
            'item_name' => $this->string(64)->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->integer(),
                ], $tableOptions);
        $this->addPrimaryKey('pk-user_item', $this->authManager->assignmentTable, ['item_name', 'user_id']);

        $this->addForeignKey('fk-user_rbac_assignment_item', $this->authManager->assignmentTable, 'item_name', $this->authManager->itemTable, 'name', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk-user_rbac_assignment_user', $this->authManager->assignmentTable, 'user_id', User::tableName(), 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * Drop user tables.
     */
    public function down() {
        $this->dropTable($this->authManager->assignmentTable);
        $this->dropTable($this->authManager->itemChildTable);
        $this->dropTable($this->authManager->itemTable);
        $this->dropTable($this->authManager->ruleTable);
    }

}

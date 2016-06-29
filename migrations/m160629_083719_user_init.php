<?php

use yii\db\Migration;

class m160629_083719_user_init extends Migration {

    public function safeUp() {
        $tableOptions = null;
        if (Yii::$app->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(255)->notNull(),
            'email' => $this->string(255)->notNull(),
            'password_hash' => $this->string(60)->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'confirmed_at' => $this->integer(),
            'unconfirmed_email' => $this->string(255),
            'blocked_at' => $this->integer(),
            'registration_ip' => $this->string(45),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'flags' => $this->integer()->notNull()->defaultValue(0),
                ], $tableOptions);

        $this->createIndex('idx-user-email', '{{%user}}', 'email', 1);
        $this->createIndex('idx-user-username', '{{%user}}', 'username', 1);

        $this->createTable('{{%profile}}', [
            'user_id' => $this->integer()->notNull(),
            'name' => $this->string(255),
            'surname' => $this->string(255),
            'lastname' => $this->string(255),
            'public_email' => $this->string(255),
            'bio' => $this->text(),
            'timezone' => $this->string(40)->notNull(),
                ], $tableOptions);
        $this->createIndex('idx-profile-user', '{{%profile}}', 'user_id');
        $this->addForeignKey('fk-profile-user', '{{%profile}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createTable('{{%social_account}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'provider' => $this->string(255)->notNull(),
            'client_id' => $this->string(255)->notNull(),
            'data' => $this->text(),
            'code' => $this->string(32),
            'created_at' => $this->integer(),
            'email' => $this->string(255),
            'username' => $this->string(255),
                ], $tableOptions);

        $this->createIndex('idx-social_account-user', '{{%social_account}}', 'user_id');
        $this->createIndex('idx-social_account-unique', '{{%social_account}}', ['provider', 'client_id'], 1);
        $this->createIndex('idx-social_account-unique_code', '{{%social_account}}', 'code', 1);
        $this->addForeignKey('fk-social_account-user_id', '{{%social_account}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createTable('{{%token}}', [
            'user_id' => $this->integer()->notNull(),
            'code' => $this->string(32)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'type' => $this->smallInteger(6)->notNull(),
                ], $tableOptions);

        $this->createIndex('idx-token-unique', '{{%token}}', ['user_id', 'code', 'type'], 1);
        $this->addForeignKey('fk-token-user_id', '{{%token}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createTable('{{%user_rbac_rule}}', [
            'name' => $this->string(64)->notNull(),
            'data' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
                ], $tableOptions);
        
        $this->createIndex('idx-user_rbac_rule-name', '{{%user_rbac_rule}}', 'name');

        $this->createTable('{{%user_rbac_item}}', [
            'name' => $this->string(64)->notNull(),
            'type' => $this->integer()->notNull(),
            'description' => $this->text(),
            'rule_name' => $this->string(64),
            'data' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
                ], $tableOptions);

        $this->createIndex('idx-user_rbac_item-name', '{{%user_rbac_item}}', 'name');
        $this->createIndex('idx-user_rbac_item-type', '{{%user_rbac_item}}', 'type');
        $this->createIndex('idx-user_rbac_item-rule', '{{%user_rbac_item}}', 'rule_name');
        $this->addForeignKey('fk-user_rbac_item_rule_name', '{{%user_rbac_item}}', 'rule_name', '{{%user_rbac_rule}}', 'name', 'CASCADE', 'RESTRICT');

        $this->createTable('{{%user_rbac_assignment}}', [
            'item_name' => $this->string(64)->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->integer(),
                ], $tableOptions);

        $this->createIndex('idx-user_rbac_assignment_user', '{{%user_rbac_assignment}}', 'user_id');
        $this->addForeignKey('fk-user_rbac_assignment_item_name', '{{%user_rbac_assignment}}', 'item_name', '{{%user_rbac_item}}', 'name', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk-user_rbac_assignment_user_id', '{{%user_rbac_assignment}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createTable('{{%user_rbac_item_child}}', [
            'parent' => $this->string(64)->notNull(),
            'child' => $this->string(64)->notNull(),
                ], $tableOptions);

        $this->createIndex('idx-user_rbac_item_child', '{{%user_rbac_item_child}}', 'child');
        $this->addForeignKey('fk-user_rbac_item_child_child', '{{%user_rbac_item_child}}', 'child', '{{%user_rbac_item}}', 'name', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk-user_rbac_item_child_parent', '{{%user_rbac_item_child}}', 'parent', '{{%user_rbac_item}}', 'name', 'CASCADE', 'RESTRICT');
    }

    public function safeDown() {
        $this->dropForeignKey('fk-user_rbac_assignment_item_name', '{{%user_rbac_assignment}}');
        $this->dropForeignKey('fk-user_rbac_assignment_user_id', '{{%user_rbac_assignment}}');
        $this->dropForeignKey('fk-user_rbac_item_rule_name', '{{%user_rbac_item}}');
        $this->dropForeignKey('fk-user_rbac_item_child_child', '{{%user_rbac_item_child}}');
        $this->dropForeignKey('fk-user_rbac_item_child_parent', '{{%user_rbac_item_child}}');
        $this->dropForeignKey('fk-profile-user', '{{%profile}}');
        $this->dropForeignKey('fk-social_account-user_id', '{{%social_account}}');
        $this->dropForeignKey('fk-token-user_id', '{{%token}}');
        $this->dropTable('{{%user}}');
        $this->dropTable('{{%profile}}');
        $this->dropTable('{{%social_account}}');
        $this->dropTable('{{%token}}');
        $this->dropTable('{{%user_rbac_assignment}}');
        $this->dropTable('{{%user_rbac_item}}');
        $this->dropTable('{{%user_rbac_item_child}}');
        $this->dropTable('{{%user_rbac_rule}}');
    }

}

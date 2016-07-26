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

        $this->createIndex('idx-user-email', '{{%user}}', 'email', true);
        $this->createIndex('idx-user-username', '{{%user}}', 'username', true);

        $this->createTable('{{%user_profile}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'name' => $this->string(255),
            'surname' => $this->string(255),
            'lastname' => $this->string(255),
            'public_email' => $this->string(255),
            'bio' => $this->text(),
            'timezone' => $this->string(40)->notNull(),
                ], $tableOptions);
        $this->createIndex('idx-profile-user', '{{%user_profile}}', 'user_id');
        $this->addForeignKey('fk-profile-user', '{{%user_profile}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createTable('{{%user_social_account}}', [
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

        $this->createIndex('idx-user_social_account-user', '{{%user_social_account}}', 'user_id');
        $this->createIndex('idx-user_social_account-unique', '{{%user_social_account}}', ['provider', 'client_id'], true);
        $this->createIndex('idx-user_social_account-unique_code', '{{%user_social_account}}', 'code', true);
        $this->addForeignKey('fk-user_social_account-user_id', '{{%user_social_account}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createTable('{{%user_token}}', [
            'user_id' => $this->integer()->notNull(),
            'code' => $this->string(32)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'type' => $this->smallInteger(6)->notNull(),
                ], $tableOptions);

        $this->createIndex('idx-token-unique', '{{%user_token}}', ['user_id', 'code', 'type'], true);
        $this->addForeignKey('fk-token-user_id', '{{%user_token}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createTable('{{%user_rbac_rule}}', [
            'name' => $this->string(64)->notNull()->unique(),
            'data' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
                ], $tableOptions);
        
        $this->createIndex('idx-user_rbac_rule-name', '{{%user_rbac_rule}}', ['name'], true);

        $this->createTable('{{%user_rbac_item}}', [
            'name' => $this->string(64)->notNull()->unique(),
            'type' => $this->integer()->notNull(),
            'description' => $this->text(),
            'rule_name' => $this->string(64),
            'data' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
                ], $tableOptions);

        $this->createIndex('idx-user_rbac_item-name', '{{%user_rbac_item}}', 'name', true);
        $this->createIndex('idx-user_rbac_item-type', '{{%user_rbac_item}}', 'type');
        $this->createIndex('idx-user_rbac_item-rule', '{{%user_rbac_item}}', 'rule_name');
        $this->addForeignKey('fk-user_rbac_item-name', '{{%user_rbac_item}}', 'rule_name', '{{%user_rbac_rule}}', 'name', 'CASCADE', 'RESTRICT');

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
        $this->dropForeignKey('fk-profile-user', '{{%user_profile}}');
        $this->dropForeignKey('fk-user_social_account-user_id', '{{%user_social_account}}');
        $this->dropForeignKey('fk-token-user_id', '{{%user_token}}');
        $this->dropTable('{{%user}}');
        $this->dropTable('{{%user_profile}}');
        $this->dropTable('{{%user_social_account}}');
        $this->dropTable('{{%user_token}}');
        $this->dropTable('{{%user_rbac_assignment}}');
        $this->dropTable('{{%user_rbac_item}}');
        $this->dropTable('{{%user_rbac_item_child}}');
        $this->dropTable('{{%user_rbac_rule}}');
    }

}

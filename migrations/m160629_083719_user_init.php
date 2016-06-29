<?php

use yii\db\Migration;

class m160629_083719_user_init extends Migration {

    public function safeUp() {
        $tableOptions = null;
        if (Yii::$app->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
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

            $this->createIndex('user_unique_email', '{{%user}}', 'email', 1);
            $this->createIndex('user_unique_username', '{{%user}}', 'username', 1);
            $this->createTable('{{%user_rbac_assignment}}', [
                'item_name' => $this->string(64)->notNull(),
                'user_id' => $this->integer()->notNull(),
                'created_at' => $this->integer(),
                    ], $tableOptions);

            $this->createIndex('fk-user_rbac_assignment_user', '{{%user_rbac_assignment}}', 'user_id', 0);
            $this->createTable('{{%user_rbac_item}}', [
                'name' => $this->string(64)->notNull(),
                'type' => $this->integer()->notNull(),
                'description' => $this->text(),
                'rule_name' => $this->string(64),
                'data' => $this->text(),
                'created_at' => $this->integer(),
                'updated_at' => $this->integer(),
                    ], $tableOptions);

            $this->createIndex('idx-auth_item-type', '{{%user_rbac_item}}', 'type', 0);
            $this->createIndex('fk-user_rbac_item_rule', '{{%user_rbac_item}}', 'rule_name', 0);
            $this->createTable('{{%user_rbac_item_child}}', [
                'parent' => $this->string(64)->notNull(),
                'child' => $this->string(64)->notNull(),
                    ], $tableOptions);

            $this->createIndex('fk-user_rbac_item_child', '{{%user_rbac_item_child}}', 'child', 0);
            $this->createTable('{{%user_rbac_rule}}', [
                'name' => $this->string(64)->notNull(),
                'data' => $this->text(),
                'created_at' => $this->integer(),
                'updated_at' => $this->integer(),
                    ], $tableOptions);

            $this->createTable('{{%profile}}', [
                'user_id' => $this->integer()->notNull(),
                'name' => $this->string(255),
                'public_email' => $this->string(255),
                'gravatar_email' => $this->string(255),
                'gravatar_id' => $this->string(32)->notNull(),
                'location' => $this->string(255),
                'website' => $this->string(255),
                'bio' => $this->text(),
                'timezone' => $this->string(40)->notNull(),
                    ], $tableOptions);

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

            $this->createIndex('account_unique', '{{%social_account}}', 'provider,client_id', 1);
            $this->createIndex('account_unique_code', '{{%social_account}}', 'code', 1);
            $this->createIndex('fk_user_account', '{{%social_account}}', 'user_id', 0);
            $this->createTable('{{%token}}', [
                'user_id' => $this->integer()->notNull(),
                'code' => $this->string(32)->notNull(),
                'created_at' => $this->integer()->notNull(),
                'type' => $this->smallInteger(6)->notNull(),
                    ], $tableOptions);

            $this->createIndex('token_unique', '{{%token}}', 'user_id,code,type', 1);
            $this->addForeignKey('fk_user_rbac_assignment_item_name', '{{%user_rbac_assignment}}', 'item_name', 'user_rbac_item', 'name');
            $this->addForeignKey('fk_user_rbac_assignment_user_id', '{{%user_rbac_assignment}}', 'user_id', 'user', 'id');
            $this->addForeignKey('fk_user_rbac_item_rule_name', '{{%user_rbac_item}}', 'rule_name', 'user_rbac_rule', 'name');
            $this->addForeignKey('fk_user_rbac_item_child_child', '{{%user_rbac_item_child}}', 'child', 'user_rbac_item', 'name');
            $this->addForeignKey('fk_user_rbac_item_child_parent', '{{%user_rbac_item_child}}', 'parent', 'user_rbac_item', 'name');
            $this->addForeignKey('fk_profile_user_id', '{{%profile}}', 'user_id', 'user', 'id');
            $this->addForeignKey('fk_social_account_user_id', '{{%social_account}}', 'user_id', 'user', 'id');
            $this->addForeignKey('fk_token_user_id', '{{%token}}', 'user_id', 'user', 'id');
            $transaction->commit();
        } catch (Exception $e) {
            echo 'Catch Exception ' . $e->getMessage() . ' and rollBack this';
            $transaction->rollBack();
        }
    }

    public function safeDown() {
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $this->dropForeignKey('fk_user_rbac_assignment_item_name', '{{%user_rbac_assignment}}');
            $this->dropForeignKey('fk_user_rbac_assignment_user_id', '{{%user_rbac_assignment}}');
            $this->dropForeignKey('fk_user_rbac_item_rule_name', '{{%user_rbac_item}}');
            $this->dropForeignKey('fk_user_rbac_item_child_child', '{{%user_rbac_item_child}}');
            $this->dropForeignKey('fk_user_rbac_item_child_parent', '{{%user_rbac_item_child}}');
            $this->dropForeignKey('fk_profile_user_id', '{{%profile}}');
            $this->dropForeignKey('fk_social_account_user_id', '{{%social_account}}');
            $this->dropForeignKey('fk_token_user_id', '{{%token}}');
            $this->dropTable('{{%user}}');
            $this->dropTable('{{%profile}}');
            $this->dropTable('{{%social_account}}');
            $this->dropTable('{{%token}}');
            $this->dropTable('{{%user_rbac_assignment}}');
            $this->dropTable('{{%user_rbac_item}}');
            $this->dropTable('{{%user_rbac_item_child}}');
            $this->dropTable('{{%user_rbac_rule}}');
            $transaction->commit();
        } catch (Exception $e) {
            echo 'Catch Exception ' . $e->getMessage() . ' and rollBack this';
            $transaction->rollBack();
        }
    }

}

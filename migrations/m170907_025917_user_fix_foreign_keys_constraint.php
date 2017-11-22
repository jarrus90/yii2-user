<?php

namespace jarrus90\User\migrations;

use yii\db\Migration;

class m170907_025917_user_fix_foreign_keys_constraint extends Migration {

    public function safeUp() {
        $this->dropForeignKey('fk-user_rbac_item-name', '{{%user_rbac_item}}');
        $this->addForeignKey('fk-user_rbac_item-name', '{{%user_rbac_item}}', 'rule_name', '{{%user_rbac_rule}}', 'name', 'CASCADE', 'CASCADE');

        $this->dropForeignKey('fk-user_rbac_assignment_item_name', '{{%user_rbac_assignment}}');
        $this->addForeignKey('fk-user_rbac_assignment_item_name', '{{%user_rbac_assignment}}', 'item_name', '{{%user_rbac_item}}', 'name', 'CASCADE', 'CASCADE');

        $this->dropForeignKey('fk-user_rbac_assignment_user_id', '{{%user_rbac_assignment}}');
        $this->addForeignKey('fk-user_rbac_assignment_user_id', '{{%user_rbac_assignment}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

        $this->dropForeignKey('fk-user_rbac_item_child_child', '{{%user_rbac_item_child}}');
        $this->addForeignKey('fk-user_rbac_item_child_child', '{{%user_rbac_item_child}}', 'child', '{{%user_rbac_item}}', 'name', 'CASCADE', 'CASCADE');

        $this->dropForeignKey('fk-user_rbac_item_child_parent', '{{%user_rbac_item_child}}');
        $this->addForeignKey('fk-user_rbac_item_child_parent', '{{%user_rbac_item_child}}', 'parent', '{{%user_rbac_item}}', 'name', 'CASCADE', 'CASCADE');

    }

    public function safeDown() {
        $this->dropForeignKey('fk-user_rbac_item-name', '{{%user_rbac_item}}');
        $this->addForeignKey('fk-user_rbac_item-name', '{{%user_rbac_item}}', 'rule_name', '{{%user_rbac_rule}}', 'name', 'CASCADE', 'RESTRICT');

        $this->dropForeignKey('fk-user_rbac_assignment_item_name', '{{%user_rbac_assignment}}');
        $this->addForeignKey('fk-user_rbac_assignment_item_name', '{{%user_rbac_assignment}}', 'item_name', '{{%user_rbac_item}}', 'name', 'CASCADE', 'RESTRICT');

        $this->dropForeignKey('fk-user_rbac_assignment_user_id', '{{%user_rbac_assignment}}');
        $this->addForeignKey('fk-user_rbac_assignment_user_id', '{{%user_rbac_assignment}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');

        $this->dropForeignKey('fk-user_rbac_item_child_child', '{{%user_rbac_item_child}}');
        $this->addForeignKey('fk-user_rbac_item_child_child', '{{%user_rbac_item_child}}', 'child', '{{%user_rbac_item}}', 'name', 'CASCADE', 'RESTRICT');

        $this->dropForeignKey('fk-user_rbac_item_child_parent', '{{%user_rbac_item_child}}');
        $this->addForeignKey('fk-user_rbac_item_child_parent', '{{%user_rbac_item_child}}', 'parent', '{{%user_rbac_item}}', 'name', 'CASCADE', 'RESTRICT');
    }

}

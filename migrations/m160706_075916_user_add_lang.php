<?php

namespace jarrus90\User\migrations;

use yii\db\Migration;

class m160706_075916_user_add_lang extends Migration {

    public function safeUp() {
        $this->addColumn('{{%user}}', 'lang', $this->string(10));
        $this->createIndex('idx-user-lang', '{{%user}}', 'lang');
        $this->addForeignKey('fk-user-lang', '{{%user}}', 'lang', '{{%languages}}', 'code', 'CASCADE', 'RESTRICT');
    }

    public function safeDown() {
        $this->dropForeignKey('fk-user-lang', '{{%user}}');
        $this->dropColumn('{{%user}}', 'lang');
    }

}

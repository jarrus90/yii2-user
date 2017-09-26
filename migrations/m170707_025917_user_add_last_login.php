<?php

namespace jarrus90\User\migrations;

use yii\db\Migration;

class m170707_025917_user_add_last_login extends Migration {

    public function safeUp() {
        $this->addColumn('{{%user}}', 'last_login', $this->integer());
    }

    public function safeDown() {
        $this->dropColumn('{{%user}}', 'last_login');
    }

}

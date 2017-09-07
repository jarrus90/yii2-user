<?php

namespace jarrus90\User\migrations;

use yii\db\Migration;

class m170707_025917_profile_add_gravatar_id extends Migration {

    public function safeUp() {
        $this->addColumn('{{%user_profile}}', 'gravatar_id', $this->string(32)->null());
    }

    public function safeDown() {
        $this->dropColumn('{{%user_profile}}', 'gravatar_id');
    }

}

<?php

namespace jarrus90\User\migrations;

use yii\db\Migration;

class m170707_025916_profile_fix_timezone extends Migration {

    public function safeUp() {
        $this->alterColumn('{{%user_profile}}', 'timezone', $this->string(40));
    }

    public function safeDown() {
        $this->alterColumn('{{%user_profile}}', 'timezone', $this->string(40)->notNull());
    }

}

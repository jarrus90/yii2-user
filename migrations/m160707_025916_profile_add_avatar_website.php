<?php

use yii\db\Migration;

class m160707_025916_profile_add_avatar_website extends Migration {

    public function safeUp() {
        $this->addColumn('{{%user_profile}}', 'avatar', $this->string(255));
        $this->addColumn('{{%user_profile}}', 'website', $this->string(255));
    }

    public function safeDown() {
        $this->dropColumn('{{%user_profile}}', 'avatar');
        $this->dropColumn('{{%user_profile}}', 'website', $this->string(255));
    }

}

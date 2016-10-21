<?php

namespace jarrus90\User\migrations;

use jarrus90\User\migrations\RbacMigration;

class m160706_075917_user_roles extends RbacMigration {

    public function up() {
        //Create default roles
        $user = $this->createRole('user', 'Default user');
        $admin = $this->createRole('admin', 'Administrator role (using only to check if user can login to adminpanel)');
        $adminContent = $this->createRole('admin_content', 'Content administrator role');
        $adminModerator = $this->createRole('admin_moderator', 'Moderator role');
        $adminSuper = $this->createRole('admin_super', 'Superadmin role');

        $this->assignChildRole($admin, $user);

        $this->assignChildRole($adminSuper, $admin);
        $this->assignChildRole($adminModerator, $admin);
        $this->assignChildRole($adminContent, $admin);
        $this->assignChildRole($adminSuper, $adminModerator);
        $this->assignChildRole($adminSuper, $adminContent);

        //create user roles
        $userAdmin = $this->createRole('user_admin', 'User moderator role');
        $userModerator = $this->createRole('user_moderator', 'User moderator role');

        $this->assignChildRole($userModerator, $admin);

        $this->assignChildRole($userAdmin, $userModerator);
        $this->assignChildRole($adminModerator, $userModerator);
        $this->assignChildRole($adminSuper, $userAdmin);
    }

    public function down() {
        
    }

}

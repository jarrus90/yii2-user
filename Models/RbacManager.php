<?php

namespace jarrus90\User\Models;

class RbacManager extends \yii\rbac\DbManager {

    private $assignmentsCache = array();
    public $cachePrefix = 'RbacDbCache';
    public $assignmentsCachePrefix = 'RbacDbCacheAssignment';

    /**
     * @var string the name of the table storing user_rbacorization items. Defaults to "user_rbac_item".
     */
    public $itemTable = '{{%user_rbac_item}}';

    /**
     * @var string the name of the table storing user_rbacorization item hierarchy. Defaults to "user_rbac_item_child".
     */
    public $itemChildTable = '{{%user_rbac_item_child}}';

    /**
     * @var string the name of the table storing user_rbacorization item assignments. Defaults to "user_rbac_assignment".
     */
    public $assignmentTable = '{{%user_rbac_assignment}}';

    /**
     * @var string the name of the table storing rules. Defaults to "user_rbac_rule".
     */
    public $ruleTable = '{{%user_rbac_rule}}';

    public function getAssignments($userId) {
        if ($this->cache !== null) {
            if (!ISSET($this->assignmentsCache[$userId])) {
                $key = $this->assignmentsCachePrefix . '_' . $userId;
                $data = $this->cache->get($key);
                if ($data === false) {
                    $data = parent::getAssignments($userId);
                    $this->cache->set($key, $data);
                }
                $this->assignmentsCache[$userId] = $data;
            }
            return $this->assignmentsCache[$userId];
        } else {
            return parent::getAssignments($userId);
        }
    }

    public function assign($role, $userId) {
        if ($this->cache !== null) {
            $this->cache->delete($this->assignmentsCachePrefix . '_' . $userId);
        }
        return parent::assign($role, $userId);
    }

    public function revoke($role, $userId) {
        if ($this->cache !== null) {
            $this->cache->delete($this->assignmentsCachePrefix . '_' . $userId);
        }
        parent::revoke($role, $userId);
    }

    public function revokeAll($userId) {
        if ($this->cache !== null) {
            $this->cache->delete($this->assignmentsCachePrefix . '_' . $userId);
        }
        parent::revokeAll($userId);
    }

}

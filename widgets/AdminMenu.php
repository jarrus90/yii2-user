<?php

/**
 * Class AdminMenu
 * 
 * @package app\modules\core\widgets
 */

namespace jarrus90\User\widgets;

use Yii;
use yii\helpers\Url;

/**
 * Admin menu
 * 
 * Builds menu of adminpanel checking if user can access menu item
 */
class AdminMenu extends \yii\bootstrap\Widget {

    /**
     * List of menu items
     * @var srray
     */
    protected $_menuItems;

    /**
     * Initialization
     * 
     * Initialize widget and build menu elements
     */
    public function init() {
        parent::init();
        $this->_menuItems = [];
        if (!Yii::$app->user->isGuest) {
            $this->_menuItems = $this->buildMenu();
        }
    }

    /**
     * Build menu
     * 
     * Passes through modules and builds menu
     * 
     * @return array Menu items
     */
    protected function buildMenu() {
        $menuItems = [];
        $currentModule = Yii::$app->controller->module->id;
        $startPos = 999999;
        foreach(Yii::$app->params['admin']['menu'] AS $key => $list) {
            if($list instanceof \Closure) {
                $list = $list();
            }
            if(!empty($list['items'])) {
                $moduleMenuItems = $this->buildModuleMenu($key, $list['items']);
                if(count($moduleMenuItems) > 0){
                    $menuItems[] = [
                        'label' => $list['label'],
                        'icon' => ISSET($list['icon']) ? $list['icon'] : '',
                        'active' => ( $currentModule == $key ) ? true : false,
                        'childs' => $moduleMenuItems,
                        'position' => !empty($list['position']) ? $list['position'] : $startPos++
                    ];
                }
            } else if (!empty($list['url'])){
                $itemStructure = $this->_getRouteStructure($list['url']);
                $module = Yii::$app->getModule($itemStructure[0]);
                if($module && $this->getIsAllowed($module, $list['url'], $itemStructure[2])) {
                    $menuItems[] = [
                        'label' => $list['label'],
                        'url' => Url::toRoute($list['url']),
                        'icon' => ISSET($list['icon']) ? $list['icon'] : '',
                        'active' => ( $currentModule == $key ) ? true : false,
                        'position' => !empty($list['position']) ? $list['position'] : $startPos++
                    ];
                }
            }
        }
        usort($menuItems, function($a, $b) {
            return $a['position'] > $b['position'];
        });
        return $menuItems;
    }
    
    /**
     * Build module menu
     * 
     * Passes through the menu items described in module
     * and checks their availability for the current user
     * 
     * @param array $items Module menu items
     * @return array Available items
     */
    protected function buildModuleMenu($key, $items){
        $list = [];
        foreach($items AS $item){
            $itemStructure = $this->_getRouteStructure($item['url']);
            $module = Yii::$app->getModule($key);
            if(!$module || !$this->getIsAllowed($module, $item['url'], $itemStructure[2])) {
                continue;
            }
            $list[] = [
                'title' => Yii::t($key, $item['label']),
                'url' => Url::toRoute($item['url'])
            ];
        }
        return $list;
    }
    
    protected function _getRouteStructure($url){
        $itemStructure = explode('/', $url);
        if($itemStructure[0] == '') {
            array_shift($itemStructure);
        }
        return $itemStructure;
    }

    /**
     * Is allowed
     * 
     * Check if user can access specified menu item
     * 
     * @param string $module \yii\base\Module
     * @param string $route Route
     * @param string $actionId action
     * @return boolean Is user allowed to access
     */
    protected function getIsAllowed($module, $route, $actionId) {
        $controller = $module->createController(substr($route, strpos($route, '/', 1) + 1))[0];
        $action = $controller->createAction($actionId);
        $behaviors = $controller->behaviors();
        if(ISSET($behaviors['access'])) {
            $access = Yii::createObject([
                'class' => $behaviors['access']['class'],
                'rules' => $behaviors['access']['rules']
            ]);
            $access->init();
            foreach($access->rules AS $rule){
                if($allow = $rule->allows($action, Yii::$app->user, Yii::$app->getRequest())){
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Render widget
     * @return string
     */
    public function run() {
        return $this->render('adminMenu', [
                    'items' => $this->_menuItems
        ]);
    }

}

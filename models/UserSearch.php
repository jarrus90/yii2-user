<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace jarrus90\User\models;

use jarrus90\User\Finder;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * UserSearch represents the model behind the search form about User.
 */
class UserSearch extends User {

    /** @var string */
    public $name;

    /**
     * @param Finder $finder
     * @param array  $config
     */
    public function __construct($config = []) {
        parent::__construct($config);
    }

    /** @inheritdoc */
    public function rules() {
        return [
            'fieldsSafe' => [['email', 'created_at', 'name', 'confirmed_at', 'blocked_at'], 'safe'],
            'createdDefault' => ['created_at', 'default', 'value' => null],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels() {
        return [
            'username' => Yii::t('user', 'Username'),
            'email' => Yii::t('user', 'Email'),
            'created_at' => Yii::t('user', 'Registration time'),
            'registration_ip' => Yii::t('user', 'Registration ip'),
        ];
    }

    /**
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = self::find()->addSelect([User::tableName() . '.*', Profile::tableName() . '.name'])->joinWith('profile');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                //'pageSize' => 1,
            ],
        ]);

        $dataProvider->sort->attributes['name'] = [
            'asc'   => [self::tableName() . '.id' => SORT_ASC],
            'desc'  => [self::tableName() . '.id' => SORT_DESC],
        ];
        if (($this->load($params) && $this->validate())) {
            if ($this->created_at !== null) {
                $date = strtotime($this->created_at);
                $query->andFilterWhere(['between', 'created_at', $date, $date + 3600 * 24]);
            }
            if (!is_null($this->name) && $this->name != '') {
                $clientName[] = explode(' ', $this->name);
                foreach ($clientName AS $item) {
                    $query->andFilterWhere(['like', Profile::tableName() . '.name' , $item]);
                    /*$query->andFilterWhere(['or',
                        ['like', Profile::tableName() . '.name', $item],
                        ['like', Profile::tableName() . '.surname', $item]
                    ]);*/
                }
            }
            $query->andFilterWhere(['like', 'email', $this->email]);
        }

        return $dataProvider;
    }

    public function formName() {
        return '';
    }
}

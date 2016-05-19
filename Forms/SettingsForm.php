<?php

namespace jarrus90\User\Forms;

use Yii;
use jarrus90\User\Models\User;

/**
 * SettingsForm gets user's name, surname etc
 *
 * @property User $user
 *
 */
class SettingsForm extends \jarrus90\Core\Models\Model {

    /** @var string */
    public $name;

    /** @var string */
    public $surname;

    /** @var string */
    public $phone;

    /** @var string */
    public $adress;
    /** @var string */
    public $adress_street;
    /** @var string */
    public $adress_house;
    public $adress_zip;
    public $fax;
    public $dob;

    /** @var bool * */
    public $gender;

    /** @var string */
    public $salutation;

    /** @var string */
    public $description;
    protected $user;

    /** @inheritdoc */
    public function __construct($user) {
        $this->user = $user;
        $this->setAttributes([
            'name' => $this->user->name,
            'surname' => $this->user->surname,
            'phone' => $this->user->phone,
            'dob' => $this->user->dob,
            'gender' => $this->user->gender,
            'salutation' => $this->user->salutation,
            'description' => $this->user->description,
            'adress_street' => $this->user->adress_street,
            'adress_house' => $this->user->adress_house,
            'adress_zip' => $this->user->adress_zip,
            'fax' => $this->user->fax,
                ], false);
        parent::__construct();
    }

    /**
     * Validation rules
     * @return array
     */
    public function rules() {
        return [
            'nameRequired' => ['name', 'required'],
            'userSafe' => [['name', 'surname'], 'safe'],
            'phoneLength' => ['phone', 'string', 'min' => 6],
            'dobFormat' => ['dob', 'date', 'format' => 'php:Y-m-d'],
            'genderRange' => ['gender', 'in', 'range' => [User::GENDER_MALE, User::GENDER_FEMALE]],
            'salutationLength' => ['salutation', 'string', 'max' => 10],
            'salutationTrim' => ['salutation', 'trim'],
            'descriptionTrim' => ['description', 'trim'],
            'descriptionSafe' => ['description', 'safe'],
            'addressSafe' => [['adress_street','adress_house','adress_zip','fax'], 'safe']
        ];
    }

    public function validateCity($attribute) {
        if(!CityModel::findOne(['id' => $this->city_id, 'country_id' => $this->country_id])) {
            $this->addError($attribute, Yii::t('user', 'Selected city is not in selected country'));
        }
    }
    /**
     * Attribute labels
     * @return array
     */
    public function attributeLabels() {
        return [
            'name' => Yii::t('user', 'First Name'),
            'surname' => Yii::t('user', 'Last Name'),
            'phone' => Yii::t('user', 'Phone number'),
            'dob' => Yii::t('user', 'Date of birth'),
            'gender' => Yii::t('user', 'Gender'),
            'salutation' => Yii::t('user', 'Salutation'),
            'description' => Yii::t('user', 'General info'),
            'adress' => Yii::t('user', 'Adress'),
            'adress_street' => Yii::t('user', 'Street'),
            'adress_house' => Yii::t('user', 'House number'),
            'adress_zip' => Yii::t('user', 'ZIP code'),
            'fax' => Yii::t('user', 'Fax'),
        ];
    }

    /**
     * Returns custom form name
     * @return string
     */
    public function formName() {
        return 'settings-form';
    }

    /**
     * Saves new account settings.
     *
     * @return bool
     */
    public function save() {
        if ($this->validate()) {
            $this->user->scenario = 'settings';
            $this->user->name = $this->name;
            $this->user->surname = $this->surname;
            $this->user->phone = $this->phone;
            $this->user->dob = $this->dob;
            $this->user->gender = $this->gender;
            $this->user->salutation = $this->salutation;
            $this->user->description = $this->cleanTextarea($this->description);
            $this->user->adress_street = $this->adress_street;
            $this->user->adress_house = $this->adress_house;
            $this->user->adress_zip = $this->adress_zip;
            $this->user->fax = $this->fax;
            return $this->user->save();
        }
        return false;
    }

    public function getUserItem() {
        return $this->user;
    }

}

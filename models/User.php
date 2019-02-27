<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use common\models\MyActiveRecord;

class User extends MyActiveRecord implements IdentityInterface
{
    public $new_password;
    public $new_password_repeat;
    public $search;

    public static function tableName()
    {
        return 'users';
    }

    public static function findIdentity($id)
    {
        return static::find()->where(['status'=>'on', 'id'=>$id])->one();
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public static function findByUsername($username)
    {
        $username = str_replace('amica-travel', 'amicatravel', $username);
        return static::find()->where(['status'=>'on', 'login' => $username])->one();
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return $this->uid;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public function rules()
    {
        return [
            [['fname', 'lname', 'name', 'country_code', 'email', 'phone', 'language', 'timezone', 'note'], 'filter', 'filter'=>'trim'],
            [['fname', 'lname', 'name', 'gender'], 'required'],
            [['nickname'], 'required', 'on'=>'meprefs'],
            [['gender'], 'in', 'range'=>['male', 'female', 'other']],
            [['bday', 'bmonth', 'byear'], 'default', 'value'=>0],
            [['bday'], 'integer', 'max'=>31],
            [['bmonth'], 'integer', 'max'=>12],
            [['byear'], 'integer', 'max'=>date('Y')],
            [['email'], 'email'],
            [['image'], 'url'],

            array('email', 'unique', 'message' => 'This email address has already been taken.', 'on' => 'signup'),
            array('email', 'exist', 'message' => 'There is no user with such email.', 'on' => 'forgot'),

            [['fname, lname, name, gender, country_code'], 'required'],
            //

            [[
                'status', 'login', 'name', 'mention', 'language', 'timezone', 'email', 'phone', 'note', 'contact_id',
                ], 'trim'],
            [[
                'status', 'login', 'name', 'mention', 'language', 'timezone', 'email', 'phone',
                ], 'required', 'message'=>Yii::t('x', 'Required')],
            [[
                'login', 'name', 'mention',
                ], 'unique', 'message'=>Yii::t('x', 'Duplicate found')],
            [[
                'contact_id',
                ], 'integer', 'min'=>0, 'message'=>Yii::t('x', 'Invalid')],
            [[
                'new_password', 'new_password_repeat',
                ], 'required', 'on'=>'me/my-settings/password', 'message'=>Yii::t('x', 'Required')],
            [[
                'new_password_repeat',
                ], 'string', 'length'=>[6, 32], 'on'=>['acp/users/c', 'acp/users/u', 'me/my-settings/password'], 'tooLong'=>Yii::t('x', 'Password too long'), 'tooShort'=>Yii::t('x', 'Password too short')],
            [[
                'new_password',
                ], 'compare', 'message'=>Yii::t('x', 'Passwords do not match')],
        ];
    }

    public function scenarios()
    {
        return [
            'login/forgot'=>['email'],
            'me/my-settings/password'=>['new_password', 'new_password_repeat'],
            'acp/users/c'=>['status', 'login', 'name', 'mention', 'language', 'timezone', 'email', 'phone', 'new_password', 'new_password_repeat', 'note', 'contact_id'],
            'acp/users/u'=>['status', 'login', 'name', 'mention', 'language', 'timezone', 'email', 'phone', 'new_password', 'new_password_repeat', 'note', 'contact_id'],
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (($this->isNewRecord || $this->getScenario() === 'reset') && !empty($this->password)) {
                if ($this->getScenario() === 'reset')
                    $this->password_reset_token = '';
                $this->password = Yii::$app->security->generatePasswordHash($this->password);
            }
            return true;
        }
        return false;
    }

    public function getMetas()
    {
        return $this->hasMany(Meta::className(), ['rid' => 'id'])->where(['rtype'=>'user']);
    }

    public function getRefCases()
    {
        return $this->hasMany(Kase::className(), ['id' => 'case_id'])
            ->viaTable('at_referrals', ['user_id'=>'id']);
    }

    // TODO Rename this to Person
    public function getMember()
    {
        return $this->hasOne(Member::className(), ['contact_id' => 'id']);
    }

    public function getProfileTourguide()
    {
        return $this->hasOne(ProfileTourguide::className(), ['user_id' => 'id']);
    }

    public function getProfileCustomer()
    {
        return $this->hasOne(ProfileCustomer::className(), ['user_id' => 'id']);
    }

    public function getProfileDriver()
    {
        return $this->hasOne(ProfileDriver::className(), ['user_id' => 'id']);
    }

    public function getSearch()
    {
        return $this->hasOne(Search::className(), ['rid' => 'id'])->where(['rtype'=>'user']);
    }

    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['code' => 'country_code']);
    }

    public function getRoles()
    {
        return $this->hasMany(Role::className(), ['id' => 'role_id'])
            ->viaTable('at_role_user', ['user_id'=>'id']);
    }

    public function getGroups()
    {
        return $this->hasMany(Group::className(), ['id' => 'group_id'])
            ->viaTable('at_group_user', ['user_id'=>'id']);
    }


    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    // User belongs to all of these roles
    public function hasRoles()
    {
        $roles = func_get_args();
        foreach ($roles as $role) {
            if (is_int($role) && !in_array($role, $this->roles)) return false;
            if (!is_int($role) && !in_array($role, $this->roleNames)) return false;
        }
        return true;
    }

    public function getCases()
    {
        return $this->hasMany(Kase::className(), ['id' => 'case_id'])
            ->viaTable('at_case_user', ['user_id'=>'id']);
    }

    public function getContact()
    {
        return $this->hasOne(Contact::className(), ['id' => 'contact_id']);
    }

    public function hasGroups()
    {
        return $this->hasMany(Term::className(), ['id'=>'term_id'])
            ->viaTable('at_term_rel', ['rid'=>'id'])
            ->where(['at_terms.taxonomy_id'=>1]);
    }

    public function hasTags()
    {
        return $this->hasMany(Term::className(), ['id'=>'term_id'])
            ->viaTable('at_term_rel', ['rid'=>'id'])
            ->where(['at_terms.taxonomy_id'=>2]);
    }

    // User belongs to at least one of these roles
    public function hasOneOfRoles()
    {
        $roles = func_get_args();
        foreach ($roles as $role) {
            if (is_int($role) && in_array($role, $this->roles)) return true;
            if (!is_int($role) && in_array($role, $this->roleNames)) return true;
        }
        return false;
    }


}

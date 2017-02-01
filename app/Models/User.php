<?php

namespace App\Models;

use Auth;
use App\Models\Panel\PanelUser;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Resizer;

class User extends PanelUser implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'gallery',
        'gallery_titles',
        'subscribed',
        'subscription_sent',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        '_password',
        'password',
        'remember_token',
    ];

    //protected $configSet = 'dirs.user';

    protected static function boot()
    {
        parent::boot();

        static::creating(function($model){
            /**
             * @var $model $this
             */
            $model->setAttribute('password', self::encryptPassword($model->getAttribute('password')));
        });
    }

    protected static function encryptPassword($password = '')
    {
        return bcrypt($password);
    }

    public function isMe()
    {
        try{
            if(!Auth::check()) throw new \Exception;

            if($this->id != Auth::user()->id) throw new \Exception;

            return true;
        }catch (\Exception $e){
            return false;
        }
    }

    public function getValidationRules()
    {
        return [
            'name' => 'required',
            'email' => 'required',
        ];
    }

    public function getValidationMessages()
    {
        return [
            'name.required' => 'Укажите имя',
            'email.required' => 'Укажите e-mail',
            'email.email' => 'Укажите реальный e-mail'
        ];
    }

    public function getSignupValidationRules()
    {
        return [
            'name' => 'required',
            'email' => 'required|email|unique:'. $this->getTable() . ',email',
            'password' => 'required',
            'captcha' => 'required|captcha',
        ];
    }

    public function getSignupValidationMessages()
    {
        return [
            'name.required' => 'Укажите имя',
            'email.required' => 'Укажите e-mail',
            'email.email' => 'Укажите реальный e-mail',
            'email.unique' => 'Пользователь с указанным e-mail уже зарегистрирован',
            'password.required' => 'Укажите пароль',
            'captcha.required' => 'Повторите символы',
            'captcha.captcha' => 'Повторите символы верно',
        ];
    }

    public function getForgotValidationRules()
    {
        return [
            'email' => 'required|email',
            'captcha' => 'required|captcha',
        ];
    }

    public function getForgotValidationMessages()
    {
        return [
            'email.required'=>'Укажите e-mail',
            'email.email'=>'Укажите реальный e-mail',
            'captcha.required' => 'Повторите символы',
            'captcha.captcha' => 'Повторите символы верно',
        ];
    }

    public function getLoginValidationRules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }

    public function getLoginValidationMessages()
    {
        return [
            'email.required'=>'Укажите e-mail',
            'email.email'=>'Укажите реальный e-mail',
            'password.required'=>'Укажите пароль',
            'password.min'=>'Укажите пароль',
        ];
    }

    public function getOptions()
    {
        $userPanelModels = $this->PanelModels()->get();

        return compact(
            'userPanelModels'
        );
    }

    public function beforeSave($attrubutes = [])
    {
        $this->setPanelModelIds(!empty($attrubutes['_panel_model_ids']) ? $attrubutes['_panel_model_ids'] : [], !empty($attrubutes['_panel_model_crud']) ? $attrubutes['_panel_model_crud'] : [] );

        if(!empty($attrubutes['_password'])) $this->setAttribute('password', self::encryptPassword($attrubutes['_password']));

        return $this;
    }

    public function deleteProfile($id = 0)
    {
        try{
            $user = !empty($id) ? $this->where('id', '=', intval($id))->first() : Auth::user();

            if(empty($user)) throw new \Exception;

            if(!empty($user->gallery)) Resizer::deleteImages($user->gallery, false, $this->getResizerConfigSet());

            $user->destroy($user->id);

            if(Auth::check()) Auth::logout();

            return true;
        }catch (\Exception $e){
            return false;
        }

    }
}

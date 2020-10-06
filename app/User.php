<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\helper\ViewBuilder;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];
 
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array

    protected $hidden = [
        'password', 'remember_token',
    ];
*/
     

    public function user_roles()
    {
        return $this->hasMany('App\User_role');
    }
    
    public static function auth() {
        $user = User::find(session("user")); 
        return $user;
    }
    
    /**
     * build view object this will make view html
     *
     * @return ViewBuilder 
     */
    public function getViewBuilder() {
        $builder = new ViewBuilder($this, "rtl");
  
        $builder->setAddRoute(url('/user/store'))
                ->setEditRoute(url('/user/update') . "/" . $this->id)
                ->setCol(["name" => "id", "label" => __('id'), "editable" => false])
                ->setCol(["name" => "name", "label" => __('name')]) 
                ->setCol(["name" => "email", "label" => __('email'), "type" => "email"]) 
                ->setCol(["name" => "password", "label" => __('password'), "type" => "password"])  
                ->setUrl(url('/image/user'))
                ->build();

        return $builder;
    }
    
    
}

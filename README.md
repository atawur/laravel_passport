# Laravel passport 
What is Laravel Passport?
Laravel Passport is native OAuth 2 server for Laravel apps. Like Cashier and Scout, 
you'll bring it into your app with Composer. It uses the League OAuth2 Server package 
as a dependency but provides a simple, easy-to-learn and easy-to-implement syntax

### You have to just follow a few steps to get following web services
##### User Login 
##### User Register 
##### User Details 




## Getting Started
### Step i: Install Package(laravel/passport)

```` composer require laravel/passport ````

## open config/app.php file and add the below code in service provider.

```javascript 

config/app.php
'providers' =>[
Laravel\Passport\PassportServiceProvider::class,
],

````

## Step ii: Run Migration and Install

```javascript 

php artisan migrate
php artisan passport:install


````


## Step iii: Passport Configuration  app/User.php

```javascript 

<?php
namespace App;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class User extends Authenticatable
{
  use HasApiTokens, Notifiable;
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
*/
protected $hidden = [
'password', 'remember_token',
];
}

````


## app/Providers/AuthServiceProvider.php



```javascript 

<?php
namespace App\Providers;
use Laravel\Passport\Passport; 
use Illuminate\Support\Facades\Gate; 
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
class AuthServiceProvider extends ServiceProvider 
{ 
    /** 
     * The policy mappings for the application. 
     * 
     * @var array 
     */ 
    protected $policies = [ 
        'App\Model' => 'App\Policies\ModelPolicy', 
    ];
/** 
     * Register any authentication / authorization services. 
     * 
     * @return void 
     */ 
    public function boot() 
    { 
        $this->registerPolicies(); 
        Passport::routes(); 
    } 
}

````

## Step iv :config/auth.php

```javascript 

<?php
return [
'guards' => [ 
        'web' => [ 
            'driver' => 'session', 
            'provider' => 'users', 
        ], 
        'api' => [ 
            'driver' => 'passport', 
            'provider' => 'users', 
        ], 
    ],

````
## Step v: Create API Route

```javascript 

<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['prefix' => 'v1'],function(){
  Route::group(['middleware' => 'auth:api'], function(){
    Route::post('details', 'API\V1\UserController@user_details');
  });
  Route::post('login', 'API\V1\UserController@user_login');
  Route::post('register', 'API\V1\UserController@user_register');
  
});


````


## Step vi: Create the Controller

```javascript 

<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class UserController extends Controller
{
    public $successStatus = 200;
    /** 
     * user login api 
     * 
     * @return \Illuminate\Http\Response 
     */
    public function user_login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('laravel')->accessToken;
            return response()->json(['success' => $success], $this->successStatus);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }
    /** 
     * user Register api 
     * 
     * @return \Illuminate\Http\Response 
     */
    public function user_register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('laravel')->accessToken;
        $success['name'] =  $user->name;
        return response()->json(['success' => $success], $this->successStatus);
    }
    /** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */
    public function user_details()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], $this->successStatus);
    }
}



````
## Step vii: Run 

```javascript 

php artisan serve



````

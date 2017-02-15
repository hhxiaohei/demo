<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Forone\Controllers\BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin/roles';

    protected $currentUser;

    protected $loginView = "forone::auth.login";

    protected $redirectAfterLogout = "login";

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//      $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
        $this->middleware('guest', ['except' => ['logout', 'getLogout']]);
        $this->uri = "";
//        $this->currentUser =Auth::user();
//        View::share('currentUser', $this->currentUser);

//        dd(Auth::guard());
        //share the config option to all the views
        View::share('siteConfig', config('forone.site_config'));
        View::share('pageTitle', $this->loadPageTitle());
        view()->share('page_name', "");
        view()->share('uri', "");
//        parent::__construct();
    }
    private function loadPageTitle()
    {
        $menus = config('forone.menus');
        foreach ($menus as $title => $menu) {
            if (array_key_exists('children', $menu) && $menu['children'] ) {
                foreach ($menu['children'] as $childTitle => $child) {
                    if (strripos(URL::current(), $child['uri'])) {
                        return $title;
                    }
                }
            } else {
                if (strripos(URL::current(), $menu['uri'])) {
                    return $title;
                }
            }
        }
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}

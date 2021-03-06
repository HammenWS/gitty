<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToServiceProvider()
    {
        return Socialite::driver('github')
            ->scopes('repo')
            ->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {
        $user = $this->_findOrCreateGithubUser(
            Socialite::driver('github')->user()
        );

        auth()->login($user);

        return redirect('/');
    }

    public function _findOrCreateGithubUser($githubUser) {
        $user = User::firstOrNew(['github_id' => $githubUser->id]);

        if ($user->exits) return $user;

        $user->fill([
            'github_id' => $githubUser->id,
            'username' => $githubUser->nickname,
            'email' => $githubUser->email,
            'avatar' => $githubUser->avatar,
            'github_token' => $githubUser->token
        ])->save();

        return $user;
    }

}

<?php

namespace App\Http\Controllers;

use GrahamCampbell\GitHub\GitHubFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $user = Auth::user();

        $client = app(GitHubFactory::class)->make([
            'token' => $user->github_token,
            'method' => 'token',
            'cache' => true
        ]);

        
        $repos = $client->api('current_user')->repositories();
        return $repos;
    }
}

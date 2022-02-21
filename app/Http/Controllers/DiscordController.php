<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use \GuzzleHttp;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

class DiscordController extends Controller
{
  protected $tokenURL = "https://discord.com/api/oauth2/token";
  protected $apiURLBase = "https://discord.com/api/users/@me";
  protected $tokenData = [
    "client_id" => NULL,
    "client_secret" => NULL,
    "grant_type" => "authorization_code",
    "code" => NULL,
    "redirect_uri" => NULL,
    "scope" => "identifiy&email"
  ];

  public function tologin()
  {
    if (Auth::check()) {
      return redirect()->route("home");
    };

    if (!session()->has('from')) {
      session(['from' => url()->previous()]);
    }

    return redirect("https://discord.com/oauth2/authorize?client_id=" . config("discord.client_id") . "&redirect_uri=" . config("discord.redirect_uri") . "&response_type=code&scope=identify%20email");
  }

  public function loginCallback(Request $request)
  {
    if (Auth::check()) {
      return redirect()->route("home");
    };
    if ($request->missing("code") && $request->missing("access_token")) {
      return redirect()->route("home");
    };

    $this->tokenData["client_id"] = config("discord.client_id");
    $this->tokenData["client_secret"] = config("discord.client_secret");
    $this->tokenData["redirect_uri"] = config("discord.redirect_uri");
    $this->tokenData["code"] = $request->get("code");

    $client = new GuzzleHttp\Client();

    try {
      $accessTokenData = $client->post($this->tokenURL, ["form_params" => $this->tokenData]);
      $accessTokenData = json_decode($accessTokenData->getBody());
    } catch (\GuzzleHttp\Exception\ClientException $error) {
      abort(500);
    };

    $userData = Http::withToken($accessTokenData->access_token)->get($this->apiURLBase);
    if ($userData->clientError() || $userData->serverError()) {
      abort(500);
    };

    $userData = json_decode($userData);

    // see if we need to cache that avatar
    $user = User::find("id");

    // No avatar or outdated avatar
    if (!$user || $userData->avatar != $user->avatar) {
      $avatarUrl = "https://cdn.discordapp.com/avatars/$userData->id/$userData->avatar";

      $path = Storage::path('public/avatars/' . $userData->id);
      $client->request('GET', $avatarUrl, [
        'sink' => $path,
      ]);
    }

    $user = User::updateOrCreate(
      [
        'id' => $userData->id,
      ],
      [
        'id' => $userData->id,
        'username' => $userData->username,
        'discriminator' => $userData->discriminator,
        'email' => $userData->email,
        'avatar' => $userData->avatar,
        'verified' => $userData->verified,
        'locale' => $userData->locale,
        'mfa_enabled' => $userData->mfa_enabled,
        'refresh_token' => $accessTokenData->refresh_token
      ]
    );

    if (is_null($user->team)) {
      $user->team = rand(1, 2);
      $user->save();
    }

    Auth::login($user, true);

    return redirect(session('from'));
  }

  public function logout(Request $request)
  {
    Auth::logout();
    $request->session()->invalidate();

    return redirect()->back();
  }
}

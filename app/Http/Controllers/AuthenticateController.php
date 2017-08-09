<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthenticateController extends Controller
{

	public function __construct(){
        $this->middleware('jwt.auth', ['except' => ['authenticate']]);
    }

    public function authenticate(Request $request)
    {
        $result = filter_var( $request->email, FILTER_VALIDATE_EMAIL );     //PROVJERA DA LI JE EMAIL UPISAN
        if(!$result) return response()->json(['error' => 'Not email'], 406);

        // grab credentials from the request
        $credentials = request()->only('email', 'password');        //DOHVAT UPISANOG

        try {                                                                   //JWT
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        $user = JWTAuth::toUser($token);
        $permissions = $user->permissions;
        $username = $user->name;
        // all good so return the token
        return response()->json(compact('token', 'permissions', 'username'));
    }


    public function check(){
    	$token = JWTAuth::getToken();		//Dohvaća dobivrni token
    	$user = JWTAuth::toUser($token);	//dohvaća usera za koji je token vezan
    	//return $user;
    	$payload = JWTAuth::parseToken()->getPayload();
    	$expires_at = date('d M Y H:i', $payload->get('exp')); 
    	return ['token_exp' => $expires_at, 'user' => $user];
    }

    public function refreshToken(){
        $token = JWTAuth::getToken();
        $newToken = JWTAuth::refresh($token);
        // all good so return the token
        return response()->json(compact('newToken'));
    }

    public function expTime(){
        $payload = JWTAuth::parseToken()->getPayload();
        $expires_at = date('d.m.Y H:i', $payload->get('exp')); 
        return $expires_at;
    }

}
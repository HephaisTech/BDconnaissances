<?php

namespace App\Http\Controllers;

use App\Mail\VerificationMail;
use App\Models\User;
use Carbon\Carbon;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{

    function register(Request $request)
    {
        # code... 
        try {
            $result = Validator::make($request->all(), [
                'email' => 'required|email|unique:users',
                'password' => 'required|between:8,255|confirmed',
                'name' => 'required',
                'phone' => 'required|between:8,20|unique:users',
            ]);
            if ($result->fails()) {
                return response()->json(['error' => $result->getMessageBag()]);
            }
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'name' => $request->name,
                'phone' => $request->phone,
            ]);
            $user = User::where('email', $user->email)->first();
            $data['user'] = $user;
            $data['token'] = $user->createToken($user->email, [])->plainTextToken;
            Mail::to($user)->send(new VerificationMail($request->getHttpHost() . "/api/v3/confirmUser/$user->email"));
            return $this->jsonResult(true, 'ok', 201, $data);
        } catch (\Throwable $th) {
            return $this->jsonResult(false, 'error', $th->getCode(), $th->getMessage());
        }
    }

    public function login(Request $request)
    {
        try {
            $result = Validator::make($request->all(), [
                'email' => 'required_without:phone|email',
                'phone' => 'required_without:email|between:8,16',
                'password' => 'required|between:8,255',
            ]);
            if ($result->fails()) {
                return response()->json(['error' => $result->getMessageBag()]);
            }
            $emailcredentials = [
                'email' => $request['email'],
                'password' => $request['password'],
            ];
            $phonecredentials = [
                'phone' => $request['phone'],
                'password' => $request['password'],
            ];
            if (!Auth::attempt($emailcredentials) && !Auth::attempt($phonecredentials)) {
                return $this->jsonResult(false, 'Credentials do not match', 401,);
            }
            $user = User::where('email', $request->email)->orWhere('phone', $request->phone)->first();
            $data['user'] = $user;
            $dateExpire = Carbon::now()->add(new DateInterval('PT12H'));
            $data['token'] = $user->createToken($user->email, ['create'], $dateExpire)->plainTextToken;
            return $this->jsonResult(true, "welcome", 200, $data);
        } catch (\Throwable $th) {
            return $this->jsonResult(false, 'error', $th->getCode(), $th->getMessage());
        }
    }

    public function logout(Request $request)
    {
        try {
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            return $this->jsonResult(false, 'error', $th->getCode(), $th->getMessage());
        }
    }
}
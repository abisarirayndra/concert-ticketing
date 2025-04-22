<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use App\Models\UserModel;
use Alert;
use Validator;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function login(){
        return view('auth.login');
    }

    public function register(){
        return view('auth.register');
    }

    public function doRegister(Request $request){
        $validator = Validator::make($request->all(), [
            'user_email' => 'required|unique:user',
        ],[
            'user_email.user' => 'Email sudah terdaftar, daftar dengan email lain!',
        ]);

        $userCreate = UserModel::create([
            'user_name' => $request->user_name,
            'user_name_last' => $request->user_name_last,
            'user_email' => $request->user_email,
            'user_password' => Hash::make($request->user_password),
            'user_role' => 2,
            'user_status' => 1,
        ]);

        Alert::success('Data Berhasil Tersimpan','success');
        return redirect()->route('login');
    }

    public function emailChecking(Request $request)
    {
        $exists = UserModel::where('user_email', $request->user_email)->exists();
        return response()->json(['exists' => $exists]);
    }

    public function doLogin(Request $request){

        $user_check = UserModel::where('user_email', $request->user_email)->first();

        if(!$user_check){
            Alert::success('Login gagal, email tidak terdaftar','error');
            return redirect()->route('login');
        }elseif(!Hash::check($request->user_password, $user_check->user_password)){
            Alert::success('Login gagal, password salah','error');
            return redirect()->route('login');
        }else{
            $credentials = [
                'user_email' => $request->user_email,
                'password' => $request->user_password,
            ];
        
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                if (Auth::user()->user_role == 1) {
                    return redirect()->route('admin.dashboard');
                } else {
                    return redirect()->route('user.dashboard');
                }
            }
        }

    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

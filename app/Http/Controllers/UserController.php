<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\login;
use App\Http\Requests\register;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Carbon;
class UserController extends Controller
{
    public function GetRegister(){
        return view('admin.register');
    }
    public function PostRegister(register $request){
        DB::table('users')->insert(
            [
                'role'=>$request->role,
                'name'=>$request->name,
                'username'=>$request->username,
                'email'=>$request->email,
                'password'=>bcrypt($request->password),
                'organization'=>$request->organization,
                'salary'=>$request->salary,
                'created_at'=>Carbon::now(),
                'created_by'=>1
            ]
        );
        echo "done";
    }

    public function GetLogin(){
    	return view('admin.login');
    }
    public function PostLogin(login $request){
    	if(Auth::attempt(['email'=>$request->email, 'password'=>$request->password])){
            if(Auth::user()->role == 1){
                return redirect('admin/index');
            }else{
                return redirect('mempage');
            }
    	}else{
    		return redirect('admin/login')->with('fail', 'Login failed');
    	}
    }
    public function GetLogout(){
        Auth::Logout();
        return redirect('login');
    }
}

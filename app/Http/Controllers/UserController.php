<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\login;
use App\Http\Requests\register;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function GetRegister(){
        return view('admin.register');
    }
    public function PostRegister(register $request){
        if($request->hasFile('avatar')){
            $file = $request->file('avatar');
            $hinh = Str::random(4).'_'.$file->getClientOriginalName();
            while(file_exists('upload/avatar/'.$hinh)){
                $hinh = Str::random(4).'_'.$file->getClientOriginalName();
            }
            $file->move('upload/avatar', $hinh);
        }else{
            $hinh='user_avatar.jpg';
        }
        DB::table('users')->insert(
            [
                'role'=>$request->role,
                'name'=>$request->name,
                'username'=>$request->username,
                'email'=>$request->email,
                'password'=>bcrypt($request->password),
                'organization'=>$request->organization,
                'salary'=>$request->salary,
                'avatar'=>$hinh,
                'created_at'=>Carbon::now(),
                'created_by'=>Auth::user()->username
            ]
        );
        return redirect('admin/index');
    }

    public function GetLogin(){
    	return view('login');
    }
    public function PostLogin(login $request){
    	if(Auth::attempt(['username'=>$request->username, 'password'=>$request->password])){
            if(Auth::user()->role == 1){
                return redirect('admin/index');
            }else{
                return redirect('mempage');
            }
    	}else{
    		return redirect('login')->with('fail', 'Login failed');
    	}
    }
    public function GetLogout(){
        Auth::Logout();
        return redirect('login');
    }
    public function GetList(){
        return view('admin.users.list');
    }
}

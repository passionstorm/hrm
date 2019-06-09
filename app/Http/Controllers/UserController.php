<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\login;
use App\Http\Requests\register;
use App\Http\Requests\EditUser;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use File;

class UserController extends Controller
{
    public function GetRegister(){
        return view('admin.register');
    }
    public function PostRegister(register $request){
        if($request->hasFile('avatar')){
            $file = $request->file('avatar');
            $hinh = md5(time()).'_'.$file->getClientOriginalName();
            $file->move(public_path('upload/avatar'), $hinh);
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
            }elseif(Auth::user()->role == 2){
                return redirect('staff/index');
            }else{
                return redirect('member/index');
            }
    	}else{
    		return redirect('login')->with('fail', 'Login failed');
    	}
    }
    public function GetLogout(){
        Auth::Logout();
        return redirect('login');
    }

    public function GetListUsersForAdmin(){
        $users = DB::table('users')->where('id','!=', Auth::user()->id)->get();
        return view('admin.users.list', ['users'=>$users]);
    }

    public function GetListUsersForStaff(){
        $users = DB::table('users')->where('id','!=', Auth::user()->id)->get();
        return view('staff.users.list', ['users'=>$users]);
    }

    public function GetEdit($id){
        if(Auth::user()->id == $id || Auth::user()->role == 1){
            $user = DB::table('users')->find($id);
            return view('user.edit', ['user'=>$user]);
        }else{
            echo "You can not access this page";
        }
    }
    public function PostEdit(EditUser $request, $id){
        $user = DB::table('users')->find($id);
        
        //validate password and rpassword
        if($request->ChangePassword == 'on'){           
            $request->validate(
                [
                    'password' => 'required',
                    'rpassword' => 'required'
                ]
            );
            DB::table('users')->where('id', $id)->update(
                [
                    'password'=>bcrypt($request->password),
                ]
            );
        }

        //validate username
        if($request->username != $user->username){
            $request->validate(
                [
                    'username' => 'unique:users,username'
                ]
            );
        }

        //validate email
        if($request->email != $user->email){
            $request->validate(
                [
                    'email' => 'unique:users,email'
                ]
            );
        }

        //validate avatar
        if($request->hasFile('avatar')){
            $file = $request->avatar;
            $name = md5(time()).'_'.$file->getClientOriginalName();
            $file->move(public_path('upload/avatar'), $name);
            $user->avatar = $name;
        }

    
        DB::table('users')->where('id', $id)->update(
            [
                'role'=>$request->role,
                'name'=>$request->name,
                'username'=>$request->username,
                'email'=>$request->email,
                'organization'=>$request->organization,
                'salary'=>$request->salary,
                'avatar'=>$user->avatar,
                'updated_at'=>Carbon::now(),
                'updated_by'=>Auth::user()->username
            ]
        );
        return redirect('user/edit/'.$id)->with('success', 'successful editing!');
    }

    public function GetDeleteUser($id){
        DB::table('users')->where('id', $id)->update(
            [
                'is_deleted'=>1
            ]
        );
        return redirect('admin/users/list')->with('success', 'Delete user successfully!');
    }
}

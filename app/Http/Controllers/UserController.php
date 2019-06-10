<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\login;
use App\Http\Requests\PostUser;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use File;
use Constants;

class UserController extends Controller
{

    public function GetLogin(){
    	return view('login');
    }

    public function PostLogin(login $request){
    	if(Auth::attempt(['username'=>$request->username, 'password'=>$request->password])){
                return redirect('index');
    	}else{
    		return redirect('login')->with('fail', 'Login failed');
    	}
    }

    public function GetIndexPage(){
        return view('pages.index');
    }

    public function GetLogout(){
        Auth::Logout();
        return redirect('login');
    }

    public function GetList(){
        $users = DB::table('users')->where('id','!=', Auth::user()->id)->get();
        return view('users.list', ['users'=>$users]);
    }

    public function GetDeleteUser($id){
        DB::table('users')->where('id', $id)->update(
            [
                'is_deleted'=>1
            ]
        );
        return redirect('users/list')->with('success', 'Delete user successfully!');
    }

    public function GetPost($id = null){
        if( $id ){
            //Get Edit user section

            //only admin and current user
            if(Auth::user()->id == $id || Auth::user()->role == 1){
                $user = DB::table('users')->find($id);
                return view('users.post', ['user'=>$user]);
            }else{
                abort(401);
            }
        }else{
            //Get Add user section

            //Only Admin
            if(Auth::user()->role != Constants::ROLES['admin']){
                return abort(401);
            }

            return view('users.post');
        }
    }

    public function PostPost(PostUser $request, $id = null){
        if( $id ){//post Edit user section

            $user = DB::table('users')->find($id);

            //validate email if email is changed
            if($request->email != $user->email){
                $request->validate(
                    [
                        'email' => 'unique:users,email'
                    ]
                );
            }

            //save file if available
            if($request->hasFile('avatar')){
                $file = $request->avatar;
                $name = md5(time()).'_'.$file->getClientOriginalName();
                $file->move(public_path('upload/avatar'), $name);
                $user->avatar = $name;//cover case of no file
            }

            //only admin case. because attr of select input must be disabled, must save the changes of role and salary
            if(Auth::user()->role == Constants::ROLES['admin']){
                $user->role = $request->role;
                $user->organization = $request->organization;
                $user->salary = $request->salary;
            }

            DB::table('users')->where('id', $id)->update(
                [
                    'role'=>$user->role,
                    'name'=>$request->name,
                    'email'=>$request->email,
                    'organization'=>$user->organization,
                    'salary'=>$user->salary,
                    'avatar'=>$user->avatar,
                    'updated_at'=>Carbon::now(),
                    'updated_by'=>Auth::user()->username
                ]
            );
            return redirect('users/post/'.$id)->with('success', 'successful editing!');
            
        }else{//post Add user section
            
            // validate unique username, role, salary, password
            $request->validate(
                [
                    'username' => 'required|unique:users,username',
                    'role' => 'required',
                    'salary' => 'required',
                    'password' => 'required',
                    'retype_password' => 'required'
                ]
            );

            //insert to db
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
            return redirect('users/post')->with('success', 'Additional success!');
        }
    }


    //this is for reference
    // public function PostEdit(EditUser $request, $id){
                
    //     //validate password and rpassword. Then save it
    //     // if($request->ChangePassword == 'on'){           
    //     //     $request->validate(
    //     //         [
    //     //             'password' => 'required',
    //     //             'rpassword' => 'required'
    //     //         ]
    //     //     );
    //     //     DB::table('users')->where('id', $id)->update(
    //     //         [
    //     //             'password'=>bcrypt($request->password),
    //     //         ]
    //     //     );
    //     // }

    //     //validate username
    //     // if($request->username != $user->username){
    //     //     $request->validate(
    //     //         [
    //     //             'username' => 'unique:users,username'
    //     //         ]
    //     //     );
    //     // }

    //     $user = DB::table('users')->find($id);

    //     //validate email
    //     if($request->email != $user->email){
    //         $request->validate(
    //             [
    //                 'email' => 'unique:users,email'
    //             ]
    //         );
    //     }

    //     //save file if available
    //     if($request->hasFile('avatar')){
    //         $file = $request->avatar;
    //         $name = md5(time()).'_'.$file->getClientOriginalName();
    //         $file->move(public_path('upload/avatar'), $name);
    //         $user->avatar = $name;//cover case of no file
    //     }

    //     //only admin. because attr of select input must be disabled
    //     if(Auth::user()->role == Constants::ROLE_ADMIN){
    //         $user->role = $request->role;
    //         $user->organization = $request->organization;
    //         $user->salary = $request->salary;
    //     }

    //     DB::table('users')->where('id', $id)->update(
    //         [
    //             'role'=>$user->role,
    //             'name'=>$request->name,
    //             // 'username'=>$request->username,
    //             'email'=>$request->email,
    //             'organization'=>$user->organization,
    //             'salary'=>$user->salary,
    //             'avatar'=>$user->avatar,
    //             'updated_at'=>Carbon::now(),
    //             'updated_by'=>Auth::user()->username
    //         ]
    //     );
    //     return redirect('users/edit/'.$id)->with('success', 'successful editing!');
    // }
}

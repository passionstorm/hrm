<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Http\Requests\login;
use App\Http\Requests\PostUser;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class UserController extends Controller
{

    /**
     * @return Factory|View
     */
    public function GetLogin()
    {
        return view('login');
    }

    /**
     * @param login $request
     * @return RedirectResponse|Redirector
     */
    public function PostLogin(login $request)
    {
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            return redirect('index');
        } else {
            return redirect('login')->with('fail', 'Login failed');
        }
    }

    /**
     * @return Factory|View
     */
    public function GetIndexPage()
    {
        return view('pages.index');
    }

    /**
     * @return RedirectResponse|Redirector
     */
    public function GetLogout()
    {
        Auth::Logout();
        return redirect('login');
    }

    /**
     * @return Factory|View
     */
    public function GetList()
    {
        $users = DB::table('users')->where('id', '!=', Auth::user()->id)->get();
        return view('users.list', ['users' => $users]);
    }

    /**
     * @param $id
     * @return RedirectResponse|Redirector
     */
    public function DeleteUser($id)
    {
        DB::table('users')->where('id', $id)->update(
            [
                'is_deleted' => Constants
            ]
        );
        return redirect('users/list')->with('success', 'Delete user successfully!');
    }

    /**
     * @param null $id
     * @return Factory|View
     */
    public function EditUser($id = null)
    {
        if ($id) {
            $user = DB::table('users')->find($id);
            return view('users.post', ['user' => $user]);
        }

        return view('users.post');
    }

    /**
     * @param Request $request
     * @param null $id
     * @return RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function PostUser(Request $request, $id = null)
    {
        $rule = [
            'email' => 'required',
            'retype_password' => 'same:password',
            'name' => 'required',
            'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];
        $this->validate($request, $rule);
        if ($id) {//post Edit user section
            $user = DB::table('users')->find($id);
            //validate email if email is changed
            if ($request->email != $user->email) {
                $this->validate($request, ['email' => 'unique:users,email']);
            }

            //save file if available
            if ($request->hasFile('avatar')) {
                $file = $request->avatar;
                $name = md5(time()) . '_' . $file->getClientOriginalName();
                $file->move(public_path('upload/avatar'), $name);
                $user->avatar = $name;//cover case of no file
            }

            //only admin case. because attr of select input must be disabled, must save the changes of role and salary
            if (Auth::user()->role == Constants::ROLE_ADMIN) {
                $user->role = $request->role;
                $user->organization = $request->organization;
                $user->salary = $request->salary;
            }

            DB::table('users')->where('id', $id)->update(
                [
                    'role' => $user->role,
                    'name' => $request->name,
                    'email' => $request->email,
                    'organization' => $user->organization,
                    'salary' => $user->salary,
                    'avatar' => $user->avatar,
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::user()->username
                ]
            );
            return redirect('users/edit/' . $id)->with('success', 'successful editing!');
        }

        //insert to db
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $hinh = md5(time()) . '_' . $file->getClientOriginalName();
            $file->move(public_path('upload/avatar'), $hinh);
        } else {
            $hinh = 'user_avatar.jpg';
        }
        DB::table('users')->insert(
            [
                'role' => $request->role,
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'organization' => $request->organization,
                'salary' => $request->salary,
                'avatar' => $hinh,
                'created_at' => Carbon::now(),
                'created_by' => Auth::user()->username
            ]
        );
        return redirect('users/edit')->with('success', 'Additional success!');
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

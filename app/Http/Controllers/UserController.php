<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Http\Requests\loginRequest;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class UserController extends Controller
{

    /**
     * @return Factory|View
     */
    public function GetLogin(Request $request)
    {
        $username  = $request->cookie('username');
        $password = $request->cookie('password');
        $rm = $request->cookie('rm');
        return view('login', [
            'username'=>$username,
            'password'=>$password,
            'rm'=>$rm,
        ]);
    }

    /**
     * @param login $request
     * @return RedirectResponse|Redirector
     */
    public function PostLogin(login $request)
    {
        $remember_me = $request->has('remember_me') ? true : false;
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password], $remember_me)) {
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
        $user = Auth::user();
        $numberOfEmployees = DB::table('users')->where('company_id', $user->company_id)->count();
        $numberOfProjects = DB::table('projects')->where('company_id', $user->company_id)->count();
        return view('pages.index',[
            'numberOfEmployees'=>$numberOfEmployees,
            'numberOfProjects'=>$numberOfProjects,
        ]);
    }

    /**
     * @return Factory|View
     */
    public function getList()
    {
        $user = Auth::user();
        $users = DB::table('users')->where([
            ['id', '!=', $user->id],
            ['company_id', $user->company_id],
        ])->get();
        return view('users.list', ['users' => $users]);
    }

    /**
     * @param $id
     * @return RedirectResponse|Redirector
     */
    public function deleteUser($id)
    {
        DB::table('users')->where('id', $id)->update(['is_deleted' => Constants::IS_DELETED]);
        return redirect('users/list')->with('success', 'Delete user successfully!');
    }

    /**
     * @param null $id
     * @return Factory|View
     */
    public function editUser($id = null)
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
    public function postUser(Request $request, $id = null)
    {
        $rule = [
            'retype_password' => 'same:password',
            'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];
        $this->validate($request, $rule);
        if(Auth::check()){
            $currentUser = Auth::user();
            $createdBy = $currentUser->username;
        }else{
            $createdBy = $request->username;
        }
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
                $user->salary = $request->salary;
            }

            DB::table('users')->where('id', $id)->update(
                [
                    'role' => $user->role,
                    'name' => $request->name,
                    'email' => $request->email,
                    'salary' => $user->salary,
                    'avatar' => $user->avatar,
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::user()->username
                ]
            );
            return redirect('users/edit/' . $id)->with('success', 'successful editing!');
        }

        //Add user 
        $this->validate($request, ['username' => 'unique:users,username']);
        $this->validate($request, ['email' => 'unique:users,email']);
        $this->validate($request, ['company_name' => 'unique:companies,company_name']);
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $avatar = md5(time()) . '_' . $file->getClientOriginalName();
            $file->move(public_path('upload/avatar'), $avatar);
        } else {
            $avatar = 'user_avatar.jpg';
        }
        if($request->firstRegister){
            DB::table('companies')->insert([
                'company_name' => $request->company_name,
            ]);
            $companyId = DB::table('companies')->where('company_name', $request->company_name)->get()[0]->id;
        }else{
            $companyId = $currentUser->company_id;
        }
        DB::table('users')->insert(
            [
                'role' => $request->role,
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'company_id' => $companyId,
                'password' => bcrypt($request->password),
                'salary' => $request->salary,
                'avatar' => $hinh,
                'created_at' => Carbon::now(),
                'created_by' => $createdBy,
            ]
        );
        if($request->firstRegister){
            Auth::attempt(['username' => $request->username, 'password' => $request->password]);
            return redirect('index');
        }
        return redirect('users/edit')->with('success', 'Additional success!');
    }

}

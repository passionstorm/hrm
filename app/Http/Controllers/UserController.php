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
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class UserController extends Controller
{

    /**
     * @return Factory|View
     */
    public function GetIndexPage()
    {
        return view('pages.index');
    }

    /**
     * @return Factory|View
     */
    public function GetList()
    {
        $users = DB::table('users')->where('id', '!=', Auth::id())->get();
        return view('users.list', ['users' => $users]);
    }

    /**
     * @param $id
     * @return RedirectResponse|Redirector
     */
    public function DeleteUser($id)
    {
        DB::table('users')->where('id', $id)->update(['is_deleted' => Constants::IS_DELETED]);
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
            $avatar = md5(time()) . '_' . $file->getClientOriginalName();
            $file->move(public_path('upload/avatar'), $avatar);
        } else {
            $avatar = 'user_avatar.jpg';
        }
        DB::table('users')->insert([
            'role' => $request->input('role'),
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'organization' => $request->input('organization'),
            'salary' => $request->input('salary'),
            'avatar' => $avatar,
            'created_at' => Carbon::now(),
            'created_by' => Auth::user()->username,
            'updated_at' => Carbon::now(),
            'updated_by' => Auth::user()->username,
        ]);
        return redirect('users/edit')->with('success', 'Additional success!');
    }

}

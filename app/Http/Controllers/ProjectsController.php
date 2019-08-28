<?php

namespace App\Http\Controllers;

use App\Constants;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;


class ProjectsController extends Controller
{
    /**
     * @return Factory|View
     */
    public function GetList()
    {
        $data = DB::table('projects')->get();

        return view('projects.list', ['projects' => $data]);
    }

    /**
     * @param $id
     * @return RedirectResponse|Redirector
     */
    public function DeleteProject($id)
    {
        DB::table('projects')
            ->where('id', $id)
            ->update(['is_deleted' => Constants::IS_DELETED]);

        return redirect('projects/list')->with('success', 'close the project successfully!');
    }


    /**
     * @param null $id
     * @return Factory|View
     */
    public function EditProject($id = null)
    {
        if ($id) {
            $data = DB::table('projects')->find($id);

            return view('projects.edit', ['project' => $data]);
        }

        return view('projects.edit');
    }


    /**
     * @param Request $request
     * @param null $id
     * @return RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function PostProject(Request $request, $id = null)
    {
        $rules = [
            'name' => 'required',
            'c_name' => 'required',
            'budget' => 'required',
            'deadline' => 'required|date|after:today',
        ];
        $errorMessages = [
            'name.required' => 'Project name cannot be empty',
            'c_name.required' => 'Customer name cannot be empty',
            'budget.required' => 'Budget cannot be empty',
            'deadline.required' => 'Deadline cannot be empty',
        ];

        $this->validate($request, $rules, $errorMessages);
        $c_country = isset(Constants::COUNTRIES[$request->c_country]) ? Constants::COUNTRIES[$request->c_country] : '';

        if ($id) {
            $project = DB::table('projects')->find($id);
            //validate name if it's changed
            if ($project->name != $request->name) {
                $this->validate($request, ['name' => 'unique:projects,name']);
            }

            DB::table('projects')->where('id', $id)->update(
                [
                    'c_country' => $c_country,
                    'c_name' => $request->c_name,
                    'name' => $request->name,
                    'budget' => $request->budget,
                    'deadline' => $request->deadline,
                    'describe' => $request->describe,
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::user()->username,
                ]
            );

            return redirect('projects/edit/' . $id)->with('success', 'Successful project editing!');
        }

        DB::table('projects')->insert(
            [
                'c_country' => $c_country,
                'c_name' => $request->c_name,
                'name' => $request->name,
                'budget' => $request->budget,
                'deadline' => $request->deadline,
                'describe' => $request->describe,
                'created_at' => Carbon::now(),
                'created_by' => Auth::user()->username,
            ]
        );

        return redirect('projects/edit')->with('success', 'Additional project successfully');
    }

    public function AddParticipants($id)
    {
        $project = DB::table('projects')->find($id);
        $project_name = $project->name;
        $project_id = $project->id;
        $project_participants = explode(',', $project->participants);

        $users = DB::table('users')
            ->where('is_deleted', 0)
            ->get(['id', 'name']);

        return view('projects.participants', [
            'users' => $users,
            'project_name' => $project_name,
            'project_id' => $project_id,
            'project_participants' => $project_participants
        ]);
    }

}

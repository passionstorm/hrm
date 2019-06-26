<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PostProject;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use DB;
use Constants;


class ProjectsController extends Controller
{

   public function GetList(){
   	$projects = DB::table('projects')->get();
   	return view('projects.list', ['projects'=>$projects]);
   }

   public function GetDelete($id){
   	DB::table('projects')->where('id',$id)->update(
   		[
   			'is_deleted'=>1
   		]
   	);
   	return redirect('projects/list')->with('success', 'close the project successfully!');
   }

    public function GetPost($id = null){
        if( $id ){
            //Get Edit section

            //Only Admin
            if(Auth::user()->role != Constants::ROLES['admin']){
                return abort(401);
            }

            $project = DB::table('projects')->find($id);
            return view('projects.post', ['project'=>$project]);
        }else{
            //Get Add section

            //Only Admin
            if(Auth::user()->role != Constants::ROLES['admin']){
                return abort(401);
            }

            return view('projects.post');
        }
    }

    public function PostPost(PostProject $request, $id = null){
        if( $id ){//post Edit user section

            $project = DB::table('projects')->find($id);

            //validate name if it's changed
            if($project->name != $request->name){
               $request->validate(
                  [
                     'name'=>'unique:projects,name'
                  ]
               );
            }

           if($request->c_country == 0){
            $c_country=Constants::COUNTRIES['vn'];
           }elseif($request->c_country == 1){
            $c_country=Constants::COUNTRIES['jp'];
           }
           
           DB::table('projects')->where('id', $id)->update(
            [
               'c_country'=>$c_country,
               'c_name'=>$request->c_name,
               'name'=>$request->name,
               'budget'=>$request->budget,
               'deadline'=>$request->deadline,
               'describe'=>$request->describe,
               'updated_at'=>Carbon::now(),
               'updated_by'=>Auth::user()->username,
            ]
           );
           return redirect('projects/post/'.$id)->with('success', 'Successful project editing!');
            
        }else{//post Add section
            
            $request->validate(
               [
                  'name'=>'unique:projects,name'
               ]
            );

            if($request->c_country == 0){
               $c_country=Constants::COUNTRIES['vn'];
            }elseif($request->c_country == 1){
               $c_country=Constants::COUNTRIES['jp'];
            }
            
            DB::table('projects')->insert(
               [
                  'c_country'=>$c_country,
                  'c_name'=>$request->c_name,
                  'name'=>$request->name,
                  'budget'=>$request->budget,
                  'deadline'=>$request->deadline,
                  'describe'=>$request->describe,
                  'created_at'=>Carbon::now(),
                  'created_by'=>Auth::user()->username,
               ]
            );
            return redirect('projects/post')->with('success', 'Additional project successfully');
         }
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddProject;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use DB;
use Constants;


class ProjectsController extends Controller
{
   public function GetAdd(){
   	return view('projects.add');
   }

   public function PostAdd(AddProject $request){
   		if($request->c_country == 0){
   			$c_country=Constants::COUNTRY_VN;
   		}elseif($request->c_country == 1){
   			$c_country=Constants::COUNTRY_JP;
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
   		return redirect('projects/add')->with('success', 'Additional project successfully');
   }

   public function GetList(){
   	$projects = DB::table('projects')->get();
   	return view('projects.list', ['projects'=>$projects]);
   }

   public function GetDelete($id){
   	$projects = DB::table('projects')->where('id',$id)->update(
   		[
   			'is_deleted'=>1
   		]
   	);
   	return redirect('projects/list')->with('success', 'close the project successfully!');
   }

   public function GetEdit($id){
   	$project = DB::table('projects')->find($id);
   	return view('projects.edit', ['project'=>$project]);
   }

   public function PostEdit(AddProject $request, $id){
   		if($request->c_country == 0){
   			$c_country=Constants::COUNTRY_VN;
   		}elseif($request->c_country == 1){
   			$c_country=Constants::COUNTRY_JP;
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
   		return redirect('projects/edit/'.$id)->with('success', 'Successful project editing!');
   }
}

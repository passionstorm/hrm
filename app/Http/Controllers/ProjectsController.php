<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;


class ProjectsController extends Controller
{
   public function GetAdd(){
   	return view('projects.add');
   }
}

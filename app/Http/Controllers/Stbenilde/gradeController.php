<?php namespace App\Http\Controllers\stbenilde;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Contracts\Auth\Guard;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Http\Models\Stbenilde\Tblattendance as Tblattendance;
use Auth;
use DB;


class gradeController extends Controller
{

	public function index(){ 

	 	$auth = Auth::user();

	 	$grades = DB::table('tblgrades')->whereIn('studID', [$auth->studnum])->get(); 

	 	$studenrolled = DB::table('tblstudenrolled')->get();

	 	$studname = DB::table('tblstudname')->whereIn('studID', [$auth->studnum])->get();

	 	if(empty($studname)){

		 	$msg = "Student Number you entered doesnt match our records.";
		 	flash()->error($msg);

		 	$studfullname = 'Unknown Student';
		 	$studid = '00-0000-00';

		}else{

			$msg = "Welcome!";
		 	flash()->success($msg);

		 	$studfullname = ucfirst($studname[0]->FirstName) . " " . ucfirst($studname[0]->MiddleName) . " " . ucfirst($studname[0]->LastName);
		 	
		 	$studid = $studname[0]->studID;

		}

	 	$subject_polish = array();
		$date_polish = array();



	 	$subject = array_unique($subject_polish); 

	 	$date = array_unique($date_polish); 
	 	$date = array_slice($date, -7);
	 	

	 	return view('stbenilde.grade.index',compact('grades','auth','studfullname','studid','subject','date'));		

	}  
}
    
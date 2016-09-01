<?php namespace App\Http\Controllers\stbenilde;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Contracts\Auth\Guard;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Http\Models\Stbenilde\Tblattendance as Tblattendance;
use Auth;
use DB;


class attendanceController extends Controller
{

	public function index(){ 

		$auth = Auth::user();

	 	$attendance = DB::table('tblattendance')->whereIn('studID', [$auth->active_stud_num])->get(); 

	 //	print_r($attendance); exit;
	 
	 	$studenrolled = DB::table('tblstudenrolled')->get();

	 	$studname = DB::table('tblstudname')->whereIn('studID', [$auth->active_stud_num])->get();

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

		foreach($attendance as $attendance_list){

	 		$subject_raw = array_push($subject_polish,ucfirst($attendance_list->Subject));
	 		$date_raw = array_push($date_polish,ucfirst(date("m-d-Y", strtotime($attendance_list->Date))));
	 		 
	 	}

	 	$subject = array_unique($subject_polish); 

	 	$date = array_unique($date_polish); 
	 	$date = array_slice($date, -7);

	 	$arr = [];
		$arr2 = [];

		foreach ($attendance as $v) {
		

			if($v->Status =='Absent'){

				$color = '#FFABAB';
			}else{
				$color = '#6EB5FF';

	
		// /	print_r( $v->Date); exit; 

			$date = strtr( $v->Date, '/', '-');
			$newDate = date("Y-m-d", strtotime($date));
			$arr2[] = [
			'title' => $v->Status,
		
			'start' => '2016/9/1 5:00 ',
			'end' => '2016/9/1 23:00 ',
			'color'  => $color,
			'url' => '/rosebud/schedule/show/'
			];				
		
		}
	 	
	 	$arr = json_encode($arr2);
	 	return view('stbenilde.attendance.index',compact('attendance','auth','studfullname','studid','subject','date','arr'));		

	 }
	}  
}
    
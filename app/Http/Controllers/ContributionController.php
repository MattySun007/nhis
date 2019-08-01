<?php

namespace App\Http\Controllers;

use App\Models\Adoptee;
use App\Models\User;
use App\Models\Contribution;
use Log;
use App\Utilities\Utility;
use App\Models\Institution;
use Illuminate\Support\Facades\DB;

class ContributionController extends Controller
{

  public function index()
  {
    //dd(redirect()->intended()->getTargetUrl());
    $adoptees = $institutions = [];
    $user = null;
    $item = '';
    if(auth()->user()->user_type == 'Institution User'){
      $institutions = Institution::whereIn('id', auth()->user()->user_institutions)->with(['town', 'country', 'state', 'lga'])->get()->all();
      $item = 'institution';
    }
    if(auth()->user()->user_type == 'Individual Contributor'){
      $user = User::where('id', auth()->user()->id)->first();
      $item = 'individual';
    }
    if(User::isAdoptor()){
      $adoptees = Adoptee::where('user_id', auth()->user()->id)->get();
    }
    //dd(json_encode(Utility::listYear()));
    return view('contributions.list', [
      'itemTitle' => $item,
      'years' => Utility::listYear(),
      'months' => Utility::listMonth(),
      'user' => $user,
      'institutions' => $institutions,
      'adoptees' => $adoptees
    ]);
  }

  public function history()
  {
    $data = $this->request->all();
    //$data = array('month' => 6,'year' => 2019, 'institution_id' => 1);
    $result = $adoptees = [];
    if(auth()->user()->user_type == 'Institution User'){
      $result = Contribution::where([['month', $data['month']], ['year',$data['year']], ['approved', 1]])
        ->whereIn('user_id', DB::table('institution_user')->where('institution_id', $data['institution_id'])->pluck('user_id'))->with(['user'])->get()->all();
    }
    if(auth()->user()->user_type == 'Individual Contributor'){
      $result = Contribution::where([['user_id', auth()->user()->id], ['month', $data['month']], ['year',$data['year']], ['approved', 1]])->with(['user'])->get()->all();
    }
    if(User::isAdoptor()){
      $adoptees = Contribution::where([['month', $data['month']], ['year',$data['year']], ['approved', 1]])
        ->whereIn('user_id', DB::table('adoptees')->where('user_id', auth()->user()->id)->pluck('adoptee_id'))->with(['user'])->get()->all();
    }
    return response()->json([
      'success' => (bool) $result,
      'message' => 'Records fetched',
      'data' => ['result' => $result, 'adoptees' => $adoptees]
    ]);
  }

  public function generateBatchCode($m, $y)
  {
    $seed = "NHIS_";
    a:
    $code = Utility::generateRandomNumber(6);
    $code = $seed.$code.'_'.$m.'_'.$y;
    if(Contribution::where('batch_code', $code)->count() <= 0){
      return $code;
    }else{
      goto a;
    }
  }

  public function getBatchCode($m, $y)
  {
    return response()->json([
      'success' => true,
      'message' => 'Batch_code generated',
      'data' => array('batch_code' => $this->generateBatchCode($m, $y))
    ]);
  }

  public function process()
  {
    $data = $this->request->all();
    $result = $adoptees = [];
    if(auth()->user()->user_type == 'Institution User'){
      $result = User::whereIn('id', DB::table('institution_user')->where('institution_id', $data['institution_id'])->pluck('user_id'))
        ->whereNotIn('id', DB::table('contributions')->where([['month', $data['month']], ['year',$data['year']], ['processed', 1]])->pluck('user_id'))->get()->all();
        //->with(['blood_group', 'genotype', 'marital_status', 'gender'])->get()->all();
    }
    if(auth()->user()->user_type == 'Individual Contributor'){
      $result = User::where('id', auth()->user()->id)
        ->whereNotIn('id', DB::table('contributions')->where([['month', $data['month']], ['year',$data['year']], ['processed', 1]])->pluck('user_id'))->get()->all();
    }
    if(User::isAdoptor()){
      $adoptees = User::whereIn('id', DB::table('adoptees')->where('user_id', auth()->user()->id)->pluck('adoptee_id'))
        ->whereNotIn('id', DB::table('contributions')->where([['month', $data['month']], ['year',$data['year']], ['processed', 1]])->pluck('user_id'))->get()->all();
    }
    return response()->json([
      'success' => (bool) $result || $adoptees,
      'message' => 'Records fetched',
      'data' => ['result' => $result, 'adoptees' => $adoptees]
    ]);
  }

  public function approve()
  {
    $data = $this->request->all();
    $result = $adoptees = [];
    if(auth()->user()->user_type == 'Institution User'){
      $result = User::whereIn('id', DB::table('institution_user')->where('institution_id', $data['institution_id'])->pluck('user_id'))
        ->whereIn('id', DB::table('contributions')->where([['month', $data['month']], ['year',$data['year']], ['processed', 1], ['approved', 0]])->pluck('user_id'))->get()->all();
    }
    if(auth()->user()->user_type == 'Individual Contributor'){
      $result = User::where('id', auth()->user()->id)
        ->whereIn('id', DB::table('contributions')->where([['month', $data['month']], ['year',$data['year']], ['processed', 1], ['approved', 0]])->pluck('user_id'))->get()->all();
    }
    if(User::isAdoptor()){
      $adoptees = User::whereIn('id', DB::table('adoptees')->where('user_id', auth()->user()->id)->pluck('adoptee_id'))
        ->whereIn('id', DB::table('contributions')->where([['month', $data['month']], ['year',$data['year']], ['processed', 1], ['approved', 0]])->pluck('user_id'))->get()->all();
    }
    return response()->json([
      'success' => (bool) $result || $adoptees,
      'message' => 'Records fetched',
      'data' => ['result' => $result, 'adoptees' => $adoptees]
    ]);
  }

  public function pay()
  {
    $data = $this->request->all();
    $result = $adoptees = [];
    if(auth()->user()->user_type == 'Institution User'){
      $result = User::whereIn('id', DB::table('institution_user')->where('institution_id', $data['institution_id'])->pluck('user_id'))
        ->whereIn('id', DB::table('contributions')->where([['month', $data['month']], ['year',$data['year']], ['processed', 1], ['approved', 1], ['paid', 0]])->pluck('user_id'))->get()->all();
    }
    if(auth()->user()->user_type == 'Individual Contributor'){
      $result = User::where('id', auth()->user()->id)
        ->whereIn('id', DB::table('contributions')->where([['month', $data['month']], ['year',$data['year']], ['processed', 1], ['approved', 1], ['paid', 0]])->pluck('user_id'))->get()->all();
    }
    if(User::isAdoptor()){
      $adoptees = User::whereIn('id', DB::table('adoptees')->where('user_id', auth()->user()->id)->pluck('adoptee_id'))
        ->whereIn('id', DB::table('contributions')->where([['month', $data['month']], ['year',$data['year']], ['processed', 1], ['approved', 1], ['paid', 0]])->pluck('user_id'))->get()->all();
    }
    return response()->json([
      'success' => (bool) $result || $adoptees,
      'message' => 'Records fetched',
      'data' => ['result' => $result, 'adoptees' => $adoptees]
    ]);
  }


  public function doProcess()
  {
    $data = $this->request->all();
    $params = [
      'user_id' => $data['user_id'],
      'amount' => $data['amount'],
      'month' => $data['month'],
      'year' => $data['year'],
      'processed' => 1,
      'processed_by' => auth()->user()->id,
      'processed_at' =>  \Carbon\Carbon::now(),
      'created_at' => \Carbon\Carbon::now(),
      'updated_at' => \Carbon\Carbon::now()
    ];
    $dup = ['amount','updated_at'];
    $save = Contribution::insertOnDuplicateKey($params, $dup);
    return response()->json([
      'success' => (bool) $save,
      'message' => $save ? 'User contribution processed' : 'User contribution not processed',
      'data' => ['result' => $save]
    ]);
  }

  public function doApprove()
  {
    $data = $this->request->all();
    $params = [
      'approved' => 1,
      'approved_by' => auth()->user()->id,
      'approved_at' => \Carbon\Carbon::now(),
      'updated_at' => \Carbon\Carbon::now()
    ];
    $save = Contribution::where([['user_id', $data['user_id']],['month', $data['month']],['year', $data['year']],['approved', 0]])->update($params);
    return response()->json([
      'success' => (bool) $save,
      'message' => $save ? 'User contribution approved' : 'User contribution not approved',
      'data' => ['result' => $save]
    ]);
  }

  public function doPay()
  {
    $data = $this->request->all();
    $bcode = $this->generateBatchCode($data['month'], $data['year']);
    $params = [
      'batch_code' => $bcode,
      'paid' => 1,
      'paid_by' => auth()->user()->id,
      'paid_at' => \Carbon\Carbon::now(),
      'updated_at' => \Carbon\Carbon::now()
    ];
    $save = Contribution::whereIn('user_id', $data['users'])->where([['month', $data['month']],['year', $data['year']],['paid', 0]])->update($params);
    return response()->json([
      'success' => (bool) $save,
      'message' => $save ? 'User contribution paid' : 'User contribution not paid',
      'data' => ['result' => $save]
    ]);
  }

  public function fetchProcess()
  {
    $data = $this->request->all();
    //$data = array('month' => 7,'year' => 2019, 'users' => [3,22], 'batch_code' => '', 'mode' => 'process','user_type'=>'users');
    $str = $data['mode'] == 'process' ? 'processed' : 'approved';
    if(empty($data['users'])){
      return response()->json([
        'success' => false,
        'message' => 'No User contribution '.$str,
        'data' => ['result' => []]
      ]);
    }
    if($data['user_type'] == 'users'){
      $result = Contribution::where([['month', $data['month']], ['year',$data['year']], ['processed', 1]])->whereIn('user_id', $data['users'])->with(['user'])->get()->all();
    }else{
      $result = Contribution::where([['month', $data['month']], ['year',$data['year']], ['processed', 1]])
        ->whereIn('user_id', $data['users'])
        ->whereIn('user_id', DB::table('adoptees')->pluck('adoptee_id'))
        ->with(['user'])->get()->all();
    }

    return response()->json([
      'success' => (bool) $result,
      'message' => 'User contribution '.$str,
      'data' => ['result' => $result]
    ]);
  }

  public function fetchApprove()
  {
    $data = $this->request->all();
    if(empty($data['users'])){
      return response()->json([
        'success' => false,
        'message' => 'No User contribution approved',
        'data' => ['result' => []]
      ]);
    }
    //$data = array('month' => 7,'year' => 2019, 'users' => [3,22], 'batch_code' => '', 'mode' => 'process','user_type'=>'users');
    if($data['user_type'] == 'users'){
      $result = Contribution::where([['month', $data['month']], ['year',$data['year']], ['approved', 1]])->whereIn('user_id', $data['users'])->with(['user'])->get()->all();
    }else{
      $result = Contribution::where([['month', $data['month']], ['year',$data['year']], ['approved', 1]])
        ->whereIn('user_id', $data['users'])
        ->whereIn('user_id', DB::table('adoptees')->pluck('adoptee_id'))
        ->with(['user'])->get()->all();
    }
    return response()->json([
      'success' => (bool) $result,
      'message' => 'User contributions processed fetched',
      'data' => ['result' => $result]
    ]);
  }








}

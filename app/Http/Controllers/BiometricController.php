<?php

namespace App\Http\Controllers;

use Log;
use App\Utilities\Utility;
use App\Models\User;
use App\Models\UserBiometric;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;

class BiometricController extends Controller
{

  private $api_key = '';
  private $url = '';
  private $return_url = '';
  private $error_url = '';

  public function index()
  {
    $this->request->session()->put('backUrl', url()->previous());
    $this->api_key = env('BIOMETRIC_KEY');
    $this->url = env('BIOMETRIC_URL');
    $this->return_url = url(env('BIOMETRIC_RETURN_URL'));
    $this->error_url = url(env('BIOMETRIC_ERROR_URL'));
    $userData = array(
      'key' => $this->api_key,
      'UserID' => $this->request->session()->get('currentUserId'),
      'ReturnUrl' => $this->return_url,
      'ErrorUrl' =>  $this->error_url
    );
    $ch = curl_init($this->url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . $this->api_key));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $userData);
    $result = curl_exec($ch);
    curl_close($ch);
    echo $result;
  }

  public function save_bio($params){
    $validator = UserBiometric::creationValidator($params);
    if ($validator->fails()) {
      $v_err = Utility::parseValidatorErrors($validator->errors()->messages());
      return [
        'success' => false,
        'message' => 'Validation failed',
        'url' => "biometric/start",
        'data' => $v_err
      ];
    }

      $this->request->session()->forget(['currentUserVerificationNo', 'currentUserId', 'currentUserBiometricStatus']);
      $is_exists = false;
      $success = "User biometrics successful";
      $u = UserBiometric::where('user_id', $params['user_id'])->first();
      if ($u && $u->count() >= 1) {
        $is_exists = true;
        UserBiometric::find($u->id)->fill($params)->save();
      }else{
        UserBiometric::create($params);
      }
      $user = User::find($params['user_id']);
      $user->load('userBiometric');

    $getImage = false;

    try {
      $getImage = Utility::getConvertImage(array(
        'file_path' => $params['data_url'],
        'width' => 300,
        'height' => 300,
        'format' => 'jpg',
        'save_path' => public_path('images/biometrics/'.$user->verification_no.'.jpg'),
        'quality' => 60));
    } catch(\Exception $e) {
      Log::info("Error getting image {$e->getTraceAsString()}");
    }

    if($getImage){
      return [
        'success' => true,
        'message' => !$is_exists ? $success : "This biometric exists for user:: ".$user->last_name. ' '.$user->first_name. '( ' . $user->verification_no . ' ), biometric updated',
        'url' => $this->request->session()->get('backUrl'),
        'data' => array(
          'user' => $user,
          'img_url' => Utility::getStoredImage($user->verification_no,'BIOMETRIC_IMAGE_DIR'),
          'item' => !$is_exists ? 'completed' : 'exists',
          'class_id' => $params['class_id'],
          'token_id' => $params['token_id'],
          'data_url' => $params['data_url'],
          'user_id' => $params['user_id'],
          'verification_no' => $params['verification_no'],
          'biometric_status' => $params['biometric_status']
        )
      ];
    } else {
      $error = 'Could not get image for :: '.$params['verification_no'];
      Log::info($error);
      return [
        'success' => false,
        'message' => 'Could not get image',
        'url' => "biometric/start",
        'data' => $error
      ];
    }
  }

  public function success(){
    $current_user_id = $this->request->session()->get('currentUserId');
    $current_vno = $this->request->session()->get('currentUserVerificationNo');
    $userStr = "(current user: $current_user_id, vno: $current_vno)";

    // arbitrary
    $this->api_key = 'RhuNkU266WTPyymNS2GApbSWO';
    $current_vno = 'USER-11112223';
    $current_user_id = 2;
    $this->request->session()->put('backUrl', 'hcps');

    $data = [
      'class_id' => $_GET['ImageID'],
      'token_id' => $this->api_key,
      'data_url' => $_GET['DataURL'],
      'user_id' => $_GET['UserID'],
      'verification_no' => $current_vno,
      'biometric_status' => 1
    ];

    // remove session data
    //$this->request->session()->forget(['currentUserVerificationNo', 'currentUserId', 'currentUserBiometricStatus']);

    if ($_GET['status'] == 200) { // New enrolment
      return view('biometrics.list', $this->save_bio($data));
    } else { // Duplicate
      if (((int) $_GET['UserID']) == $current_user_id) { // ...for this account
        // log duplicate, route user to see image and choose to confirm or reject
        $error = "Capture is a duplicate for $userStr";
        Log::info('Biometric duplicate detected:: '.$error);
        $user = User::find($_GET['UserID']);
        $user->load('userBiometric');
        return view('biometrics.list', [
          'success' => true,
          'message' => $error,
          'url' => "",
          'data' => array(
            'user' => $user,
            'img_url' => Utility::getStoredImage($user->verification_no,'BIOMETRIC_IMAGE_DIR'),
            'item' => 'duplicate',
            'class_id' => $_GET['ImageID'],
            'token_id' => $this->api_key,
            'data_url' => $_GET['DataURL'],
            'user_id' => $_GET['UserID'],
            'verification_no' => $user->verification_no,
          )
        ]);
      } else { // Enroll the previous account and delete this one
        $user = User::where('id', $_GET['UserID'])->first();
        $dupStr = "(previous user: {$_GET['UserID']}, vno: $user->verification_no)";
        // delete this current reg and log
        User::destroy($current_user_id);
        Log::info('Biometric duplicate deleted:: '."$userStr is a duplicate of $dupStr");
        // save the biometrics for the previous reg
        return view('biometrics.list', $this->save_bio($data));
      }
    }
  }

  public function error(){
    $error = $this->request->error ? urldecode($this->request->error) : '';
    Log::info('Biometric error occurred:: '."Received URL {$_SERVER['REQUEST_URI']}");
    echo "<h3>Biometric capture not successful .:: <span style='color: #b94630'>[ ".$error." ]</span></h3><p><a href='/biometric/start'>Go back</a></p>";
  }

  public function test()
  {
    Log::info('this is recorded');
  }

  public function userBiometricData($id = ''){
    if ($this->request->isMethod('post')) {
      $where = array(["user_id", $this->request->all()['user_id']]);
      $user_bio = UserBiometric::getUserBiometricData($where);
      if(($user_bio)){
        return response()->json([
          'success' => true,
          'message' => 'Record Fetched',
          'data' => $user_bio
        ], 200);
      }
      return response()->json([
        'success' => false,
        'message' => 'No Record Fetched',
        'data' => []
      ], 422);
    }
    if ($this->request->isMethod('get')) {
      $where = array(["user_id", $id]);
      $user_bio = UserBiometric::getUserBiometricData($where);
      if(($user_bio)){
        return response()->json([
          'success' => true,
          'message' => 'Record Fetched',
          'data' => $user_bio // access like data->class_id
        ], 200);
      }
      return response()->json([
        'success' => false,
        'message' => 'No Record Fetched',
        'data' => []
      ], 422);
    }

  }








}

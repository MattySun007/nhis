<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\Institution;
use Illuminate\Support\Facades\URL;
use Log;
use App\Utilities\Utility;
use App\Models\User;
use App\Models\UserBiometric;
use App\Models\HcpUser;
use App\Models\InstitutionUser;
use App\Models\TreatmentUser;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;

use App\Exports\ExportFileCollection;
use App\Exports\ExportFileArray;
use App\Exports\ExportFileQuery;
use App\Exports\ExportFileView;
use App\Exports\ExportFileMultipleSheet;
use App\Imports\ImportFile;
use Maatwebsite\Excel\Facades\Excel;

class BiometricController extends Controller
{

  private $api_key = '';
  private $url = '';
  private $return_url = '';
  private $error_url = '';

  public function identify()
  {
    $this->api_key = env('BIOMETRIC_IDENTIFY_KEY');
    $this->url = env('BIOMETRIC_IDENTIFY_URL');
    $this->return_url = url(env('BIOMETRIC_IDENTIFY_RETURN_URL'));
    $this->error_url = url(env('BIOMETRIC_IDENTIFY_ERROR_URL'));
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
        'url' => url(env('BIOMETRIC_IDENTIFY_START_URL')),
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
        'save_path' => public_path(env('BIOMETRIC_IMAGE_DIR').$user->verification_no.'.jpg'),
        'quality' => 60));
    } catch(\Exception $e) {
      Log::info("Error getting image {$e->getTraceAsString()}");
    }

    if($getImage){
      /**
       * send email
       */
      $params = array(
        'subject' => 'New '.env("APP_NAME").' User/Contributor created',
        'message_body' => "<table border='1' cellpadding=\"2\" cellspacing=\"2\"><thead> </thead><tbody>
                   <tr><th colspan='2'>Your account as a User/Contributor has just been successfully created, find below the details</th></tr> 
                   <tr><th>Name: </th><td>". $user->last_name.' '.$user->first_name."</td></tr> 
                   <tr><th>Verification No: </th><td>". $user->verification_no."</td></tr>
                   <tr><th>Phone: </th><td>". $user->phone."</td></tr>
                   <tr><th>Email: </th><td>". $user->email."</td></tr>
                   <tr><th>Date: </th><td>". $user->created_at."</td></tr></tbody></table>",
        'message_header' => 'User/Contributor Creation',
        'button_link' => '',
        'button_link_text' => '',
        'to' => $user->email,
        'cc' => array(
          ['email' => env("SUPPORT_EMAIL"), 'name' => env("SUPPORT_NAME").' Support']
        ),
        'bcc' => array(
          ['email' => env("SUPPORT_BCC"), 'name' => 'Support Bcc']
        ),
      );
      Utility::send_email($params);
      return [
        'success' => true,
        'message' => !$is_exists ? $success : "This biometric exists for user:: ".$user->last_name. ' '.$user->first_name. '( ' . $user->verification_no . ' )',
        'url' => $this->request->session()->has('backUrl') ? $this->request->session()->get('backUrl') : 'dashboard',
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
        'url' => url(env('BIOMETRIC_IDENTIFY_START_URL')),
        'data' => $error
      ];
    }
  }

  public function successIdentify(){
    $current_user_id = $this->request->session()->get('currentUserId');
    $current_vno = $this->request->session()->get('currentUserVerificationNo');
    $userStr = "(current user: $current_user_id, vno: $current_vno)";

    $data = [
      'class_id' => $_GET['ImageID'],
      'token_id' => !empty($this->api_key) ? $this->api_key : env('BIOMETRIC_KEY'),
      'data_url' => $_GET['DataURL'],
      'user_id' => $_GET['UserID'],
      'verification_no' => $current_vno,
      'biometric_status' => 1
    ];

    if ($_GET['status'] == 200) { // New enrolment
      return view('biometrics.list', $this->save_bio($data));
    } else { // Duplicate
      if (UserBiometric::where('class_id', $_GET['ImageID'])->first()) { // ...for this account
        // log duplicate, route user to see image
        $error = "Capture is a duplicate for $userStr";
        Log::info('Biometric duplicate detected:: '.$error);
        $user = User::find($_GET['UserID']);
        $user->load('userBiometric');
        User::destroy($current_user_id);
        return view('biometrics.list', [
          'success' => true,
          'message' => $error,
          'url' => $this->request->session()->has('backUrl') ? $this->request->session()->get('backUrl') : 'dashboard',
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
        $vno = $user ? $user->verification_no : '';
        $dupStr = "(previous user: {$_GET['UserID']}, vno: $vno)";
        // delete this current reg and log
        User::destroy($current_user_id);
        Log::info('Biometric duplicate deleted:: '."$userStr is a duplicate of $dupStr");
        // save the biometrics for the previous reg
        return view('biometrics.list', $this->save_bio($data));
      }
    }
  }

  public function errorIdentify(){
    $error = $this->request->error ? urldecode($this->request->error) : '';
    $start = url(env('BIOMETRIC_IDENTIFY_START_URL'));//dd("<h3>Biometric capture not successful .:: <span style='color: #b94630'>[ ".$error." ]</span></h3><p><a href='.$start.'>Go back</a></p>");
    Log::info('Biometric error occurred:: '."Received URL {$_SERVER['REQUEST_URI']}");
    echo "<h3>Biometric capture not successful .::. <span style='color: #b94630'>[ ".$error." ]</span></h3><p><a href=".$start.">Go back</a></p>";
  }

  public function verify($payload)
  {
    $this->request->session()->put('bioVerifyData', $payload);
    $payload = json_decode(base64_decode($payload));
    $bio = UserBiometric::where('user_id', $payload->user_id)->select('class_id','user_id')->first();
    if($bio){
      $this->api_key = env('BIOMETRIC_VERIFY_KEY');
      $this->url = env('BIOMETRIC_VERIFY_URL');
      $this->return_url = url(env('BIOMETRIC_VERIFY_RETURN_URL'));
      $this->error_url = url(env('BIOMETRIC_VERIFY_ERROR_URL'));
      $userData = array(
        'key' => $this->api_key,
        'UserID' => $bio->user_id,
        'callID' => $bio->class_id,
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
    }else{
      echo "<h3>User do not exist or inactive (biometric data not found) !</h3><p><a href=".$payload->last_url.">Go back</a></p>";
    }
  }

  public function successVerify(){
    $message = '';
    $payload = $this->request->session()->has('bioVerifyData') ? $this->request->session()->get('bioVerifyData') : [];
    $payload = json_decode(base64_decode($payload));
    if ($_GET['status'] == 201) { // user verified
      if($payload->action == 'add_hcp_user'){
        $user = User::where('id',$payload->user_id)->first();
        $user->assignHcpUserPermissions();
        if(HcpUser::create(['user_id' => $payload->user_id, 'hcp_id' => $payload->hcp_id])){
          $message = "HCP User successfully created";
        }
      }elseif($payload->action == 'add_institution_user'){
        $user = User::where('id',$payload->user_id)->first();
        $user->assignInstitutionUserPermissions();
        if(InstitutionUser::create(['user_id' => $payload->user_id, 'institution_id' => $payload->institution_id])){
          $message = "Institution User successfully created";
        }
      }elseif($payload->action == 'hcp_verify_user'){
        $b = Utility::generateVerificationCode(TreatmentUser::class);
        if(TreatmentUser::create(['user_id' => $payload->user_id, 'hcp_id' => $payload->hcp_id,'verification_code' => $b, 'verified_by' => auth()->user()->id])){
          $message = "User successfully verified and ready for treatment";
        }
      }
      echo "<h3>Biometric verified .::. <span style='color: green'>[ ... this user biometric matches, thus $message ]</span></h3><p><a href=".$payload->last_url.">Go back</a></p>";
    } elseif($_GET['status'] == 404) { // user not verified
      echo "<h3>Biometric not verified .::. <span style='color: #b94630'>[ ... this user biometric do not match ]</span></h3><p><a href=".$payload->last_url.">Go back</a></p>";
    }
  }

  public function errorVerify(){
    $error = $this->request->error ? urldecode($this->request->error) : '';
    $payload = $this->request->session()->has('bioVerifyData') ? $this->request->session()->get('bioVerifyData') : '';
    $start = url(env('BIOMETRIC_VERIFY_START_URL')).'/'.$payload;
    Log::info('Verification error occurred:: '."Received URL {$_SERVER['REQUEST_URI']}");
    echo "<h3>Verification not successful .:: <span style='color: #b94630'>[ ".$error." ]</span></h3><p><a href=".$start.">Go back</a></p>";
  }


  public function test()
  {
    $data_for_nuban_check_post = array(
      'vendor_code' => '555600987',//'555600987',8875365312
      'account_number' => '3018383756',
      'bank_code' => '011',
    );
    $request_headers = array();
    $request_headers[] = 'apikey: x0jPnHLb2A9E6nNIGkzl';
    $request_headers[] = 'Content-Type: application/json';
    $request_headers[] = 'Accept: application/json';
    $request_headers[] = 'User-Agent: Apache-HttpClient/4.1.1 ';
    $request_headers[] = 'Connection: Keep-Alive';


    $result = Utility::make_post_request($data_for_nuban_check_post,'https://api.appmartgroup.com/v2/accounts/nubanCheckPost', $request_headers);
    $res = "Error: request not successful!";
    if($result) {
      $result = json_decode(($result));
      if(isset($result->Message)){
        $res = $result->Message;
      }elseif(isset($result->status) && $result->status == '00'){
        $res = $result->accountName;
      }
    }

    dd($res);

    /*$params = array(
      'subject' => 'Institution Creation',
      'message_body' => "<table border='1' cellpadding=\"2\" cellspacing=\"2\"><thead> </thead><tbody>
                   <tr><th colspan='2'>Your institution/company has just been successfully created, Please find below the details</th></tr> 
                   <tr><th>Name: </th><td>erwerwer</td></tr> 
                   <tr><th>Code: </th><td>323141234</td></tr>
                  
                   <tr><th>Date: </th><td>33434134</td></tr></tbody></table>",
      'message_header' => 'New '.env("APP_NAME").' Institution created',
      'button_link' => '',
      'button_link_text' => '',
      'to' => 'pcollinso@yahoo.com',
      'cc' => array(
        ['email' => env("SUPPORT_EMAIL"), 'name' => env("SUPPORT_NAME").' Support']
      ),
      'bcc' => array(
        ['email' => env("SUPPORT_BCC"), 'name' => 'Support Bcc']
      ),
    );
    Utility::send_email($params);*/
    dd();
//dd(Utility::getStoredImage('USER-68062329','BIOMETRIC_IMAGE_DIR'));
    //Log::info('this is recorded');

    /*$users = User::where('id',1)->select('id','email','phone','verification_no')->get();
    Excel::store(new ExportFileCollection(['collection' => $users, 'headings' => ['id','email','phone','verification_no']]), 'public/exports/test_collection.xlsx');*/

    //Excel::store(new ExportFileArray(['headings' => ['a','b','c'],'array' => [[1, 2, 3], [1, 2, 3]]]), 'public/exports/test_array.xlsx');

    /*$query = User::query()->whereEmail('agency@nhis.com')->select('id','email','phone','verification_no');
    Excel::store(new ExportFileQuery(['query' => $query, 'headings' => ['id','email','phone','verification_no']]), 'public/exports/test_query.xlsx');*/

    /*$users = User::select('id','email','phone','verification_no')->get();
    Excel::store(new ExportFileView(['view' => 'exports.exports', 'data' => $users]), 'public/exports/test_view.xlsx');*/

    /*$users = User::where('id',1)->select('id','email','phone','verification_no')->get();
    $institutions = Institution::where('id',1)->select('id','email','phone','name')->get();
    $cont = Contribution::where('id',1)->select('id','amount','created_at','approved')->get();
    $user1 = User::where('id',3)->select('id','email','phone','verification_no')->get();
    Excel::store(new ExportFileMultipleSheet(['data' =>
      [
        ['collection' => $users, 'headings' => ['id','email','phone','verification_no']],
        ['collection' => $institutions, 'headings' => ['id','email','phone','name']],
        ['collection' => $user1, 'headings' => ['id','email','phone','verification_no']],
        ['collection' => $cont, 'headings' => ['id','amount','created_at','approved']]
      ],
      'dataStructure' => 'collection'
    ]), 'public/exports/test_multiple.xlsx');*/ // storage_path

    Excel::import(new ImportFile(['model' => User::class, 'columns' => ['id','verification_no','first_name','middle_name','last_name','email','phone','password']]), storage_path('app/public/imports/users.xlsx')); // any path

    //$array = Excel::toArray(new ImportFile(['model' => User::class, 'columns' => ['id','verification_no','first_name','middle_name','last_name','email','phone','password']]), storage_path('app/public/imports/users.xlsx'));dd($array);

    /*dd(1);
    $params = array(
      'subject' => 'Contribution Payment Notification',
      'message_body' => 'Attached is a copy of the contribution payment schedule for this institution',
      'message_header' => 'Contribution Payment File',
      'title' => 'Contribution Payment Notification',
      'button_link' => '',
      'button_link_text' => '',
      'lower_text' => 'For any complaints, kindly reach us using the information below',
      'to' => @yahoo.com',
      'from_n'pcollinsoame' => 'NHIS',
      'from_email' => 'noreply@nhis.com',
      'reply_to' => '',
      'reply_to_email' => '',
      'reply_to_name' => '',
      'cc' => array(
        ['email' => 'pcollinso@yahoo.com', 'name' => 'Paulcollins Obi']
      ),
      'bcc' => array(),
      'attachment' => array(
        ['path' => public_path(('/images/attachments/test.xls')), 'display_name' => 'File attached']
      )
    );
    Utility::send_email($params);*/
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

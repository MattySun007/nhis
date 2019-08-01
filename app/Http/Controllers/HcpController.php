<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Log;
use App\Utilities\Utility;
use App\Models\Hcp;
use App\Models\Country;
use App\Models\HcpType;
use App\Models\State;
use App\Models\Lga;

class HcpController extends Controller
{

  public function index()
  {
    return view('hcps.list', [
      'pageTitle' => 'HCPs',
      'hcps' => auth()->user()->user_type == 'Hcp User' ? Hcp::whereIn('id', auth()->user()->user_hcps)->with(['town', 'country', 'state', 'lga', 'hcp_type', 'bank'])->get()->all() : Hcp::with(['town', 'country', 'state', 'lga', 'hcp_type', 'bank'])->get()->all(),
      'countries' => Country::all(),
      'hcp_types' => HcpType::all(),
      'states' => State::all(),
      'lgas' => Lga::all(),
      'banks' => Bank::all()
    ]);
  }

  public function create()
  {
    $data = $this->request->all();
    $validator = Hcp::creationValidator($data);
    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => 'Validation failed',
        'data' => $validator->errors()->messages()
      ], 422);
    }
    $hcp = new Hcp($data);
    $hcp->save();
    $hcp->loadMissing(['town', 'country', 'state', 'lga', 'hcp_type', 'bank']);
    /**
     * send email
     */
    $params = array(
      'subject' => 'New '.env("APP_NAME").' HCP created',
      'message_body' => "<table border='1' cellpadding=\"2\" cellspacing=\"2\"><thead> </thead><tbody>
                   <tr><th colspan='2'>Your HCP has just been successfully created, Please find below the details</th></tr> 
                   <tr><th>Name: </th><td>". $hcp->name."</td></tr> 
                   <tr><th>Code: </th><td>". $hcp->code."</td></tr>
                   <tr><th>Phone: </th><td>". $hcp->phone."</td></tr>
                   <tr><th>Email: </th><td>". $hcp->email."</td></tr>
                   <tr><th>Hcp type: </th><td>". $hcp->hcp_type->hcp_type."</td></tr>
                   <tr><th>Bank: </th><td>". $hcp->bank->bank_name."</td></tr>
                   <tr><th>Account: </th><td>". $hcp->account_number."</td></tr>
                   <tr><th>Country: </th><td>". $hcp->country->country."</td></tr>
                   <tr><th>State: </th><td>". $hcp->state->name."</td></tr>
                   <tr><th>LGA: </th><td>". $hcp->lga->name."</td></tr>
                   <tr><th>Date: </th><td>". $hcp->created_at."</td></tr></tbody></table>",
      'message_header' => 'HCP Creation',
      'button_link' => '',
      'button_link_text' => '',
      'to' => $hcp->email,
      'cc' => array(
        ['email' => env("SUPPORT_EMAIL"), 'name' => env("SUPPORT_NAME").' Support']
      ),
      'bcc' => array(
        ['email' => env("SUPPORT_BCC"), 'name' => 'Support Bcc']
      ),
    );
    Utility::send_email($params);
    return response()->json([
      'success' => true,
      'message' => 'Hcp created',
      'data' => $hcp
    ]);
  }

  public function update($id)
  {
    $data = $this->request->all();
    $validator = Hcp::updateValidator($data, $id);
    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => 'Validation failed',
        'data' => $validator->errors()->messages()
      ], 422);
    }
    $hcp = Hcp::find($id);
    $hcp->fill($data);
    $hcp->save();
    $hcp->loadMissing(['town', 'country', 'state', 'lga', 'hcp_type', 'bank']);
    return response()->json([
      'success' => true,
      'message' => 'Hcp updated',
      'data' => $hcp
    ]);
  }

  public function generateHcpCode()
  {
    $seed = "HCP-";
    a:
    $code = Utility::generateRandomNumber(6);
    $code = $seed.$code;
    if(Hcp::where('code', $code)->count() <= 0){
      return $code;
    }else{
      goto a;
    }
  }

  public function getHcpCode()
  {
    return response()->json([
      'success' => true,
      'message' => 'Hcp code generated',
      'data' => array('code' => $this->generateHcpCode())
    ]);
  }





}

<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Treatment;
use Illuminate\Support\Facades\DB;

class ClaimController extends Controller
{

  public function index()
  {
    $state = $institution = $treatment = array();$item = '';
    if(auth()->user()->user_type == 'Hcp User'){
      $item = 'state';
      $state = DB::table('treatments')
        ->join('hcps', 'treatments.hcp_id', '=', 'hcps.id')
        ->join('states', 'hcps.state_id', '=', 'states.id')
        ->select('states.name as state','hcps.state_id', DB::raw('count(*) as counta'),DB::raw('sum(treatments.bill) as suma'))
        ->where('treatments.paid', 0)
        ->whereIn('treatments.hcp_id', auth()->user()->user_hcps)
        ->groupBy('states.name')
        ->get();
    }
    if(auth()->user()->user_type == 'Agency User'){
      $item = 'state';
      $state = DB::table('treatments')
        ->join('hcps', 'treatments.hcp_id', '=', 'hcps.id')
        ->join('states', 'hcps.state_id', '=', 'states.id')
        ->select('states.name as state','hcps.state_id', DB::raw('count(*) as counta'),DB::raw('sum(treatments.bill) as suma'))
        ->where('treatments.paid', 0)
        ->groupBy('states.name')
        ->get();
    }
    if(auth()->user()->user_type == 'Institution User'){
      $item = 'institution';
      $institution = DB::table('treatments')
        ->join('users', 'treatments.user_id', '=', 'users.id')
        ->join('institution_user', 'treatments.user_id', '=', 'institution_user.user_id')
        ->join('institutions', 'institutions.id', '=', 'institution_user.institution_id')
        ->select('institutions.name as institution','institutions.id', 'institutions.code','institutions.id as institution_id', DB::raw('count(*) as counta'),DB::raw('sum(treatments.bill) as suma'))
        ->where('treatments.paid', 0)
        ->whereIn('institution_user.institution_id', auth()->user()->user_institutions)
        ->groupBy('institutions.name')
        ->get();
    }
    return view('hcps.list-claims', [
      'pageTitle' => 'Unpaid Claims',
      'itemTitle' => $item,
      'paid' => 0,
      'states' => $state,
      'institutions' => $institution,
      'treatments' => $treatment
    ]);
  }

  public function paidClaims()
  {
    $state = $institution = $treatment = array();$item = '';
    if(auth()->user()->user_type == 'Hcp User'){
      $item = 'state';
      $state = DB::table('treatments')
        ->join('hcps', 'treatments.hcp_id', '=', 'hcps.id')
        ->join('states', 'hcps.state_id', '=', 'states.id')
        ->select('states.name as state','hcps.state_id', DB::raw('count(*) as counta'),DB::raw('sum(treatments.bill) as suma'))
        ->where('treatments.paid', 1)
        ->whereIn('treatments.hcp_id', auth()->user()->user_hcps)
        ->groupBy('states.name')
        ->get();
    }
    if(auth()->user()->user_type == 'Agency User'){
      $item = 'state';
      $state = DB::table('treatments')
        ->join('hcps', 'treatments.hcp_id', '=', 'hcps.id')
        ->join('states', 'hcps.state_id', '=', 'states.id')
        ->select('states.name as state','hcps.state_id', DB::raw('count(*) as counta'),DB::raw('sum(treatments.bill) as suma'))
        ->where('treatments.paid', 1)
        ->groupBy('states.name')
        ->get();
    }
    if(auth()->user()->user_type == 'Institution User'){
      $item = 'institution';
      $institution = DB::table('treatments')
        ->join('users', 'treatments.user_id', '=', 'users.id')
        ->join('institution_user', 'treatments.user_id', '=', 'institution_user.user_id')
        ->join('institutions', 'institutions.id', '=', 'institution_user.institution_id')
        ->select('institutions.name as institution','institutions.id', 'institutions.code','institutions.id as institution_id', DB::raw('count(*) as counta'),DB::raw('sum(treatments.bill) as suma'))
        ->where('treatments.paid', 1)
        ->whereIn('institution_user.institution_id', auth()->user()->user_institutions)
        ->groupBy('institutions.name')
        ->get();
    }
    return view('hcps.list-claims', [
      'pageTitle' => 'Paid Claims',
      'itemTitle' => $item,
      'paid' => 1,
      'states' => $state,
      'institutions' => $institution,
      'treatments' => $treatment
    ]);
  }

  public function stateHcpsClaims($id,$paid=null)
  {
    $hcp = array();
    if(is_null($paid)){
      if(auth()->user()->user_type == 'Hcp User'){
        $hcp = DB::table('treatments')
          ->join('hcps', 'treatments.hcp_id', '=', 'hcps.id')
          ->join('states', 'hcps.state_id', '=', 'states.id')
          ->select('hcps.name as hcp','treatments.hcp_id','hcps.code as hcp_code','states.name as state', DB::raw('count(*) as counta'),DB::raw('sum(treatments.bill) as suma'))
          ->whereIn('treatments.hcp_id', auth()->user()->user_hcps)
          ->where('hcps.state_id', $id)
          ->groupBy('hcp')
          ->get();
      }
      if(auth()->user()->user_type == 'Agency User'){
        $hcp = DB::table('treatments')
          ->join('hcps', 'treatments.hcp_id', '=', 'hcps.id')
          ->join('states', 'hcps.state_id', '=', 'states.id')
          ->select('hcps.name as hcp','treatments.hcp_id','hcps.code as hcp_code','states.name as state', DB::raw('count(*) as counta'),DB::raw('sum(treatments.bill) as suma'))
          ->where('hcps.state_id', $id)
          ->groupBy('hcp')
          ->get();
      }
    }else{
      if(auth()->user()->user_type == 'Hcp User'){
        $hcp = DB::table('treatments')
          ->join('hcps', 'treatments.hcp_id', '=', 'hcps.id')
          ->join('states', 'hcps.state_id', '=', 'states.id')
          ->select('hcps.name as hcp','treatments.hcp_id','hcps.code as hcp_code','states.name as state', DB::raw('count(*) as counta'),DB::raw('sum(treatments.bill) as suma'))
          ->where('treatments.paid', $paid)
          ->whereIn('treatments.hcp_id', auth()->user()->user_hcps)
          ->where('hcps.state_id', $id)
          ->groupBy('hcp')
          ->get();
      }
      if(auth()->user()->user_type == 'Agency User'){
        $hcp = DB::table('treatments')
          ->join('hcps', 'treatments.hcp_id', '=', 'hcps.id')
          ->join('states', 'hcps.state_id', '=', 'states.id')
          ->select('hcps.name as hcp','treatments.hcp_id','hcps.code as hcp_code','states.name as state', DB::raw('count(*) as counta'),DB::raw('sum(treatments.bill) as suma'))
          ->where('treatments.paid', $paid)
          ->where('hcps.state_id', $id)
          ->groupBy('hcp')
          ->get();
      }
    }

    return $hcp;
  }


  public function stateHcpsClaimsUnpaid($id)
  {
    return response()->json([
      'pageTitle' => 'Claims',
      'hcps' => $this->stateHcpsClaims($id,0)
    ]);
  }


  public function stateHcpsClaimsPaid($id)
  {
    return response()->json([
      'pageTitle' => 'Claims',
      'hcps' => $this->stateHcpsClaims($id,1)
    ]);
  }

  public function hcpTreatmentsClaims($id, $paid = null)
  {
    /*$treatments = array();
    if(auth()->user()->user_type == 'Hcp User'){
      $treatments = Treatment::getTreatments(array('hcp_id' => auth()->user()->user_hcps, 'paid' => $paid));
    }
    if(auth()->user()->user_type == 'Agency User'){
      $treatments = Treatment::getTreatments(array('hcp_id' => $id, 'paid' => $paid));
    }
    return $treatments;*/
    return Treatment::getTreatments(array('hcp_id' => $id, 'paid' => $paid));
  }

  public function institutionTreatmentsClaims($id, $paid = null)
  {
    /*$treatments = array();
    if(auth()->user()->user_type == 'Institution User'){
      $treatments = Treatment::getTreatments(array('institution_id' => auth()->user()->user_institutions, 'paid' => $paid));
    }
    if(auth()->user()->user_type == 'Agency User'){
      $treatments = Treatment::getTreatments(array('institution_id' => $id, 'paid' => $paid));
    }
    return $treatments;*/
    return Treatment::getTreatments(array('institution_id' => $id, 'paid' => $paid));
  }

  public function getMyTreatmentsClaims()
  {
    return Treatment::getTreatments(array('user_id' => auth()->user()->id));
  }

  public function myTreatmentsClaims()
  {
    $state = $institution = array();$item = 'treatment';
    return view('hcps.list-claims', [
      'pageTitle' => 'Claims',
      'itemTitle' => $item,
      'paid' => null,
      'states' => $state,
      'institutions' => $institution,
      'treatments' => $this->getMyTreatmentsClaims()
    ]);
  }

  public function myTreatmentsClaimsService()
  {
    return response()->json([
      'pageTitle' => 'Claims',
      'treatments' => $this->getMyTreatmentsClaims()
    ]);
  }

  public function hcpTreatmentsClaimsUnpaid($id)
  {
    return response()->json([
      'pageTitle' => 'Claims',
      'treatments' => $this->hcpTreatmentsClaims($id,0)
    ]);
  }

  public function hcpTreatmentsClaimsPaid($id)
  {
    return response()->json([
      'pageTitle' => 'Claims',
      'treatments' => $this->hcpTreatmentsClaims($id,1)
    ]);
  }

  public function institutionTreatmentsClaimsUnpaid($id)
  {
    return response()->json([
      'pageTitle' => 'Claims',
      'treatments' => $this->institutionTreatmentsClaims($id,0)
    ]);
  }

  public function institutionTreatmentsClaimsPaid($id)
  {
    return response()->json([
      'pageTitle' => 'Claims',
      'treatments' => $this->institutionTreatmentsClaims($id,1)
    ]);
  }


  public function deleteClaim($id)
  {
    $t = Claim::find($id);
    if (empty($t))
    {
      return response()->json([
        'success' => false,
        'message' => 'Claim not found',
      ], 400);
    }
    return response()->json([
      'success' => true,
      'message' => 'Claim deleted',
      'data' => []
    ]);
  }







}

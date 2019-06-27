<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use App\Utilities\Utility;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Hcp;
use App\Models\Adoptee;
use App\Models\Institution;
use App\Models\InstitutionUser;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     *user_types:
        Agency User
        Individual Contributor
        Institution User
        Hcp User
        Adoptee
     */

    public function index()
    {
      /*dd(DB::table('users')
        ->join('institution_user', 'users.id', '=', 'institution_user.user_id')
        ->join('institutions', 'institutions.id', '=', 'institution_user.institution_id')
        ->select(DB::raw('case when sum(users.contribution_amount) > 0 then sum(users.contribution_amount) else 0 end as suma'))
        ->whereIn('institution_user.institution_id', auth()->user()->user_institutions)
        ->get()[0]->suma);*/

      /*dd(DB::table('treatments')
        ->join('users', 'treatments.user_id', '=', 'users.id')
        ->join('institution_user', 'treatments.user_id', '=', 'institution_user.user_id')
        ->join('institutions', 'institutions.id', '=', 'institution_user.institution_id')
        ->where('treatments.paid', 0)
        ->whereIn('institution_user.institution_id', auth()->user()->user_institutions)
        ->get()->count());*/
      //dd(Adoptee::where('user_id', auth()->user()->id)->count());

      if(auth()->user()->user_type == 'Agency User'){
        return view('dashboard.agency', [
          'data' => array(
            'hcp_count' => Hcp::all()->count(),
            'institution_count' => Institution::all()->count(),
            'user_count' => User::all()->count(),
            'individual_count' => User::all()->count(),
            'adoptee_count' => Adoptee::all()->count(),
            'item_title' => ' ... Agency user dashboard'
          )
        ]);
      }
      if(auth()->user()->user_type == 'Hcp User'){
        return view('dashboard.hcp', [
          'data' => array(
            'hcp_count' => count(auth()->user()->user_hcps),
            //'institution_count' => count(auth()->user()->user_institutions),
            'paid_claims_count' => DB::table('treatments')
              ->join('hcps', 'treatments.hcp_id', '=', 'hcps.id')
              ->where('treatments.paid', 1)
              ->whereIn('treatments.hcp_id', auth()->user()->user_hcps)
              ->get()->count(),
            'unpaid_claims_count' => DB::table('treatments')
              ->join('hcps', 'treatments.hcp_id', '=', 'hcps.id')
              ->where('treatments.paid', 0)
              ->whereIn('treatments.hcp_id', auth()->user()->user_hcps)
              ->get()->count(),
            'adoptee_count' => Adoptee::where('user_id', auth()->user()->id)->count(),
            'item_title' => ' ... Hcp user dashboard'
          )
        ]);
      }
      if(auth()->user()->user_type == 'Institution User'){
        return view('dashboard.institution', [
          'data' => array(
            'institution_count' => count(auth()->user()->user_institutions),
            'total_paid_claims_count' => DB::table('treatments')
              ->join('users', 'treatments.user_id', '=', 'users.id')
              ->join('institution_user', 'treatments.user_id', '=', 'institution_user.user_id')
              ->join('institutions', 'institutions.id', '=', 'institution_user.institution_id')
              ->where('treatments.paid', 1)
              ->whereIn('institution_user.institution_id', auth()->user()->user_institutions)
              ->get()->count(),
            'total_unpaid_claims_count' => DB::table('treatments')
              ->join('users', 'treatments.user_id', '=', 'users.id')
              ->join('institution_user', 'treatments.user_id', '=', 'institution_user.user_id')
              ->join('institutions', 'institutions.id', '=', 'institution_user.institution_id')
              ->where('treatments.paid', 0)
              ->whereIn('institution_user.institution_id', auth()->user()->user_institutions)
              ->get()->count(),
            'personal_paid_claims_count' => DB::table('treatments')
              ->join('users', 'treatments.user_id', '=', 'users.id')
              ->join('institution_user', 'treatments.user_id', '=', 'institution_user.user_id')
              ->join('institutions', 'institutions.id', '=', 'institution_user.institution_id')
              ->where('treatments.paid', 1)
              ->where('treatments.user_id',  auth()->user()->id)
              ->whereIn('institution_user.institution_id', auth()->user()->user_institutions)
              ->get()->count(),
            'personal_unpaid_claims_count' => DB::table('treatments')
              ->join('users', 'treatments.user_id', '=', 'users.id')
              ->join('institution_user', 'treatments.user_id', '=', 'institution_user.user_id')
              ->join('institutions', 'institutions.id', '=', 'institution_user.institution_id')
              ->where('treatments.paid', 0)
              ->where('treatments.user_id',  auth()->user()->id)
              ->whereIn('institution_user.institution_id', auth()->user()->user_institutions)
              ->get()->count(),
            'contribution_sum' => DB::table('users')
              ->join('institution_user', 'users.id', '=', 'institution_user.user_id')
              ->join('institutions', 'institutions.id', '=', 'institution_user.institution_id')
              ->select(DB::raw('case when sum(users.contribution_amount) > 0 then sum(users.contribution_amount) else 0 end as suma'))
              ->whereIn('institution_user.institution_id', auth()->user()->user_institutions)
              ->get()[0]->suma,
            'adoptee_count' => Adoptee::where('user_id', auth()->user()->id)->count(),
            'item_title' => ' ... Institution user dashboard',
            'unpaid_claims_text' => ' ... institution(s) statistics',
            'paid_claims_text' => ' ... institution(s) statistics',
            'personal_unpaid_claims_text' => ' ... Individual statistics',
            'personal_paid_claims_text' => ' ... Individual statistics'
          )
        ]);
      }
      if(auth()->user()->user_type == 'Institution User'){
        return view('dashboard.agency', [
          'data' => array(
            'hcp_count' => Hcp::all()->count(),
            'institution_count' => Institution::all()->count(),
            'user_count' => User::all()->count(),
            'individual_count' => User::all()->count(),
            'adoptee_count' => Adoptee::all()->count(),
            'item_title' => ' ... Institution user dashboard'
          )
        ]);
      }
      if(auth()->user()->user_type == 'Agency User'){
        return view('dashboard.agency', [
          'data' => array(
            'hcp_count' => Hcp::all()->count(),
            'institution_count' => Institution::all()->count(),
            'user_count' => User::all()->count(),
            'individual_count' => User::all()->count(),
            'adoptee_count' => Adoptee::all()->count(),
            'item_title' => ' ... Agency user dashboard'
          )
        ]);
      }
      return view('welcome');

    }







}

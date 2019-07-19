<template>
  <Page>
    <page-title title="Manage Contributions"/>
    <div class="row" v-if="action === 'select_date'">
      <div class="col-sm-12">
        <div class="panel panel-inverse">
          <div class="panel-heading">
            <h4 class="panel-title">Contributions - {{ action_text }}</h4>
          </div>
          <div class="panel-body">
            <div v-if="errors.length" class="alert alert-warning" v-html="errors"></div>
            <form>
              <div class="form-group row">
                <label class="control-label col-md-3 col-sm-4">Year *</label>
                <div class="col-md-9 col-sm-9">
                  <v-select placeholder="Select year" label="name" :options="years" index="value" v-model="year"/>
                </div>
              </div>
              <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3">Month *</label>
                <div class="col-md-9 col-sm-9">
                  <v-select placeholder="Select month" label="name" :options="months" index="value" v-model="month"/>
                </div>
              </div>
              <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3"></label>
                <div class="col-md-3 col-sm-3" >
                  <button :disabled="!formOk || !canRead" @click.stop.prevent="viewContributionHistory()" type="button" class="btn btn-secondary">Contribution History</button>
                </div>
                <div class="col-md-3 col-sm-3">
                  <button :disabled="!formOk || !canProcess" @click.stop.prevent="processContribution()" type="button" class="btn btn-secondary">Process Contribution</button>
                </div>
                <div class="col-md-3 col-sm-3" >
                  <button :disabled="!formOk || !canApprove" @click.stop.prevent="approveContribution()" type="button" class="btn btn-secondary">Approve Contribution</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div v-else-if="action === 'history'">

      <div class="row">
        <div class="col-sm-12">
          <div class="panel panel-default">
            <div class="panel-heading">
              <button @click.stop.prevent="backToDate()" type="button" class="btn btn-secondary m-r-2">Back to date</button>
            </div>
          </div>
        </div>
      </div>
      <!-- begin display user contribution history -->
      <div class="row">
        <div class="col-sm-12">
          <div class="panel panel-inverse">
            <div class="panel-heading">
              <h4 class="panel-title">Contribution History - {{ action_text }}</h4>
            </div>
            <div class="panel-body">
              <div class="table-responsive">
                <table class="table table-striped m-b-0">
                  <thead>
                  <tr>
                    <th>Name</th>
                    <th>Verification_no</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Amount</th>
                    <th>Batch_code</th>
                    <th>Year</th>
                    <th>Month</th>
                    <th>Date</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-if="!localHistory.length">
                    <td colspan="8" class="text-center">No record</td>
                  </tr>
                  <tr v-for="i in localHistory" :key="i.id">
                    <td>{{ i.user.last_name + ' ' + i.user.first_name + ' ' + i.user.middle_name }}</td>
                    <td>{{ i.user.verification_no }}</td>
                    <td>{{ i.user.phone }}</td>
                    <td>{{ i.user.email }}</td>
                    <td>{{ i.amount }}</td>
                    <td>{{ i.batch_code }}</td>
                    <td>{{ i.year }}</td>
                    <td>{{ i.month }}</td>
                    <td>{{ i.created_at }}</td>
                  </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- begin display adoptee contribution history -->
      <div class="row">
        <div class="col-sm-12">
          <div class="panel panel-inverse">
            <div class="panel-heading">
              <h4 class="panel-title">Adoptee Contribution History - {{ action_text }}</h4>
            </div>
            <div class="panel-body">
              <div class="table-responsive">
                <table class="table table-striped m-b-0">
                  <thead>
                  <tr>
                    <th>Name</th>
                    <th>Verification_no</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Amount</th>
                    <th>Batch_code</th>
                    <th>Year</th>
                    <th>Month</th>
                    <th>Date</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-if="!localAdoptees.length">
                    <td colspan="8" class="text-center">No record</td>
                  </tr>
                  <tr v-for="i in localAdoptees" :key="i.id">
                    <td>{{ i.user.last_name + ' ' + i.user.first_name + ' ' + i.user.middle_name }}</td>
                    <td>{{ i.user.verification_no }}</td>
                    <td>{{ i.user.phone }}</td>
                    <td>{{ i.user.email }}</td>
                    <td>{{ i.amount }}</td>
                    <td>{{ i.batch_code }}</td>
                    <td>{{ i.year }}</td>
                    <td>{{ i.month }}</td>
                    <td>{{ i.created_at }}</td>
                  </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-else-if="action === 'process'">

      <div class="row">
        <div class="col-sm-12">
          <div class="panel panel-default">
            <div class="panel-heading">
              <button @click.stop.prevent="backToDate()" type="button" class="btn btn-secondary m-r-2">Back to date</button>&nbsp;
              <button @click.stop.prevent="viewContributionHistory()" type="button" class="btn btn-secondary m-r-2">History</button>
            </div>
          </div>
        </div>
      </div>
      <!-- begin process users and display processed -->
      <div class="row">
        <div class="col-sm-4">
          <div class="panel panel-inverse">
            <div class="panel-heading">
              <h4 class="panel-title">Process Contributions - {{ action_text }}</h4>
            </div>
            <div class="panel-body">
              <div class="table-responsive">
                <table class="table table-striped m-b-0">
                  <thead>
                  <tr>
                    <th>Name</th>
                    <th>Verification_no</th>
                    <th>Phone</th>
                    <th>Amount</th>
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-if="!localProcess.length">
                    <td colspan="5" class="text-center">No record, contributions might have been processed for this date!</td>
                  </tr>
                  <tr v-for="i in localProcess" :key="i.id">
                    <td>{{ i.last_name + ' ' + i.first_name + ' ' + i.middle_name }}</td>
                    <td>{{ i.verification_no }}</td>
                    <td>{{ i.phone }}</td>
                    <td>{{ i.contribution_amount }}</td>
                    <td class="with-btn" nowrap>
                      <input type="checkbox" class="form-control" v-model="selectedUsers" :value="i">
                    </td>
                  </tr>
                  <tr>
                    <td colspan="5" class="with-btn" nowrap>
                      <button
                        :disabled="!formUserProcessOk"
                        v-if="canManage || canProcess"
                        @click="doProcess('users')"
                        class="btn btn-sm btn-secondary m-r-2"
                      >Process</button>
                    </td>
                  </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-8">
          <div class="panel panel-inverse">
            <div class="panel-heading">
              <h4 class="panel-title">User Contributions Currently Processed - {{ action_text }}</h4>
            </div>
            <div class="panel-body">
              <div class="table-responsive">
                <table class="table table-striped m-b-0">
                  <thead>
                  <tr>
                    <th>Processed</th>
                    <th>Name</th>
                    <th>Verification_no</th>
                    <th>Amount</th>
                    <th>Phone</th>
                    <th>Year</th>
                    <th>Month</th>
                    <th>Date</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-if="!usersProcessed.length">
                    <td colspan="8" class="text-center">No record</td>
                  </tr>
                  <tr v-for="i in usersProcessed" :key="i.id">
                    <td v-if="i.processed === 1"><span><i class="fa fa-check text-success"></i>&nbsp;</span></td>
                    <td v-else><span><i class="fa fa-remove text-danger"></i>&nbsp;</span></td>
                    <td>{{ i.user.last_name + ' ' + i.user.first_name + ' ' + i.user.middle_name }}</td>
                    <td>{{ i.user.verification_no }}</td>
                    <td>{{ i.amount }}</td>
                    <td>{{ i.phone }}</td>
                    <td>{{ i.year }}</td>
                    <td>{{ i.month }}</td>
                    <td>{{ i.processed_at }}</td>
                  </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- begin process adoptees and display processed -->
      <div class="row">
        <div class="col-sm-4">
          <div class="panel panel-inverse">
            <div class="panel-heading">
              <h4 class="panel-title">Process Adoptee Contributions - {{ action_text }}</h4>
            </div>
            <div class="panel-body">
              <div class="table-responsive">
                <table class="table table-striped m-b-0">
                  <thead>
                  <tr>
                    <th>Name</th>
                    <th>Verification_no</th>
                    <th>Phone</th>
                    <th>Amount</th>
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-if="!localAdopteesProcess.length">
                    <td colspan="5" class="text-center">No record, contributions might have been processed for this date!</td>
                  </tr>
                  <tr v-for="i in localAdopteesProcess" :key="i.id">
                    <td>{{ i.last_name + ' ' + i.first_name + ' ' + i.middle_name }}</td>
                    <td>{{ i.verification_no }}</td>
                    <td>{{ i.phone }}</td>
                    <td>{{ i.contribution_amount }}</td>
                    <td class="with-btn" nowrap>
                      <input type="checkbox" class="form-control" v-model="selectedAdoptees" :value="i">
                    </td>
                  </tr>
                  <tr>
                    <td colspan="5" class="with-btn" nowrap>
                      <button
                        :disabled="!formAdopteeProcessOk"
                        v-if="canManage || canProcess"
                        @click="doProcess('adoptees')"
                        class="btn btn-sm btn-secondary m-r-2"
                      >Process</button>
                    </td>
                  </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-8">
          <div class="panel panel-inverse">
            <div class="panel-heading">
              <h4 class="panel-title">Adoptee Contributions Currently Processed - {{ action_text }}</h4>
            </div>
            <div class="panel-body">
              <div class="table-responsive">
                <table class="table table-striped m-b-0">
                  <thead>
                  <tr>
                    <th>Processed</th>
                    <th>Name</th>
                    <th>Verification_no</th>
                    <th>Amount</th>
                    <th>Phone</th>
                    <th>Year</th>
                    <th>Month</th>
                    <th>Date</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-if="!adopteesProcessed.length">
                    <td colspan="8" class="text-center">No record</td>
                  </tr>
                  <tr v-for="i in adopteesProcessed" :key="i.id">
                    <td v-if="i.processed === 1"><span><i class="fa fa-check text-success"></i>&nbsp;</span></td>
                    <td v-else><span><i class="fa fa-remove text-danger"></i>&nbsp;</span></td>
                    <td>{{ i.user.last_name + ' ' + i.user.first_name + ' ' + i.user.middle_name }}</td>
                    <td>{{ i.user.verification_no }}</td>
                    <td>{{ i.amount }}</td>
                    <td>{{ i.phone }}</td>
                    <td>{{ i.year }}</td>
                    <td>{{ i.month }}</td>
                    <td>{{ i.processed_at }}</td>
                  </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-else-if="action === 'approve'">

      <div class="row">
        <div class="col-sm-12">
          <div class="panel panel-default">
            <div class="panel-heading">
              <button @click.stop.prevent="backToDate()" type="button" class="btn btn-secondary m-r-2">Back to date</button>&nbsp;
              <button @click.stop.prevent="viewContributionHistory()" type="button" class="btn btn-secondary m-r-2">History</button>
            </div>
          </div>
        </div>
      </div>
      <!-- begin process users and display processed -->
      <div class="row">
        <div class="col-sm-4">
          <div class="panel panel-inverse">
            <div class="panel-heading">
              <h4 class="panel-title">Approve Contributions - {{ action_text }}</h4>
            </div>
            <div class="panel-body">
              <div class="table-responsive">
                <table class="table table-striped m-b-0">
                  <thead>
                  <tr>
                    <th>Name</th>
                    <th>Verification_no</th>
                    <th>Phone</th>
                    <th>Amount</th>
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-if="!localApprove.length">
                    <td colspan="5" class="text-center">No record, contributions might have been Approve for this date!</td>
                  </tr>
                  <tr v-for="i in localApprove" :key="i.id">
                    <td>{{ i.last_name + ' ' + i.first_name + ' ' + i.middle_name }}</td>
                    <td>{{ i.verification_no }}</td>
                    <td>{{ i.phone }}</td>
                    <td>{{ i.contribution_amount }}</td>
                    <td class="with-btn" nowrap>
                      <input type="checkbox" class="form-control" v-model="selectedUsersApprove" :value="i">
                    </td>
                  </tr>
                  <tr>
                    <td colspan="5" class="with-btn" nowrap>
                      <button
                        :disabled="!formUserApproveOk"
                        v-if="canManage || canApprove"
                        @click="doApprove('users')"
                        class="btn btn-sm btn-secondary m-r-2"
                      >Approve</button>
                    </td>
                  </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-8">
          <div class="panel panel-inverse">
            <div class="panel-heading">
              <h4 class="panel-title">User Contributions Currently Approved - {{ action_text }}</h4>
            </div>
            <div class="panel-body">
              <div class="table-responsive">
                <table class="table table-striped m-b-0">
                  <thead>
                  <tr>
                    <th>Approved</th>
                    <th>Name</th>
                    <th>Verification_no</th>
                    <th>Phone</th>
                    <th>Amount</th>
                    <th>Batch_code</th>
                    <th>Year</th>
                    <th>Month</th>
                    <th>Date</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-if="!usersApproved.length">
                    <td colspan="9" class="text-center">No record</td>
                  </tr>
                  <tr v-for="i in usersApproved" :key="i.id">
                    <td v-if="i.approved === 1"><span><i class="fa fa-check text-success"></i>&nbsp;</span></td>
                    <td v-else><span><i class="fa fa-remove text-danger"></i>&nbsp;</span></td>
                    <td>{{ i.user.last_name + ' ' + i.user.first_name + ' ' + i.user.middle_name }}</td>
                    <td>{{ i.user.verification_no }}</td>
                    <td>{{ i.user.phone }}</td>
                    <td>{{ i.amount }}</td>
                    <td>{{ i.batch_code }}</td>
                    <td>{{ i.year }}</td>
                    <td>{{ i.month }}</td>
                    <td>{{ i.approved_at }}</td>
                  </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- begin process adoptees and display processed -->
      <div class="row">
        <div class="col-sm-4">
          <div class="panel panel-inverse">
            <div class="panel-heading">
              <h4 class="panel-title">Approve Adoptee Contributions - {{ action_text }}</h4>
            </div>
            <div class="panel-body">
              <div class="table-responsive">
                <table class="table table-striped m-b-0">
                  <thead>
                  <tr>
                    <th>Name</th>
                    <th>Verification_no</th>
                    <th>Phone</th>
                    <th>Amount</th>
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-if="!localAdopteesApprove.length">
                    <td colspan="5" class="text-center">No record, contributions might have been Approve for this date!</td>
                  </tr>
                  <tr v-for="i in localAdopteesApprove" :key="i.id">
                    <td>{{ i.last_name + ' ' + i.first_name + ' ' + i.middle_name }}</td>
                    <td>{{ i.verification_no }}</td>
                    <td>{{ i.phone }}</td>
                    <td>{{ i.contribution_amount }}</td>
                    <td class="with-btn" nowrap>
                      <input type="checkbox" class="form-control" v-model="selectedAdopteesApprove" :value="i">
                    </td>
                  </tr>
                  <tr>
                    <td colspan="5" class="with-btn" nowrap>
                      <button
                        :disabled="!formAdopteeApproveOk"
                        v-if="canManage || canApprove"
                        @click="doApprove('adoptees')"
                        class="btn btn-sm btn-secondary m-r-2"
                      >Approve</button>
                    </td>
                  </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-8">
          <div class="panel panel-inverse">
            <div class="panel-heading">
              <h4 class="panel-title">Adoptee Contributions Currently Processed - {{ action_text }}</h4>
            </div>
            <div class="panel-body">
              <div class="table-responsive">
                <table class="table table-striped m-b-0">
                  <thead>
                  <tr>
                    <th>Approved</th>
                    <th>Name</th>
                    <th>Verification_no</th>
                    <th>Phone</th>
                    <th>Amount</th>
                    <th>Batch_code</th>
                    <th>Year</th>
                    <th>Month</th>
                    <th>Date</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-if="!adopteesApproved.length">
                    <td colspan="9" class="text-center">No record</td>
                  </tr>
                  <tr v-for="i in adopteesApproved" :key="i.id">
                    <td v-if="i.approved === 1"><span><i class="fa fa-check text-success"></i>&nbsp;</span></td>
                    <td v-else><span><i class="fa fa-remove text-danger"></i>&nbsp;</span></td>
                    <td>{{ i.user.last_name + ' ' + i.user.first_name + ' ' + i.user.middle_name }}</td>
                    <td>{{ i.user.verification_no }}</td>
                    <td>{{ i.user.phone }}</td>
                    <td>{{ i.amount }}</td>
                    <td>{{ i.batch_code }}</td>
                    <td>{{ i.year }}</td>
                    <td>{{ i.month }}</td>
                    <td>{{ i.approved_at }}</td>
                  </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div ref="modal" class="modal" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Select Institution - {{ action_text }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="table-responsive">
              <table class="table table-striped m-b-0">
                <thead>
                <tr>
                  <th>Code</th>
                  <th>Name</th>
                  <th>RCC number</th>
                  <th>Country</th>
                  <th>State</th>
                  <th>Lga</th>
                  <th>Town</th>
                  <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="!institutions.length">
                  <td colspan="10" class="text-center">No institutions</td>
                </tr>
                <tr v-for="i in institutions" :key="i.id">
                  <td>{{ i.code }}</td>
                  <td>{{ i.name }}</td>
                  <td>{{ i.rcc_number }}</td>
                  <td>{{ i.country.country }}</td>
                  <td>{{ i.state.name }}</td>
                  <td>{{ i.lga.name }}</td>
                  <td>{{ i.town.name }}</td>
                  <td class="with-btn" nowrap>
                    <button v-if="action === 'history'" @click.stop.prevent="viewInstitutionContributionHistory(i)" class="btn btn-sm btn-secondary m-r-2">Select Institution</button>
                    <button v-else-if="action === 'process'" @click.stop.prevent="processInstitutionContribution(i)" class="btn btn-sm btn-secondary m-r-2">Select Institution</button>
                    <button v-else-if="action === 'approve'" @click.stop.prevent="approveInstitutionContribution(i)" class="btn btn-sm btn-secondary m-r-2">Select Institution</button>
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <!--<button
              :disabled="!formOk"
              @click.stop.prevent="searchResult()"
              type="button"
              class="btn btn-secondary"
            >Search</button>-->
          </div>
        </div>
      </div>
    </div>


  </Page>
</template>
<script>
  import Page from './Page';
  import PageTitle from '../components/header/PageTitle';
  import AlertMixin from '../mixins/AlertMixin';
  import PermissionMixin from '../mixins/PermissionMixin';

  export default {
    name: 'Contributions',
    props: {
      item_title: {
        type: String,
        required: true
      },
      user: {
        type: Object,
      },
      institutions: {
        type: Array,
      },
      adoptees: {
        type: Array,
      },
      years: {
        type: Array,
        required: true
      },
      months: {
        type: Array,
        required: true
      }
    },
    components: {
      Page,
      PageTitle
    },
    mixins: [AlertMixin, PermissionMixin],
    data() {
      return {
        canRead: false,
        canProcess: false,
        canApprove: false,
        canManage: false,
        localHistory: [],
        localAdoptees: [],

        localProcess: [],
        localAdopteesProcess: [],
        selectedUsers: [],
        selectedAdoptees: [],
        usersProcessed: [],
        adopteesProcessed: [],

        usersApproved: [],
        adopteesApproved: [],
        selectedUsersApprove: [],
        selectedAdopteesApprove: [],
        localApprove: [],
        localAdopteesApprove: [],

        errors: '',
        year: 0,
        month: 0,
        monthText: '',
        yearText: '',
        action_text: 'Select year and month',
        action: 'select_date',
        batchCode: ''
      };
    },
    computed: {
      formOk: {
        get: function(){
          const {
            year,
            month
          } = this;
          const yearOk =  year > 0;
          const monthOk = month > 0;
          return  !!yearOk && !!monthOk;
        },
        set: function(val){
          return val;
        }
      },
      formUserProcessOk: {
        get: function(){
          const {
            selectedUsers
          } = this;
          const selectedUsersOk =  Object.values(selectedUsers).length >= 1;
          return !!selectedUsersOk;
        },
        set: function(val){
          return val;
        }
      },
      formAdopteeProcessOk: {
        get: function(){
          const {
            selectedAdoptees
          } = this;
          const selectedAdopteesOk =  Object.values(selectedAdoptees).length >= 1;
          return !!selectedAdopteesOk;
        },
        set: function(val){
          return val;
        }
      },
      formUserApproveOk: {
        get: function(){
          const {
            selectedUsersApprove
          } = this;
          const selectedUsersApproveOk =  Object.values(selectedUsersApprove).length >= 1;
          return !!selectedUsersApproveOk;
        },
        set: function(val){
          return val;
        }
      },
      formAdopteeApproveOk: {
        get: function(){
          const {
            selectedAdopteesApprove
          } = this;
          const selectedAdopteesApproveOk =  Object.values(selectedAdopteesApprove).length >= 1;
          return !!selectedAdopteesApproveOk;
        },
        set: function(val){
          return val;
        }
      },
    },
    watch: {
      year: {
        handler() {
          this.emptyVars();
        }
      },
      month: {
        handler() {
          this.months.map(i => i.value === this.month ? this.monthText = i.name: i);
          this.emptyVars();
        }
      },
      selectedUsers: {
        handler() {
          this.formUserProcessOk = Object.values(this.selectedUsers).length >= 1;
        }
      },
      selectedAdoptees: {
        handler() {
          this.formAdopteeProcessOk = Object.values(this.selectedAdoptees).length >= 1;
        }
      },
      selectedUsersApprove: {
        handler() {
          this.formUserApproveOk = Object.values(this.selectedUsersApprove).length >= 1;
        }
      },
      selectedAdopteesApprove: {
        handler() {
          this.formAdopteeApproveOk = Object.values(this.selectedAdopteesApprove).length >= 1;
        }
      }
    },
    mounted() {
      this.canRead = this.hasPermission('contributions:read');
      this.canProcess = this.hasPermission('contributions:process');
      this.canApprove = this.hasPermission('contributions:approve');
      this.canManage = this.hasPermission('contributions:manage');
    },
    methods: {
      approveContribution() {
        this.action = 'approve';
        if(this.item_title === 'institution'){
          this.action_text = 'Select institution to approve contribution for : [ '+ this.monthText + ', ' + this.year + ' ]';
          this.showToast(this.action_text, true);
          $(this.$refs.modal).modal('show');
        }else if(this.item_title === 'individual'){
          this.showToast('You cannot approve contribution for an institution!', true);
        }
      },
      approveInstitutionContribution(inst) {
        this.action = 'approve';
        this.approve(inst);
      },
      approve(inst) {
        this.action = 'approve';
        let id = inst === 0 ? 0 : inst.id;
        let name = inst === 0 ? '' : inst.name;
        this.action_text = 'Approve contribution for '+ name +': [ '+ this.monthText + ', ' + this.year + ' ]';
        axios.post(`/contributions/approve`, {month: this.month, year: this.year, institution_id: id})
          .then(({ data: { success, message, data } }) => {
            if(success){
              $(this.$refs.modal).modal('hide');
              this.localApprove = data.result;
              this.localAdopteesApprove = data.adoptees;
            }
          });
        this.showToast(this.action_text, true);
      },
      doApprove(type) {
        if(type === 'users'){
          const copy = [ ...this.selectedUsersApprove ];
          let count = 0;
          let errors = [];
          let users = [];
          const requests = copy.map((user) =>
            axios.post(`/contributions/approve/do`, {month: this.month, year: this.year, user_id: user.id})
              .then(({ data: { success, data, message } }) => {
                if (success) {
                  count++;
                  users.push(user.id);
                }else{
                  errors.push(message);
                }
              }).catch(({ response: { data: { message } } }) => {
              errors.push(message);
            }));
          Promise.all(requests).then(() => {
            this.selectedUsersApprove = [];
            this.errors = errors.join();
            console.log('error',this.errors);
            if(count > 0){
              this.localApprove = this.localApprove.filter((user) => !users.includes(user.id));
              if(this.item_title === 'individual'){
                this.fetchApprove({'month': this.month, 'year': this.year, 'users': users, 'user_type': 'users'});
              }else if(this.item_title === 'institution'){
                this.fetchApprove({'month': this.month, 'year': this.year, 'users': users, 'user_type': 'users'});
              }
              this.showToast(count +' user contributions approved for [ '+ this.monthText + ', ' + this.year + ' ]', true);
            }else{
              this.showToast('No user contributions approved for [ '+ this.monthText + ', ' + this.year + ' ]', true);
            }
          });
        }else if(type === 'adoptees'){
          const copy = [ ...this.selectedAdopteesApprove ];
          let count = 0;
          let errors = [];
          let users = [];
          const requests = copy.map((adoptee) =>
            axios.post(`/contributions/approve/do`, {month: this.month, year: this.year, user_id: adoptee.id})
              .then(({ data: { success, data, message } }) => {
                if (success) {
                  count++;
                  users.push(adoptee.id);
                }else{
                  errors.push(message);
                }
              }).catch(({ response: { data: { message } } }) => {
              errors.push(message);
            }));
          Promise.all(requests).then(() => {
            this.selectedAdopteesApprove = [];
            this.errors = errors.join();
            console.log('error',this.errors);
            if(count > 0){
              this.localAdopteesApprove = this.localAdopteesApprove.filter((adoptee) => !users.includes(adoptee.id));
              if(this.item_title === 'individual'){
                this.fetchApprove({'month': this.month, 'year': this.year, 'users': users, 'user_type': 'adoptees'});
              }else if(this.item_title === 'institution'){
                this.fetchApprove({'month': this.month, 'year': this.year, 'users': users, 'user_type': 'adoptees'});
              }
              this.showToast(count +' adoptee contributions approved for [ '+ this.monthText + ', ' + this.year + ' ]', true);
            }else{
              this.showToast('No adoptee contributions approved for [ '+ this.monthText + ', ' + this.year + ' ]', true);
            }
          });
        }
      },
      doProcess(type) {
        if(type === 'users'){
          const copy = [ ...this.selectedUsers ];
          let count = 0;
          let errors = [];
          let users = [];
          const requests = copy.map((user) =>
            axios.post(`/contributions/process/do`, {month: this.month, year: this.year, user_id: user.id, amount: user.contribution_amount, batch_code: ''})
              .then(({ data: { success, data, message } }) => {
                if (success) {
                  count++;
                  users.push(user.id);
                }else{
                  errors.push(message);
                }
              }).catch(({ response: { data: { message } } }) => {
              errors.push(message);
            }));
          Promise.all(requests).then(() => {
            this.selectedUsers = [];
            this.errors = errors.join();
            console.log('error',this.errors);
            if(count > 0){
              this.localProcess = this.localProcess.filter((user) => !users.includes(user.id));
              if(this.item_title === 'individual'){
                this.fetch({'month': this.month, 'year': this.year, 'users': users, 'batch_code': '', 'user_type': 'users', 'mode': 'approve'});
              }else if(this.item_title === 'institution'){
                this.fetch({'month': this.month, 'year': this.year, 'users': users, 'batch_code': '', 'user_type': 'users', 'mode': 'process'});
              }
              this.showToast(count +' user contributions processed for [ '+ this.monthText + ', ' + this.year + ' ]', true);
            }else{
              this.showToast('No user contributions processed for [ '+ this.monthText + ', ' + this.year + ' ]', true);
            }
          });
        }else if(type === 'adoptees'){
          const copy = [ ...this.selectedAdoptees ];
          let count = 0;
          let errors = [];
          let users = [];
          const requests = copy.map((adoptee) =>
            axios.post(`/contributions/process/do`, {month: this.month, year: this.year, user_id: adoptee.id, amount: adoptee.contribution_amount, batch_code: ''})
              .then(({ data: { success, data, message } }) => {
                if (success) {
                  count++;
                  users.push(adoptee.id);
                }else{
                  errors.push(message);
                }
              }).catch(({ response: { data: { message } } }) => {
              errors.push(message);
            }));
          Promise.all(requests).then(() => {
            this.selectedAdoptees = [];
            this.errors = errors.join();
            console.log('error',this.errors);
            if(count > 0){
              this.localAdopteesProcess = this.localAdopteesProcess.filter((adoptee) => !users.includes(adoptee.id));
              if(this.item_title === 'individual'){
                this.fetch({'month': this.month, 'year': this.year, 'users': users, 'batch_code': '', 'user_type': 'adoptees', 'mode': 'approve'});
              }else if(this.item_title === 'institution'){
                this.fetch({'month': this.month, 'year': this.year, 'users': users, 'batch_code': '', 'user_type': 'adoptees', 'mode': 'process'});
              }
              this.showToast(count +' adoptee contributions processed for [ '+ this.monthText + ', ' + this.year + ' ]', true);
            }else{
              this.showToast('No adoptee contributions processed for [ '+ this.monthText + ', ' + this.year + ' ]', true);
            }
          });
        }
      },
      emptyVars() {
        this.usersProcessed = [];
        this.adopteesProcessed = [];
        this.selectedAdoptees = [];
        this.selectedUsers = [];
        this.localAdopteesProcess = [];
        this.localProcess = [];
        this.localHistory = [];
        this.localAdoptees = [];
        this.usersApproved = [];
        this.adopteesApproved = [];
        this.selectedUsersApprove = [];
        this.selectedAdopteesApprove = [];
        this.localApprove = [];
        this.localAdopteesApprove = [];
      },
      viewContributionHistory() {
        this.action = 'history';
        if(this.item_title === 'institution'){
          this.action_text = 'approved contribution for : [ '+ this.monthText + ', ' + this.year + ' ]';
          $(this.$refs.modal).modal('show');
        }else if(this.item_title === 'individual'){
          this.action_text = 'Your approved contribution for : [ '+ this.monthText + ', ' + this.year + ' ]';
          this.history(0);
        }
      },
      viewInstitutionContributionHistory(inst) {
        this.action = 'history';
        this.history(inst);
      },
      fetch(obj) {
        axios.post(`/contributions/fetch/process`, obj)
          .then(({ data: { success, message, data } }) => {
            if(success){
              if(obj.user_type === 'users'){
                if(obj.mode === 'process' || this.item_title === 'individual'){
                  data.result.map(i => this.usersProcessed.push(i));
                }else{
                  data.result.map(i => this.usersApproved.push(i));
                }
              }else if(obj.user_type === 'adoptees'){
                if(obj.mode === 'process' || this.item_title === 'individual'){
                  data.result.map(i => this.adopteesProcessed.push(i));
                }else{
                  data.result.map(i => this.adopteesApproved.push(i));
                }
              }
            }
          });
      },
      fetchApprove(obj) {
        axios.post(`/contributions/fetch/approve`, obj)
          .then(({ data: { success, message, data } }) => {
            if(success){
              if(obj.user_type === 'users'){
                data.result.map(i => this.usersApproved.push(i));
              }else if(obj.user_type === 'adoptees'){
                data.result.map(i => this.adopteesApproved.push(i));
              }
            }
          });
      },
      history(inst) {
        this.action = 'history';
        let id = inst === 0 ? 0 : inst.id;
        let name = inst === 0 ? '' : inst.name;
        this.action_text = 'approved contribution for '+ name +': [ '+ this.monthText + ', ' + this.year + ' ]';
        axios.post(`/contributions/history`, {month: this.month, year: this.year, institution_id: id})
          .then(({ data: { success, message, data } }) => {
            if(success){
              $(this.$refs.modal).modal('hide');
              this.localHistory = data.result;
              this.localAdoptees = data.adoptees;
            }
          });
        this.showToast(this.action_text, true);
      },
      backToDate(){
        this.action_text = 'Select year and month';
        this.action = 'select_date';
        this.emptyVars();
      },
      processContribution() {
        this.action = 'process';
        if(this.item_title === 'institution'){
          this.action_text = 'Process contribution for : [ '+ this.monthText + ', ' + this.year + ' ]';
          $(this.$refs.modal).modal('show');
        }else if(this.item_title === 'individual'){
          this.action_text = 'Process your contribution for : [ '+ this.monthText + ', ' + this.year + ' ]';
          this.process(0);
        }
      },
      processInstitutionContribution(inst) {
        this.action = 'process';
        this.process(inst);
      },
      process(inst) {
        this.action = 'process';
        let id = inst === 0 ? 0 : inst.id;
        let name = inst === 0 ? '' : inst.name;
        this.action_text = 'Process contribution for '+ name +': [ '+ this.monthText + ', ' + this.year + ' ]';
        axios.post(`/contributions/process`, {month: this.month, year: this.year, institution_id: id})
          .then(({ data: { success, message, data } }) => {
            if(success){
              console.log(data.adoptees);
              $(this.$refs.modal).modal('hide');
              this.localProcess = data.result;
              this.localAdopteesProcess = data.adoptees;
            }
          });
        this.showToast(this.action_text, true);
      },

    }
  }
</script>

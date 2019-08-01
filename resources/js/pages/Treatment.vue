<template>
  <Page>
    <page-title title="Treatments"/>
    <div class="row">

      <div v-if="!selectedHcp" class="col-sm-12" id="view-hcp">
        <div class="panel panel-inverse">
          <div class="panel-heading">
            <h4 class="panel-title">HCPs - select a HCP to add treatment</h4>
          </div>
          <div class="panel-body">
            <div class="table-responsive">
              <table class="table table-striped m-b-0">
                <thead>
                <tr>
                  <th>Code</th>
                  <th>Name</th>
                  <th>Phone</th>
                  <th>Email</th>
                  <!--<th>Account</th>
                  <th>Bank</th>-->
                  <th>Type</th>
                  <th>Country</th>
                  <th>State</th>
                  <th>Lga</th>
                  <th>Town</th>
                  <!--<th>Address</th>-->
                  <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="!localHcps.length">
                  <td colspan="13" class="text-center">No hcps</td>
                </tr>
                <tr v-for="i in localHcps" :key="i.id">
                  <td>{{ i.code }}</td>
                  <td>{{ i.name }}</td>
                  <td>{{ i.phone }}</td>
                  <td>{{ i.email }}</td>
                  <!--<td>{{ i.account_number }}</td>
                  <td>{{ i.bank.bank_name }}</td>-->
                  <td>{{ i.hcp_type.hcp_type }}</td>
                  <td>{{ i.country.country }}</td>
                  <td>{{ i.state.name }}</td>
                  <td>{{ i.lga.name }}</td>
                  <td>{{ i.town.name }}</td>
                  <!--<td>{{ i.address }}</td>-->
                  <td class="with-btn" nowrap>
                    <button
                      @click.stop.prevent="selectHcp(i)"
                      class="btn btn-sm btn-secondary"
                    >Select HCP for treatment</button>
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div v-else-if="selectedHcp && selectedTitle === 'Search User'" class="col-sm-12">
        <div class="panel panel-inverse">
          <div class="panel-heading">
            <h4 class="panel-title">Select User/Contributor from list - user selected here will be bio-verified before treatment</h4>
          </div>
          <div class="panel-body">
            <button
              v-if="canVerifyConfirm"
              class="btn btn-sm btn-secondary"
              @click.stop.prevent="verifyConfirm()"
            >Confirm Verification</button>
            <button
              v-if="canVerify"
              class="btn btn-sm btn-secondary"
              @click.stop.prevent="searchUser()"
            >New Search</button>
            <button
              class="btn btn-sm btn-secondary"
              @click.stop.prevent="backToHcps()"
            >Back</button>
            <div class="table-responsive">
              <table class="table table-striped m-b-0">
                <thead>
                <tr>
                  <th>Name</th>
                  <th>Verification_no</th>
                  <th>Phone</th>
                  <th>Email</th>
                  <th>Gender</th>
                  <th v-if="canVerify">Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="!searchUsers.length">
                  <td colspan="6" class="text-center">No users</td>
                </tr>
                <tr v-for="i in searchUsers" :key="i.id">
                  <td>{{ i.last_name + ' ' + i.first_name + ' ' + i.middle_name }}</td>
                  <td>{{ i.verification_no }}</td>
                  <td>{{ i.phone }}</td>
                  <td>{{ i.email }}</td>
                  <td>{{ i.gender ? i.gender.gender : '' }}</td>
                  <td v-if="canVerify" class="with-btn" nowrap>
                    <button
                      @click.stop.prevent="verifyUser(i)"
                      class="btn btn-sm btn-secondary m-r-2"
                    >Verify</button>
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div v-else-if="selectedHcp && selectedTitle === 'Confirm Verification' && verified" class="col-sm-12">
        <div class="panel panel-inverse">
          <div class="panel-heading">
            <h4 class="panel-title">Check and confirm this verification</h4>
          </div>
          <div class="panel-body">

            <div class="table-responsive" v-if="verified">
              <table class="table table-striped m-b-0">
                <tr>
                  <th>Picture</th>
                  <td><img height="400px" width="400px" :src="verified.img_url" ></td>
                </tr>
                <tr>
                  <th>Name</th>
                  <td>{{ verified.user.user.last_name + ' '+ verified.user.user.first_name}}</td>
                </tr>
                <tr>
                  <th>User Verification No.</th>
                  <td>{{ verified.user.user.verification_no }}</td>
                </tr>
                <tr>
                  <th>Verification code</th>
                  <td>{{ verified.user.verification_code }}</td>
                </tr>
                <tr>
                  <th>Verification Date</th>
                  <td>{{ verified.user.created_at }}</td>
                </tr>
                <tr>
                  <th>&nbsp;</th>
                  <td>
                    <button
                      @click.stop.prevent="exitLink()"
                      class="btn btn-sm btn-secondary m-r-2"
                    >Confirm</button>
                  </td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div v-else-if="selectedHcp && selectedTitle === 'Show Treatments' && localTreatments" class="col-sm-12">
        <div class="panel panel-inverse">
          <div class="panel-heading">
            <h4 class="panel-title">HCP-Treatment</h4>
          </div>
          <div class="panel-body">
            <button
              v-if="canAddTreatment"
              class="btn btn-sm btn-secondary"
              @click.stop.prevent="addTreatment(null)"
            >Add Treatment</button>
            <button
              class="btn btn-sm btn-secondary"
              @click.stop.prevent="selectedHcp = null"
            >Back to Hcps</button>
            <div class="table-responsive">
              <table class="table table-striped m-b-0">
                <thead>
                <tr>
                  <th>Verification Code</th>
                  <th>Treatment Code</th>
                  <th>User</th>
                  <th>Bill</th>
                  <th>Med. Officer</th>
                  <th>Med. Condition</th>
                  <th>Diagnosis</th>
                  <th>Medication</th>
                  <th>Date</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="!localTreatments.length">
                  <td colspan="11" class="text-center">No Treatments</td>
                </tr>
                <tr v-for="i in localTreatments" :key="i.id">
                  <td>{{ i.verification_code }}</td>
                  <td>{{ i.code }}</td>
                  <td>{{ i.user.last_name + ' ' + i.user.first_name + ' (' + i.user.verification_no + ')' }}</td>
                  <td>{{ i.bill }}</td>
                  <td>{{ i.medical_officer }}</td>
                  <td>{{ i.diagnosis }}</td>
                  <td>{{ i.medical_condition }}</td>
                  <td>{{ i.medication_administered }}</td>
                  <td>{{ i.created_at }}</td>
                  <td>{{ i.paid === 1 ? 'Paid' : 'Not-Paid' }}</td>
                  <td class="with-btn" nowrap>
                    <button
                      v-if="canUpdateTreatment && !i.paid"
                      @click.stop.prevent="addTreatment(i)"
                      class="btn btn-xs btn-secondary m-r-2"
                    >View/Edit</button>
                    <button
                      v-if="canDeleteTreatment"
                      @click.stop.prevent="deleteTreatment(i)"
                      class="btn btn-xs btn-secondary"
                    >Delete</button>
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div ref="modalSearch" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">{{ selectedTitle }}</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div v-if="errors.length" class="alert alert-warning" v-html="errors" />
              <form>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Search with name, verification_no, email, phone</label>
                  <div class="col-md-6 col-sm-6">
                    <input type="text" class="form-control" v-model.trim="search_string">
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button
                :disabled="!formSearchOk"
                @click.stop.prevent="searchResult()"
                type="button"
                class="btn btn-secondary"
              >Search</button>
            </div>
          </div>
        </div>
      </div>

      <div ref="modalConfirm" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">{{ selectedTitle }}</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div v-if="errors.length" class="alert alert-warning" v-html="errors" />
              <form>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Provide verification code</label>
                  <div class="col-md-6 col-sm-6">
                    <input type="text" class="form-control" v-model.trim="search_string">
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button
                :disabled="!formConfirmOk"
                @click.stop.prevent="confirmResult()"
                type="button"
                class="btn btn-secondary"
              >Verify</button>
            </div>
          </div>
        </div>
      </div>

      <div ref="addTreatmentModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">{{ selectedTitle }}</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div v-if="errors.length" class="alert alert-warning" v-html="errors" />
              <form>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Bill *</label>
                  <div class="col-md-6 col-sm-6">
                    <input type="number" class="form-control" v-model.trim="treatment.bill">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Verification Code *</label>
                  <div class="col-md-6 col-sm-6">
                    <input readonly disabled type="text" class="form-control" v-model.trim="treatment.verification_code">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Code *</label>
                  <div class="col-md-6 col-sm-6">
                    <input readonly disabled type="text" class="form-control" v-model.trim="treatment.code">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Medical Officer *</label>
                  <div class="col-md-6 col-sm-6">
                    <input type="text" class="form-control" v-model.trim="treatment.medical_officer">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Diagnosis</label>
                  <div class="col-md-6 col-sm-6">
                    <input type="email" class="form-control" v-model.trim="treatment.diagnosis">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Medical Condition *</label>
                  <div class="col-md-6 col-sm-6">
                    <input type="text" class="form-control" v-model.trim="treatment.medical_condition">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Medication Administered *</label>
                  <div class="col-md-6 col-sm-6">
                    <input type="text" class="form-control" v-model.trim="treatment.medication_administered">
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button
                :disabled="!treatmentFormOk"
                @click.stop.prevent="saveTreatment()"
                type="button"
                class="btn btn-secondary"
              >Save Treatment</button>
            </div>
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
  import FilterMixin from "../mixins/FilterMixin";

  const defaultTreatment = {
    verification_code: '',
    code: '',
    bill: '',
    medical_officer: '',
    diagnosis: '',
    medical_condition: '',
    medication_administered: '',
    hcp_id: '',
    user_id: ''
  };

  export default {
    name: 'Treatment',
    components: {
      Page,
      PageTitle
    },
    mixins: [AlertMixin, PermissionMixin, FilterMixin],
    props: {
      hcps: {
        type: Array,
        required: true
      },
    },
    data() {
      return {
        canVerifyConfirm: false,
        canVerify: false,
        canAddTreatment: false,
        canReadTreatment: false,
        canUpdateTreatment: false,
        canDeleteTreatment: false,
        localHcps: this.hcps,
        localTreatments: {},
        treatment: { ...defaultTreatment },
        selectedHcp: null,
        searchUsers: [],
        search_string: '',
        selectedTitle: '',
        verified: null,
        verification_code: '',
        selectedUser: null,
        errors: ''
      };
    },
    computed: {
      formSearchOk() {
        const {
          search_string,
        } = this;
        const search_stringOk = search_string.length > 0;
        return !!search_stringOk;
      },
      formConfirmOk() {
        const {
          search_string,
        } = this;
        const search_stringOk = search_string.length > 0;
        return !!search_stringOk;
      },
      treatmentFormOk() {
        const {
          treatment: {
            id = 0,
            code,
            bill,
            medical_officer,
            diagnosis,
            medical_condition,
            medication_administered
          }
        } = this;

        const billOk = !!bill > 0;
        const medical_officerOk = !!medical_officer.length;
        const medication_administeredOk = !!medication_administered.length;
        const medical_conditionOk = !!medical_condition.length;
        //const codeOk = !!code.length && treatments.every(i => i.code !== code);
        const codeOk = !!code.length;

        return billOk && medical_officerOk && codeOk && medical_conditionOk && medication_administeredOk;
      }
    },
    watch: {

    },
    mounted() {
      this.canVerifyConfirm = this.hasPermission('treatments:verify-confirm');
      this.canVerify = this.hasPermission('treatments:verify');
      this.canAddTreatment = this.hasPermission('treatments:create');
      this.canReadTreatment = this.hasPermission('treatments:read');
      this.canUpdateTreatment = this.hasPermission('treatments:update');
      this.canDeleteTreatment = this.hasPermission('treatments:delete');
    },
    methods: {
      b64EncodeUnicode(str) {
        return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g,
          function toSolidBytes(match, p1) {
            return String.fromCharCode('0x' + p1);
          }));
      },
      b64DecodeUnicode(str) {
        return decodeURIComponent(atob(str).split('').map(function(c) {
          return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
        }).join(''));
      },
      selectHcp(i) {
        this.selectedHcp = i;
        this.selectedTitle = 'Search User';
      },
      backToHcps() {
        this.selectedHcp = null;
        this.selectedTitle = '';
      },
      verifyUser(i) {
        let url_string = JSON.stringify({user_id: i.id, last_url: window.location.href, action: 'hcp_verify_user', hcp_id: this.selectedHcp.id});
        window.location.href = process.env.MIX_BIOMETRIC_VERIFY_START_URL + '/' + this.b64EncodeUnicode(url_string);
      },
      searchUser() {
        this.selectedTitle = 'Search User';
        this.search_string = '';
        $(this.$refs.modalSearch).modal('show');
      },
      verifyConfirm() {
        this.selectedTitle = 'Confirm Verification';
        this.search_string = '';
        $(this.$refs.modalConfirm).modal('show');
      },
      confirmResult() {
        const copy = {};
        copy.str = this.search_string;
        this.selectedTitle = 'Confirm Verification';
        axios.post(`/treatments/verify-confirm`,copy)
          .then(({ data: { success, data, message } }) => {
            if (success) {
              $(this.$refs.modalConfirm).modal('hide');
              this.verified = data;
              this.verification_code = data.user.verification_code;
              this.selectedUser = data.user.user;
            }else{
              this.emptyVars();
              this.errors = message;
            }
          }).catch(({ response: { data: { data } } }) => {
          this.errors = Object.values(data).flat().join('<br>');
        });
      },
      emptyVars() {
        this.verified = null;
        this.verification_code = '';
        this.selectedUser = null;
      },
      exitLink() {
        this.selectedTitle = 'Show Treatments';
        axios
          .get(`/hcp/${this.selectedHcp.id}/treatments`)
          .then(({ data: { treatments } }) => {
            this.localTreatments = treatments;
          });
      },
      searchResult() {
        const copy = {};
        copy.str = this.search_string;
        axios
          .post(`/users/search`,copy)
          .then(({ data: { success, data, message } }) => {
            if (success) {
              $(this.$refs.modalSearch).modal('hide');
              if (!data.length) {
                this.searchUsers = [];
                Object.keys(data).forEach(k => {
                  this.searchUsers.push(data[k]);
                });
              } else {
                this.searchUsers = data;
              }
            }else{
              this.errors = message;
            }
          }).catch(({ response: { data: { data } } }) => {
          this.errors = Object.values(data).flat().join('<br>');
        });
      },
      addTreatment(treatment) {
        if (treatment) {
          this.selectedTitle = `Update ${treatment.code}`;
          this.treatment = { ...treatment };
        } else {
          this.selectedTitle = 'Create Hcp Treatment';
          this.treatment = { ...defaultTreatment };
          axios
            .get(`/codes/hcp/${this.selectedHcp.code}/treatments`)
            .then(({ data: { data: { code } } }) => {
              this.treatment.code = code;
              this.treatment.verification_code = this.verification_code;
            });
        }
        $(this.$refs.addTreatmentModal).modal('show');
      },
      deleteTreatment(treatment) {
        axios
          .delete(`/treatments/${treatment.id}`)
          .then(({ data: { success, data } }) => {
            if (success) {
              this.showToast('Treatment deleted');
              this.localTreatments = this.localTreatments ? this.localTreatments.filter(u => u.id !== treatment.id) : {...defaultTreatment};
            }
          }).catch(({ response: { data: { data, message } } }) => {
          data.length <= 0 ? this.errors = message : this.errors = Object.values(data).flat().join('<br>');
        });
      },
      saveTreatment() {
        this.selectedTitle = 'Show Treatments';
        const copy = { ...this.treatment };
        copy.medical_officer = copy.medical_officer.toUpperCase();
        copy.code = copy.code.toUpperCase();
        copy.hcp_id = this.selectedHcp.id;
        copy.user_id = this.selectedUser.id;
        if (copy.id) {
          axios
            .put(`/treatments/${copy.id}`, copy)
            .then(({ data: { success, data, message = 'Could not update' } }) => {
              this.showToast(message, success);
              if (success) {
                $(this.$refs.addTreatmentModal).modal('hide');
                this.localTreatments = this.localTreatments.map((treatment) => {
                  if (data.id === treatment.id) return data;
                  return treatment;
                });
              }
            }).catch(({ response: { data: { data } } }) => {
            this.errors = Object.values(data).flat().join('<br>');
          });
        } else {
          axios
            .post('/treatments', copy)
            .then(({ data: { success, data, message = 'Could not create' } }) => {
              this.showToast(message, success);
              if (success) {
                $(this.$refs.addTreatmentModal).modal('hide');
                this.localTreatments.push(data);
                this.treatment = { ...defaultTreatment };
              }
            }).catch(({ response: { data: { data } } }) => {
            this.errors = Object.values(data).flat().join('<br>');
          });
        }
      },


    }
  }
</script>

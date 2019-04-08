<template>
  <Page>
    <page-title title="HCPs"/>
    <div class="row">
      <div v-if="!selectedHcp" class="col-sm-12" id="view-hcp">
        <div class="panel panel-inverse">
          <div class="panel-heading">
            <h4 class="panel-title">HCPs</h4>
          </div>
          <div class="panel-body">
            <button
              v-if="canCreate"
              class="btn btn-sm btn-secondary"
              @click.stop.prevent="view(null)"
            >Add HCP</button>
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
                      v-if="canUpdate"
                      @click.stop.prevent="view(i)"
                      class="btn btn-sm btn-secondary m-r-2"
                    >View/Edit</button>
                    <button
                      v-if="i.active && canUpdate"
                      @click.stop.prevent="deactivate(i)"
                      class="btn btn-sm btn-secondary"
                    >Deactivate</button>
                    <button
                      v-else-if="canUpdate"
                      @click.stop.prevent="activate(i)"
                      class="btn btn-sm btn-secondary"
                    >Activate</button>
                    <button
                      v-if="canReadTreatment"
                      @click.stop.prevent="treatments(i)"
                      class="btn btn-sm btn-secondary"
                    >Treatments</button>
                    <a :href="hcpUsersLink(i)" class="btn btn-sm btn-secondary">Users</a>
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div v-else class="col-sm-12" id="view-treatment">
        <div class="panel panel-inverse">
          <div class="panel-heading">
            <h4 class="panel-title">HCP-Treatment</h4>
          </div>
          <div class="panel-body">
            <button
              v-if="canAddTreatment"
              class="btn btn-sm btn-secondary"
              @click.stop.prevent="viewTreatment(null)"
            >Add Treatment</button>
            <button
              class="btn btn-sm btn-secondary"
              @click.stop.prevent="selectedHcp = null"
            >Back to Hcps</button>
            <div class="table-responsive">
              <table class="table table-striped m-b-0">
                <thead>
                <tr>
                  <th>Code</th>
                  <th>User</th>
                  <th>Bill</th>
                  <th>Med. Officer</th>
                  <th>Med. Condition</th>
                  <th>Diagnosis</th>
                  <th>Medication</th>
                  <th>Date</th>
                  <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="!localTreatments.length">
                  <td colspan="13" class="text-center">No Treatments</td>
                </tr>
                <tr v-for="i in localTreatments" :key="i.id">
                  <td>{{ i.code }}</td>
                  <td>{{ i.user.last_name + ' ' + i.user.first_name + ' (' + i.user.verification_no + ')' }}</td>
                  <td>{{ i.bill }}</td>
                  <td>{{ i.medical_officer }}</td>
                  <td>{{ i.diagnosis }}</td>
                  <td>{{ i.medical_condition }}</td>
                  <td>{{ i.medication_administered }}</td>
                  <td>{{ i.created_at }}</td>
                  <td class="with-btn" nowrap>
                    <button
                      v-if="canUpdateTreatment"
                      @click.stop.prevent="viewTreatment(i)"
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

      <div ref="instModal" class="modal" tabindex="-1" role="dialog">
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
                  <label class="control-label col-md-4 col-sm-4">Name *</label>
                  <div class="col-md-6 col-sm-6">
                    <input type="text" class="form-control" v-model.trim="hcp.name">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Code *</label>
                  <div class="col-md-6 col-sm-6">
                    <input readonly disabled type="text" class="form-control" v-model.trim="hcp.code">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Phone *</label>
                  <div class="col-md-6 col-sm-6">
                    <input type="text" class="form-control" v-model.trim="hcp.phone">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Email *</label>
                  <div class="col-md-6 col-sm-6">
                    <input type="email" class="form-control" v-model.trim="hcp.email">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Address *</label>
                  <div class="col-md-6 col-sm-6">
                    <input type="text" class="form-control" v-model.trim="hcp.address">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Account Number *</label>
                  <div class="col-md-6 col-sm-6">
                    <input type="text" class="form-control" v-model.trim="hcp.account_number">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Bank *</label>
                  <div class="col-md-6 col-sm-6">
                    <v-select
                      placeholder="Select Bank"
                      label="bank_name"
                      :options="banks"
                      v-model="bank"
                    />
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Account Name </label>
                  <div class="col-md-6 col-sm-6">
                    <input readonly type="text" class="form-control" v-model.trim="hcp.account_name">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Hcp Type *</label>
                  <div class="col-md-6 col-sm-6">
                    <v-select
                      placeholder="Select hcp_type"
                      label="hcp_type"
                      :options="hcp_types"
                      v-model="hcp_type"
                    />
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Country *</label>
                  <div class="col-md-6 col-sm-6">
                    <v-select
                      placeholder="Select country"
                      label="country"
                      :options="countries"
                      v-model="country"
                    />
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">State *</label>
                  <div class="col-md-6 col-sm-6">
                    <v-select
                      placeholder="Select state"
                      label="name"
                      :options="filteredStates"
                      v-model="state"
                    />
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">LGA *</label>
                  <div class="col-md-6 col-sm-6">
                    <v-select
                      placeholder="Select lga"
                      label="name"
                      :options="filteredLgas"
                      v-model="lga"
                    />
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Town</label>
                  <div class="col-md-6 col-sm-6">
                    <v-select
                      placeholder="Select town"
                      label="name"
                      :options="filteredTowns"
                      v-model="town"
                    />
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button
                :disabled="!formOk"
                @click.stop.prevent="save()"
                type="button"
                class="btn btn-secondary"
              >Save</button>
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

  const defaultHcp = {
    code: '',
    name: '',
    hcp_type_id: '',
    country_id: null,
    state_id: null,
    lga_id: null,
    town_id: null,
    account_number: '',
    account_name: '',
    cbn_code: '',
    active: 1,
    email: '',
    phone: '',
    address: ''
  };

  const defaultTreatment = {
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
    name: 'Hcps',
    components: {
      Page,
      PageTitle
    },
    mixins: [AlertMixin, PermissionMixin],
    props: {
      hcps: {
        type: Array,
        required: true
      },
      countries: {
        type: Array,
        required: true
      },
      banks: {
        type: Array,
        required: true
      },
      hcp_types: {
        type: Array,
        required: true
      },
      states: {
        type: Array,
        required: true
      },
      lgas: {
        type: Array,
        required: true
      }
    },
    data() {
      return {
        canCreate: false,
        canUpdate: false,
        canAddTreatment: false,
        canReadTreatment: false,
        canUpdateTreatment: false,
        canDeleteTreatment: false,
        localHcps: this.hcps,
        localTreatments: {},
        towns: [],
        hcp: { ...defaultHcp },
        treatment: { ...defaultTreatment },
        selectedTitle: '',
        selectedHcp: null,
        selectedUser: {
          id: 25
        },
        hcp_type: { hcp_type: '' },
        bank: { bank: '' },
        country: { country: '' },
        state: { name: '' },
        lga: { name: '' },
        town: { name: '' },
        errors: ''
      };
    },
    computed: {
      filteredStates() {
        const { country } = this;
        return (!country.id) ? this.states : this.states.filter(({ country_id }) => country_id === country.id);
      },
      filteredLgas() {
        const { state } = this;
        return !state || !state.id ? this.lgas : this.lgas.filter(({ state_id }) => state_id === state.id);
      },
      filteredTowns() {
        const { lga } = this;
        return !lga || !lga.id ? this.towns : this.towns.filter(({ lga_id }) => lga_id === lga.id);
      },
      formOk() {
        const {
          localHcps,
          filteredStates,
          filteredLgas,
          hcp_types,
          countries,
          banks,
          hcp: {
            id = 0,
            code,
            name,
            email,
            account_number,
            account_name,
            cbn_code,
            phone,
            hcp_type_id,
            country_id,
            state_id,
            lga_id,
            town_id,
            active,
            address
          }
        } = this;
        const hcps = localHcps.filter(i => id !== i.id);

        const account_numberOk = !!account_number.length && hcps.every(i => i.account_number !== account_number);
        const nameOk = !!name.length && hcps.every(i => i.name !== name);
        const codeOk = !!code.length && hcps.every(i => i.code !== code);
        const emailAndPhoneOk = !!phone.length &&
          !!email.length &&
          hcps.every(i => i.phone !== phone && i.email !== email);
        const addressOk = !!address.length;
        const hcp_typeOk = hcp_types.some(({ id }) => id === hcp_type_id);
        const countryOk = countries.some(({ id }) => id === country_id);
        const stateOk = filteredStates.some(({ id }) => id === state_id);
        const lgaOk = filteredLgas.some(({ id }) => id === lga_id);

        //console.log('address', addressOk);

        return nameOk &&
          account_numberOk &&
          codeOk &&
          emailAndPhoneOk &&
          addressOk &&
          hcp_typeOk &&
          countryOk &&
          stateOk &&
          lgaOk;
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
      country(country) {
        this.hcp.country_id = country && country.id ? country.id : null;
      },
      bank(bank) {
        this.hcp.cbn_code = bank && bank.cbn_code ? bank.cbn_code : null;
      },
      hcp_type(hcp_type) {
        this.hcp.hcp_type_id = hcp_type && hcp_type.id ? hcp_type.id : null;
      },
      state(state) {
        if (state && state.id) {
          this.hcp.state_id = state.id;
          this.fetchTowns();
        } else {
          this.hcp.state_id = null;
        }
      },
      lga(lga) {
        this.hcp.lga_id = lga && lga.id ? lga.id : null;
      },
      town(town) {
        this.hcp.town_id = town && town.id ? town.id : null;
      }
    },
    mounted() {
      this.canCreate = this.hasPermission('hcps:create');
      this.canUpdate = this.hasPermission('hcps:update');
      this.canAddTreatment = this.hasPermission('treatments:create');
      this.canReadTreatment = this.hasPermission('treatments:read');
      this.canUpdateTreatment = this.hasPermission('treatments:update');
      this.canDeleteTreatment = this.hasPermission('treatments:delete');
    },
    methods: {
      fetchTowns() {
        this.towns = [];
        axios
          .get(`/options/towns/${this.state.id}`)
          .then(({ data: { towns } }) => {
            this.towns = towns;
            if (!!this.hcp.country_id) this.town = towns.find(({ id }) => id === this.hcp.town_id);
          });
      },
      save() {
        const copy = { ...this.hcp };
        copy.name = copy.name.toUpperCase();
        copy.code = copy.code.toUpperCase();

        if (copy.id) {
          axios
            .put(`/hcps/${copy.id}`, copy)
            .then(({ data: { success, data, message = 'Could not update' } }) => {
              this.showToast(message, success);
              if (success) {
                $(this.$refs.instModal).modal('hide');
                this.localHcps = this.localHcps.map((hcp) => {
                  if (data.id === hcp.id) return data;
                  return hcp;
                });
              }
            }).catch(({ response: { data: { data } } }) => {
            this.errors = Object.values(data).flat().join('<br>');
          });
        } else {
          axios
            .post('/hcps', copy)
            .then(({ data: { success, data, message = 'Could not create' } }) => {
              this.showToast(message, success);
              if (success) {
                $(this.$refs.instModal).modal('hide');
                this.localHcps.push(data);
                this.hcp = { ...defaultHcp };
              }
            }).catch(({ response: { data: { data } } }) => {
            this.errors = Object.values(data).flat().join('<br>');
          });
        }
      },
      view(inst) {
        if (inst) {
          this.selectedTitle = `Update ${inst.name}`;
          this.country = this.countries.find(({ id }) => id === inst.country_id) || {};
          this.bank = this.banks.find(({ cbn_code }) => cbn_code === inst.cbn_code) || {};
          this.hcp_type = this.hcp_types.find(({ id }) => id === inst.hcp_type_id) || {};
          this.state = this.states.find(({ id }) => id === inst.state_id) || {};
          this.lga = this.lgas.find(({ id }) => id === inst.lga_id) || {};
          this.hcp = { ...inst };
        } else {
          this.selectedTitle = 'Create Hcp';
          this.bank = {};
          this.hcp_type = {};
          this.country = {};
          this.state = {};
          this.lga = {};
          this.town = {};
          this.hcp = { ...defaultHcp };
          axios
            .get(`/codes/hcp`)
            .then(({ data: { data: { code } } }) => {
              this.hcp.code = code;
            });
        }
        $(this.$refs.instModal).modal('show');
      },
      viewTreatment(treatment) {
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
            });
        }
        $(this.$refs.addTreatmentModal).modal('show');
      },
      treatments(hcp) {
        if (hcp) {
          this.selectedTitle = `Treatments - ${hcp.name}`;
          this.selectedHcp = hcp;
          axios
            .get(`/hcp/${hcp.id}/treatments`)
            .then(({ data: { treatments } }) => {
              this.localTreatments = treatments;
            });
        }
      },
      saveTreatment() {
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
      deactivate(inst) {
        axios
          .put(`/hcps/${inst.id}`, { active: 0 })
          .then(({ data: { success, data } }) => {
            if (success) {
              this.showToast('Hcp deactivated');
              this.localHcps = this.localHcps.map((hcp) => {
                if (data.id === hcp.id) return data;
                return hcp;
              });
            }
          })
      },
      activate(inst) {
        axios
          .put(`/hcps/${inst.id}`, { active: 1 })
          .then(({ data: { success, data } }) => {
            if (success) {
              this.showToast('Hcp activated');
              this.localHcps = this.localHcps.map((hcp) => {
                if (data.id === hcp.id) return data;
                return hcp;
              });
            }
          });
      },
      hcpUsersLink({ id }) {
        return `/hcps/${id}/users`;
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
      }
    }
  }
</script>

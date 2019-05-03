<template>
  <Page>
    <page-title title="Claims"/>
    <div class="row">

      <div v-if="itemTitle === 'state'" class="col-sm-12">
        <div class="panel panel-inverse">
          <div class="panel-heading">
            <h4 class="panel-title">State Listings - {{pageTitle}}</h4>
          </div>
          <div class="panel-body">
            <div class="table-responsive">
              <table class="table table-striped m-b-0">
                <thead>
                <tr>
                  <th>State (Treatments)</th>
                  <th>Total Bill</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="!states.length">
                  <td colspan="2" class="text-center">No States</td>
                </tr>
                <tr v-for="i in states">
                  <td>{{ i.state }} ({{i.counta}})</td>
                  <th>{{ i.suma | toNaira }}</th>
                  <td>
                    <button
                      v-if="canReadClaims || canManageClaims"
                      @click.stop.prevent="stateHcps(i)"
                      class="btn btn-xs btn-secondary m-r-2"
                    >View Hcps</button>
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div v-else-if="itemTitle === 'institution'" class="col-sm-12">
        <div class="panel panel-inverse">
          <div class="panel-heading">
            <h4 class="panel-title">Institution Listings - {{pageTitle}}</h4>
          </div>
          <div class="panel-body">
            <div class="table-responsive">
              <table class="table table-striped m-b-0">
                <thead>
                <tr>
                  <th>Institution Name</th>
                  <th>Institution Code</th>
                  <th>Total Bill</th>
                  <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="!institutions.length">
                  <td colspan="4" class="text-center">No Institutions</td>
                </tr>
                <tr v-for="i in institutions" :key="i.id">
                  <td>{{ i.institution }}</td>
                  <td>{{ i.code }}</td>
                  <td>{{ i.suma | toNaira }}</td>
                  <td>
                    <button
                      v-if="canReadClaims || canManageClaims"
                      @click.stop.prevent="institutionTreatments(i)"
                      class="btn btn-xs btn-secondary m-r-2"
                    >View Treatments</button>
                    <button
                      @click.stop.prevent="myTreatments()"
                      class="btn btn-xs btn-secondary m-r-2"
                    >My Treatments</button>
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div v-else-if="itemTitle === 'hcp'" class="col-sm-12">
        <div class="panel panel-inverse">
          <div class="panel-heading">
            <h4 class="panel-title">{{ selectedTitle }}</h4>
          </div>
          <div class="panel-body">
            <a :href="backToState()" class="btn btn-sm btn-secondary">Back</a>
            <div class="table-responsive">
              <table class="table table-striped m-b-0">
                <thead>
                <tr>
                  <th>State </th>
                  <th>Hcp Name</th>
                  <th>Hcp Code</th>
                  <th>Total Bill</th>
                  <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="!localHcps.length">
                  <td colspan="4" class="text-center">No Hcps</td>
                </tr>
                <tr v-for="i in localHcps" :key="i.id">
                  <td>{{ i.state }}</td>
                  <td>{{ i.hcp }}</td>
                  <td>{{ i.hcp_code }}</td>
                  <td>{{ i.suma | toNaira }}</td>
                  <td>
                    <button
                      v-if="canReadClaims || canManageClaims"
                      @click.stop.prevent="hcpTreatments(i)"
                      class="btn btn-xs btn-secondary m-r-2"
                    >View Treatments</button>
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div v-else-if="itemTitle === 'treatment'" class="col-sm-12">
        <div class="panel panel-inverse">
          <div class="panel-heading">
            <h4 class="panel-title">{{ selectedTitle }}</h4>
          </div>
          <div class="panel-body">
            <button
              v-if="canReadClaims && selectedState ||  canManageClaims && selectedState"
              @click.stop.prevent="backToStateHcp(selectedState)"
              class="btn btn-xs btn-secondary m-r-2"
            >Back To State Hcps</button>
            <button
              v-if="canReadClaims && selectedInstitution ||  canManageClaims && selectedInstitution"
              @click.stop.prevent="backToInstitutions()"
              class="btn btn-xs btn-secondary m-r-2"
            >Back To Institutions</button>
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
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="!localTreatments.length">
                  <td colspan="9" class="text-center">No Treatments</td>
                </tr>
                <tr v-for="i in localTreatments" :key="i.id" style="font-size: 12px">
                  <td>{{ i.code }}</td>
                  <td>{{ i.user.last_name + ' ' + i.user.first_name + ' (' + i.user.verification_no + ')' }}</td>
                  <th>{{ i.bill | toNaira }}</th>
                  <td>{{ i.medical_officer }}</td>
                  <td>{{ i.diagnosis }}</td>
                  <td>{{ i.medical_condition }}</td>
                  <td>{{ i.medication_administered }}</td>
                  <td>{{ i.created_at }}</td>
                  <td>{{ i.paid == 1 ? 'Paid' : 'Not Paid' }}</td>
                  <td class="with-btn" nowrap>
                    <button
                      v-if="canManageClaims && i.paid === 0"
                      @click.stop.prevent="payClaim(i)"
                      class="btn btn-xs btn-secondary m-r-2"
                    >Pay</button>
                    <button
                      v-else-if="i.paid === 1"
                      @click.stop.prevent="paymentDetail(i)"
                      class="btn btn-xs btn-secondary m-r-2"
                    >Payment Detail</button>
                    <button
                      v-else
                      class="btn btn-xs btn-secondary m-r-2"
                      @click.stop.prevent
                    >No Action</button>
                  </td>
                </tr>
                </tbody>
              </table>
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
  import FilterMixin from '../mixins/FilterMixin';
  import PermissionMixin from '../mixins/PermissionMixin';

  export default {
    name: 'Claims',
    components: {
      Page,
      PageTitle
    },
    mixins: [AlertMixin, FilterMixin, PermissionMixin],
    props: {
      states: {
        type: Array,
        required: true
      },
      institutions: {
        type: Array,
        required: true
      },
      treatments: {
        type: Array,
        required: true
      },
      pageTitle: {
        type: String,
        required: true
      },
      itemTitleMain: {
        type: String
      },
      paidMain: {
        type: Number,
        required: true
      }
    },
    data() {
      return {
        canReadClaims: false,
        canManageClaims: false,
        localHcps: [],
        localTreatments: this.treatments ? this.treatments : [],
        selectedTitle: '',
        selectedState: null,
        paid: this.paidMain,
        selectedHcp: null,
        selectedInstitution: null,
        itemTitle: this.itemTitleMain,
        errors: ''
      };
    },
    computed: {

    },
    watch: {

    },
    mounted() {
      this.canReadClaims = this.hasPermission('claims:read');
      this.canManageClaims = this.hasPermission('claims:manage');
      if(this.treatments !== undefined && this.treatments.length >= 1){
        this.selectedTitle = `My Treatment Listings - ${this.pageTitle}`;
        this.itemTitle = 'treatment';
      }
    },
    methods: {
      stateHcps(i) {
        const url = this.paid === 0 ? `/state/${i.state_id}/hcps/claims/unpaid` : `/state/${i.state_id}/hcps/claims/paid`;
        if (i.state_id) {
          axios
            .get(url)
            .then(({ data: { hcps } }) => {
              this.localHcps = hcps;
              this.itemTitle = 'hcp';
              this.selectedState = i;
              this.selectedTitle = `${i.state} State Hcp Listings - ${this.pageTitle}`;
            });
        }
      },
      backToState() {
        const url = this.paid === 0 ? `/claims/unpaid` : `/claims/paid`;
        return url;
      },
      backToStateHcp(i) {
        this.stateHcps(i);
      },
      backToInstitutions() {
        this.selectedInstitution = null;
        this.itemTitle = 'institution';
      },
      hcpTreatments(i) {
        const url = this.paid === 0 ? `/hcp/${i.hcp_id}/treatments/claims/unpaid` : `/hcp/${i.hcp_id}/treatments/claims/paid`;
        if (i.hcp_id) {
          axios
            .get(url)
            .then(({ data: { treatments } }) => {
              this.localTreatments = treatments;
              this.itemTitle = 'treatment';
              this.selectedHcp = i;
              this.selectedTitle = `${i.hcp} Treatment Listings - ${this.pageTitle}`;
            });
        }
      },
      institutionTreatments(i) {
        const url = this.paid === 0 ? `/institution/${i.institution_id}/treatments/claims/unpaid` : `/institution/${i.institution_id}/treatments/claims/paid`;
        if (i.institution_id) {
          axios
            .get(url)
            .then(({ data: { treatments } }) => {
              this.localTreatments = treatments;
              this.itemTitle = 'treatment';
              this.selectedInstitution = i;
              this.selectedTitle = `${i.institution} Treatment Listings - ${this.pageTitle}`;
            });
        }
      },
      myTreatments() {
        const is_service = true;
        const url = `/my/treatments/claims/${is_service}`;
        axios
          .get(url)
          .then(({ data: { treatments } }) => {
            this.localTreatments = treatments;
            this.itemTitle = 'treatment';
            this.selectedTitle = `My Treatment Listings - ${this.pageTitle}`;
          });
      },
      payClaim(i) {
        alert('Payment Gateway not integrated!')
      },
      paymentDetail(i) {
        alert('Payment detail not yet ready!')
      }


    }
  }
</script>

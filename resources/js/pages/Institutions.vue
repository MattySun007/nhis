<template>
  <Page>
    <page-title title="Institutions"/>
    <div class="row">
      <div class="col-sm-12">
        <div class="panel panel-inverse">
          <div class="panel-heading">
            <h4 class="panel-title">Institutions</h4>
          </div>
          <div class="panel-body">
            <button
              class="btn btn-sm btn-secondary"
              @click.stop.prevent="view(null)"
            >Add institution</button>
            <div class="table-responsive">
              <table class="table table-striped m-b-0">
                <thead>
                  <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>RCC number</th>
                    <th>Country</th>
                    <th>Address</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="!localInstitutions.length">
                    <td colspan="9" class="text-center">No institutions</td>
                  </tr>
                  <tr v-for="i in localInstitutions" :key="i.id">
                    <td>{{ i.code }}</td>
                    <td>{{ i.name }}</td>
                    <td>{{ i.phone }}</td>
                    <td>{{ i.email }}</td>
                    <td>{{ i.rcc_number }}</td>
                    <td>{{ i.country_id }}</td>
                    <td>{{ i.address }}</td>
                    <td class="with-btn" nowrap>
                      <button
                        @click.stop.prevent="view(i)"
                        class="btn btn-sm btn-secondary m-r-2"
                      >View/Edit</button>
                      <button
                        v-if="i.active"
                        @click.stop.prevent="deactivate(i)"
                        class="btn btn-sm btn-white"
                      >Deactivate</button>
                      <button
                        v-else
                        @click.stop.prevent="activate(i)"
                        class="btn btn-sm btn-secondary"
                      >Activate</button>
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
              <form>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Name *</label>
                  <div class="col-md-6 col-sm-6">
                    <input type="text" class="form-control" v-model.trim="institution.name">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Code *</label>
                  <div class="col-md-6 col-sm-6">
                    <input type="text" class="form-control" v-model.trim="institution.code">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Phone *</label>
                  <div class="col-md-6 col-sm-6">
                    <input type="text" class="form-control" v-model.trim="institution.phone">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Email *</label>
                  <div class="col-md-6 col-sm-6">
                    <input type="email" class="form-control" v-model.trim="institution.email">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">RCC number</label>
                  <div class="col-md-6 col-sm-6">
                    <input type="text" class="form-control" v-model.trim="institution.rcc_number">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Address *</label>
                  <div class="col-md-6 col-sm-6">
                    <input type="text" class="form-control" v-model.trim="institution.address">
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
    </div>
  </Page>
</template>
<script>
import Page from './Page';
import PageTitle from '../components/header/PageTitle';
import AlertMixin from '../mixins/AlertMixin';

const defaultInstitution = {
  code: '',
  name: '',
  country_id: null,
  state_id: null,
  lga_id: null,
  town_id: null,
  active: 1,
  email: '',
  phone: '',
  address: '',
  rcc_number: ''
};

export default {
  name: 'Institutions',
  components: {
    Page,
    PageTitle
  },
  mixins: [AlertMixin],
  props: {
    institutions: {
      type: Array,
      required: true
    },
    countries: {
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
      localInstitutions: this.institutions,
      towns: [],
      institution: { ...defaultInstitution },
      selectedTitle: '',
      country: { country: '' },
      state: { name: '' },
      lga: { name: '' },
      town: { name: '' }
    };
  },
  computed: {
    filteredStates() {
      const { country } = this;
      return (!country.id) ? this.states : this.states.filter(({ country_id }) => country_id === country.id);
    },
    filteredLgas() {
      const { state } = this;
      return !state.id ? this.lgas : this.lgas.filter(({ state_id }) => state_id === state.id);
    },
    filteredTowns() {
      const { lga } = this;
      return !lga.id ? this.towns : this.towns.filter(({ lga_id }) => lga_id === lga.id);
    },
    formOk() {
      const {
        localInstitutions,
        filteredStates,
        filteredLgas,
        countries,
        institution: {
          id = 0,
          code,
          name,
          email,
          phone,
          country_id,
          state_id,
          lga_id,
          town_id,
          active,
          address,
          rcc_number
        }
      } = this;
      const insts = localInstitutions.filter(i => id !== i.id);

      const nameOk = !!name.length && insts.every(i => i.name !== name);
      const codeOk = !!code.length && insts.every(i => i.code !== code);
      const phoneOk = !!phone.length && insts.every(i => i.phone !== phone);
      const emailOk = !!email.length && insts.every(i => i.email !== email);
      const addressOk = !!address.length;
      const countryOk = countries.some(({ id }) => id === country_id);
      const stateOk = filteredStates.some(({ id }) => id === state_id);
      const lgaOk = filteredLgas.some(({ id }) => id === lga_id);

      return nameOk &&
        codeOk &&
        phoneOk &&
        emailOk &&
        addressOk &&
        countryOk &&
        stateOk &&
        lgaOk;
    }
  },
  watch: {
    country(country) {
      this.institution.country_id = country && country.id ? country.id : null;
    },
    state(state) {
      if (state && state.id) {
        this.institution.state_id = state.id;
        this.fetchTowns();
      } else {
        this.institution.state_id = null;
      }
    },
    lga(lga) {
      this.institution.lga_id = lga && lga.id ? lga.id : null;
    },
    town(town) {
      this.institution.town_id = town && town.id ? town.id : null;
    }
  },
  methods: {
    fetchTowns() {
      this.towns = [];

      axios
        .get(`/options/towns/${this.state.id}`)
        .then(({ data: { towns } }) => {
          this.towns = towns;
          if (!!this.institution.country_id) this.town = towns.find(({ id }) => id === this.institution.town_id);
        });
    },
    save() {
      const copy = { ...this.institution };
      copy.name = copy.name.toUpperCase();
      copy.code = copy.code.toUpperCase();
      copy.rcc_number = copy.rcc_number.toUpperCase();

      if (copy.id) {
        axios
        .put(`/institutions/${copy.id}`, copy)
        .then(({ data: { success, data, message = 'Could not update' } }) => {
          this.showToast(message, success);
          if (success) {
            $(this.$refs.instModal).modal('hide');
            this.localInstitutions = this.localInstitutions.map((institution) => {
              if (data.id === institution.id) return data;
              return institution;
            });
          }
        });
      } else {
        axios
        .post('/institutions', copy)
        .then(({ data: { success, data, message = 'Could not create' } }) => {
          this.showToast(message, success);
          if (success) {
            $(this.$refs.instModal).modal('hide');
            this.localInstitutions.push(data);
            this.institution = { ...defaultInstitution };
          }
        });
      }
    },
    view(inst) {
      if (inst) {
        this.selectedTitle = `Update ${inst.name}`;
        this.country = this.countries.find(({ id }) => id === inst.country_id) || {};
        this.state = this.states.find(({ id }) => id === inst.state_id) || {};
        this.lga = this.lgas.find(({ id }) => id === inst.lga_id) || {};
        this.institution = { ...inst };
      } else {
        this.selectedTitle = 'Create institution';
        this.state = {};
        this.lga = {};
        this.institution = { ...defaultInstitution };
      }
      $(this.$refs.instModal).modal('show');
    },
    deactivate(inst) {
      axios
      .put(`/institutions/${inst.id}`, { active: 0 })
      .then(({ data: { success, data } }) => {
        if (success) {
          this.showToast('Institution deactivated');
          this.localInstitutions = this.localInstitutions.map((institution) => {
            if (data.id === institution.id) return data;
            return institution;
          });
        }
      });
    },
    activate(inst) {
      axios
      .put(`/institutions/${inst.id}`, { active: 1 })
      .then(({ data: { success, data } }) => {
        if (success) {
          this.showToast('Institution activated');
          this.localInstitutions = this.localInstitutions.map((institution) => {
            if (data.id === institution.id) return data;
            return institution;
          });
        }
      });
    }
  }
}
</script>

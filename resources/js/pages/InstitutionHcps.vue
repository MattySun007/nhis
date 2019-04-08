<template>
  <Page>
    <page-title title="Institutions"/>
    <div class="row">
      <div class="col-sm-8">
        <div class="panel panel-inverse">
          <div class="panel-heading">
            <h4 class="panel-title">Institution Hcp - nothing shows here until you search for an institution</h4>
          </div>
          <div class="panel-body">
            <button
              class="btn btn-sm btn-secondary"
              @click.stop.prevent="searchInst()"
            >Search Institution</button>
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
                  <th>State</th>
                  <th>Lga</th>
                  <th>Town</th>
                  <th v-if="canCreate">Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="!localInstitutions.length ">
                  <td colspan="10" class="text-center">No institutions</td>
                </tr>
                <tr v-for="i in localInstitutions" :key="i.id">
                  <td>{{ i.code }}</td>
                  <td>{{ i.name }}</td>
                  <td>{{ i.phone }}</td>
                  <td>{{ i.email }}</td>
                  <td>{{ i.rcc_number }}</td>
                  <td>{{ i.country.country }}</td>
                  <td>{{ i.state.name }}</td>
                  <td>{{ i.lga.name }}</td>
                  <td>{{ i.town.name }}</td>
                  <td v-if="canCreate" class="with-btn" nowrap>
                    <button
                      @click.stop.prevent="viewHcp(i)"
                      class="btn btn-sm btn-secondary m-r-2"
                    >View Hcp</button>
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="col-sm-4">
        <div class="panel panel-inverse">
          <div class="panel-heading">
            <h4 class="panel-title">Hcps <span v-if="institution.name.length > 0"> for {{ institution.name }} </span></h4>
          </div>
          <div class="panel-body">
            <button
              @click.stop.prevent="addHcp(institution)"
              class="btn btn-sm btn-secondary m-r-2"
            >Add Hcp</button>
            <div class="table-responsive">
              <table class="table table-striped m-b-0">
                <thead>
                <tr>
                  <th>Code</th>
                  <th>Name</th>
                  <th>Type</th>
                  <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="!localHcps.length">
                  <td colspan="4" class="text-center">No hcps</td>
                </tr>
                <tr v-for="i in localHcps" :key="i.id">
                  <td>{{ i.hcp.code }}</td>
                  <td>{{ i.hcp.name }}</td>
                  <td>{{ i.hcp.hcp_type.hcp_type }}</td>
                  <td class="with-btn" nowrap>
                    <button
                      v-if="canDelete"
                      @click.stop.prevent="deleteInstitutionHcp(i.id)"
                      class="btn btn-sm btn-secondary m-r-2"
                    >Delete</button>
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div ref="modal" class="modal" tabindex="-1" role="dialog">
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
                <label class="control-label col-md-4 col-sm-4">Search with name, code, email, phone, Rcc number</label>
                <div class="col-md-6 col-sm-6">
                  <input type="text" class="form-control" v-model.trim="search_string">
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button
              :disabled="!formOk"
              @click.stop.prevent="searchResult()"
              type="button"
              class="btn btn-secondary"
            >Search</button>
          </div>
        </div>
      </div>
    </div>

      <div ref="modalAddHcp" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">{{ selectedTitle }} - <span v-if="institution.name.length > 0">{{ institution.name }} </span></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div v-if="errors.length" class="alert alert-warning" v-html="errors" />
              <form>
                <input type="hidden" v-model="institution.id">
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Select HCPs <span v-if="institution.name.length > 0">for {{ institution.name }} </span> *</label>
                  <div class="col-md-6 col-sm-6">
                    <v-select
                      autocomplete="on"
                      :multiple="true"
                      placeholder="Select HCP"
                      label="name"
                      :options="hcps"
                      v-model="hcp"
                    />
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button
                :disabled="!formAddHcpOk"
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
  import PermissionMixin from '../mixins/PermissionMixin';

  export default {
    name: 'InstitutionHcps',
    components: {
      Page,
      PageTitle
    },
    mixins: [AlertMixin, PermissionMixin],
    data() {
      return {
        canCreate: false,
        institution: {
          name: '',
          id: ''
        },
        localHcps: [],
        localInstitutions: {},
        search_string: '',
        selectedTitle: '',
        hcps: [],
        hcp: [],
        errors: ''
      };
    },
    computed: {
      formOk() {
        const {
          search_string,
        } = this;
        const search_stringOk = search_string.length > 0;
        return !!search_stringOk;
      },
      formAddHcpOk() {
        const {
          hcp,institution_id
        } = this;
        const hcpOk = hcp.length > 0;
        const institutionOk = institution_id !== '' && institution_id !== null;
        return !!hcpOk && !!institutionOk;
      }
    },
    watch: {

    },
    mounted() {
      this.canCreate = this.hasPermission('institution-hcp:create');
      this.canDelete = this.hasPermission('institution-hcp:delete');
    },
    methods: {
      addHcp(inst) {
        this.institution = inst;
        this.fetchHcps();
        this.selectedTitle = 'Add Hcp to Institution';
        $(this.$refs.modalAddHcp).modal('show');
      },
      viewHcp(inst) {
        this.institution = inst;
        this.selectedTitle = 'Hcp Listings';
        axios
          .get(`/institution-hcp/${inst.id}`)
          .then(({ data: { success, data, message  } }) => {
            this.showToast(message, success);
            if (success) {
              this.localHcps = data;
            }
          });
      },
      save() {
        const copy = [ ...this.hcp ];
        copy.forEach((hcp) => {
          axios
            .post(`/institution-hcp`, {institution_id: this.institution.id, hcp_id: hcp['id']})
            .then(({ data: { success, data, message = 'Could not create' } }) => {
              this.showToast(message, success);
              if (success) {
                $(this.$refs.modalAddHcp).modal('hide');
                this.localHcps = data;
                this.hcp = [];
              }
            }).catch(({ response: { data: { data } } }) => {
            this.errors = Object.values(data).flat().join('<br>');
          });
        });
      },
      fetchHcps() {
        this.hcps = [];
        axios
          .get(`/options/hcps/0/0`)
          .then(({ data: { hcps } }) => {
            this.hcps = hcps;
          });
      },
      searchInst() {
        this.selectedTitle = 'Search Institution';
        this.search_string = '';
        $(this.$refs.modal).modal('show');
      },
      deleteInstitutionHcp(id) {
        axios
          .delete(`/institution-hcp/${id}`)
          .then(({ data: { success, data } }) => {
            if (success) {
              this.showToast('Institution Hcp deleted');
              this.localHcps = data;
            }
          }).catch(({ response: { data } }) => console.log("error", data));
      },
      searchResult() {
        axios
          .get(`/institutions/search/${this.search_string}`)
          .then(({ data: { success, data, message } }) => {
            if (success) {
              $(this.$refs.modal).modal('hide');
              this.localInstitutions = data;
            }else{
              this.errors = message;
            }
          }).catch(({ response: { data: { data } } }) => {
          this.errors = Object.values(data).flat().join('<br>');
        });
      }
    }
  }
</script>

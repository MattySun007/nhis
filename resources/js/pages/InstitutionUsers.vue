<template>
  <Page>
    <page-title title="Institutions"/>
    <div class="row">

      <div v-if="selectedTitle === 'Search User'" class="col-sm-12">
        <div class="panel panel-inverse">
          <div class="panel-heading">
            <h4 class="panel-title">Select User/Contributor from list - user selected here will be bio-verified before enrollment</h4>
          </div>
          <div class="panel-body">
            <button
              class="btn btn-sm btn-secondary"
              @click.stop.prevent="searchUser()"
            >New Search</button>
            <div class="table-responsive">
              <table class="table table-striped m-b-0">
                <thead>
                <tr>
                  <th>Name</th>
                  <th>Verification_no</th>
                  <th>Phone</th>
                  <th>Email</th>
                  <th>Gender</th>
                  <th v-if="canCreate">Actions</th>
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
                  <td v-if="canCreate" class="with-btn" nowrap>
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

      <div class="col-sm-12">
        <div class="panel panel-inverse">
          <div class="panel-heading">
            <h4 class="panel-title">{{ institution_name }} - Institution users</h4>
          </div>
          <div class="panel-body">
            <button
              v-if="canCreate"
              class="btn btn-sm btn-secondary"
              @click.stop.prevent="view(null)"
            >Add Institution user</button>
            <button
              v-if="canCreate"
              class="btn btn-sm btn-secondary"
              @click.stop.prevent="searchUser()"
            >Select existing user</button>
            <a v-if="canViewInstitutions" :href="viewInstitutions()" class="btn btn-sm btn-secondary">Back to Institutions</a>
            <div class="table-responsive">
              <table class="table table-striped m-b-0">
                <thead>
                <tr>
                  <th>FirstName</th>
                  <th>LastName</th>
                  <th>Phone</th>
                  <th>Email</th>
                  <th>VerificationNo</th>
                  <th>Contribution Amt.</th>
                  <th>BloodGroup</th>
                  <th>Gender</th>
                  <th>Genotype</th>
                  <th>MaritalStatus</th>
                  <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="!localUsers.length">
                  <td colspan="11" class="text-center">No users</td>
                </tr>
                <tr v-for="i in localUsers" :key="i.id">
                  <td>{{ i.user.first_name }}</td>
                  <td>{{ i.user.last_name }}</td>
                  <td>{{ i.user.phone }}</td>
                  <td>{{ i.user.email }}</td>
                  <td>{{ i.user.verification_no }}</td>
                  <td>{{ i.user.contribution_amount }}</td>
                  <td>{{ i.user.blood_group.blood_group }}</td>
                  <td>{{ i.user.gender.gender }}</td>
                  <td>{{ i.user.genotype.genotype }}</td>
                  <td>{{ i.user.marital_status.marital_status }}</td>
                  <td class="with-btn" nowrap>
                    <button
                      v-if="canUpdate"
                      @click.stop.prevent="view(i)"
                      class="btn btn-sm btn-secondary m-r-2"
                    >View/Edit</button>
                    <button
                      v-if="canDelete"
                      @click.stop.prevent="deleteUser(i.user)"
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
              <h5 class="modal-title">{{ selectedTitle }} - {{ institution_name }}</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div v-if="errors.length" class="alert alert-warning" v-html="errors" />
              <form>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Verification No *</label>
                  <div class="col-md-6 col-sm-6">
                    <input readonly disabled type="text" class="form-control" v-model.trim="user.verification_no">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Last name *</label>
                  <div class="col-md-6 col-sm-6">
                    <input type="text" class="form-control" v-model.trim="user.last_name">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">First name *</label>
                  <div class="col-md-6 col-sm-6">
                    <input type="text" class="form-control" v-model.trim="user.first_name">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Middle name</label>
                  <div class="col-md-6 col-sm-6">
                    <input type="text" class="form-control" v-model.trim="user.middle_name">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Phone *</label>
                  <div class="col-md-6 col-sm-6">
                    <input type="text" class="form-control" v-model.trim="user.phone">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Email *</label>
                  <div class="col-md-6 col-sm-6">
                    <input type="email" class="form-control" v-model.trim="user.email">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Contribution Amount *</label>
                  <div class="col-md-6 col-sm-6">
                    <input type="number" class="form-control" v-model.trim="user.contribution_amount">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Colour </label>
                  <div class="col-md-6 col-sm-6">
                    <input type="text" class="form-control" v-model.trim="user.colour">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Height </label>
                  <div class="col-md-6 col-sm-6">
                    <input type="text" class="form-control" v-model.trim="user.height">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Gender *</label>
                  <div class="col-md-6 col-sm-6">
                    <v-select
                      placeholder="Select gender"
                      label="gender"
                      :options="genders"
                      v-model="gender"
                    />
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Marital status *</label>
                  <div class="col-md-6 col-sm-6">
                    <v-select
                      placeholder="Select marital status"
                      label="marital_status"
                      :options="maritalStatuses"
                      v-model="maritalStatus"
                    />
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Blood group</label>
                  <div class="col-md-6 col-sm-6">
                    <v-select
                      placeholder="Select blood group"
                      label="blood_group"
                      :options="bloodGroups"
                      v-model="bloodGroup"
                    />
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Genotype</label>
                  <div class="col-md-6 col-sm-6">
                    <v-select
                      placeholder="Select genotype"
                      label="genotype"
                      :options="genotypes"
                      v-model="genotype"
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

    </div>
  </Page>
</template>
<script>
  import Page from './Page';
  import PageTitle from '../components/header/PageTitle';
  import AlertMixin from '../mixins/AlertMixin';
  import PermissionMixin from '../mixins/PermissionMixin';
  import FilterMixin from "../mixins/FilterMixin";

  const defaultUser = {
    first_name: '',
    middle_name: '',
    last_name: '',
    verification_no: '',
    phone: '',
    contribution_amount: 0,
    email: '',
    colour: '',
    height: '',
    marital_status_id: null,
    genotype_id: null,
    blood_group_id: null,
    gender_id: null,
    status: 1
  };

  export default {
    name: 'InstitutionUsers',
    components: {
      Page,
      PageTitle
    },
    mixins: [AlertMixin, PermissionMixin, FilterMixin],
    props: {
      institution: {
        type: Object,
        required: true
      },
      users: {
        type: Array,
        required: true
      },
      genders: {
        type: Array,
        required: true
      },
      genotypes: {
        type: Array,
        required: true
      },
      maritalStatuses: {
        type: Array,
        required: true
      },
      bloodGroups: {
        type: Array,
        required: true
      }
    },
    data() {
      return {
        canCreate: false,
        canUpdate: false,
        canDelete: false,
        canViewInstitutions: false,
        localUsers: this.users,
        selectedTitle: '',
        searchUsers: [],
        search_string: '',
        gender: {},
        bloodGroup: {},
        maritalStatus: {},
        genotype: {},
        user: {},
        currentId: 0,
        institution_name: this.institution.name,
        institution_id: this.institution.id,
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
      formOk() {
        const {
          bloodGroups,
          genders,
          genotypes,
          maritalStatuses,
          user: {
            first_name,
            last_name,
            verification_no,
            contribution_amount,
            gender_id,
            marital_status_id,
            blood_group_id,
            genotype_id
          }
        } = this;

        const contOk = contribution_amount ? contribution_amount > 0 : true;
        const genderOk = gender_id ? genders.some(({ id }) => id === gender_id) : true;
        const bloodGroupOk = blood_group_id ? bloodGroups.some(({ id }) => id === blood_group_id) : true;
        const genotypeOk = genotype_id ? genotypes.some(({ id }) => id === genotype_id) : true;
        const maritalStatusOk = marital_status_id ? maritalStatuses.some(({ id }) => id === marital_status_id) : true;

        return !!first_name &&
          !!last_name &&
          !!contOk &&
          !!verification_no &&
          genderOk &&
          bloodGroupOk &&
          genotypeOk &&
          maritalStatusOk;
      }
    },
    watch: {
      gender(gender) {
        this.user.gender_id = gender && gender.id ? gender.id : null;
      },
      genotype(genotype) {
        this.user.genotype_id = genotype && genotype.id ? genotype.id : null;
      },
      maritalStatus(status) {
        this.user.marital_status_id = status && status.id ? status.id : null;
      },
      bloodGroup(group) {
        this.user.blood_group_id = group && group.id ? group.id : null;
      }
    },
    mounted() {
      this.canCreate = this.hasPermission('institution-users:create');
      this.canUpdate = this.hasPermission('institution-users:update');
      this.canViewInstitutions = this.hasPermission('institutions:read');
      this.canDelete = this.hasPermission('institution-users:delete');
    },
    methods: {
      save() {
        const copy = { ...this.user };
        copy.first_name = copy.first_name.toUpperCase();
        copy.middle_name = copy.middle_name.toUpperCase();
        copy.last_name = copy.last_name.toUpperCase();
        copy.institution_id = this.institution_id;

        if (copy.id) {
          axios
            .put(`/institution-users`, copy)
            .then(({ data: { success, data, message = 'Could not update' } }) => {
              this.showToast(message, success);
              if (success) {
                $(this.$refs.modal).modal('hide');
                this.localUsers = this.localUsers.map(i => data.id === i.user_id ? Object.assign(i, { user: data }) : i);
              }else{
                this.errors = message;
              }
            }).catch(({ response: { data: { data, message } } }) => {
              data.length <= 0 ? this.errors = message : this.errors = Object.values(data).flat().join('<br>');
          });
        } else {
          axios
            .post(`/institution-users`, copy)
            .then(({ data: { success, data, message = 'Could not create' } }) => {
              this.showToast(message, success);
              if (success) {
                $(this.$refs.modal).modal('hide');
                this.localUsers.push(data);
                this.user = { ...defaultUser };
                window.location.href = process.env.MIX_BIOMETRIC_IDENTIFY_START_URL;
              }else{
                this.errors = message;
              }
            }).catch(({ response: { data: { data, message } } }) => {
            data.length <= 0 ? this.errors = message : this.errors = Object.values(data).flat().join('<br>');
          });
        }
      },
      view(user) {
        if (user) {
          this.user = { ...user.user };
          this.currentId = user.id;
          this.selectedTitle = `Update user ${this.user.first_name} ${this.user.last_name}`;
          this.gender = this.genders.find(({ id }) => id === this.user.gender_id) || {};
          this.genotype = this.genotypes.find(({ id }) => id === this.user.genotype_id) || {};
          this.bloodGroup = this.bloodGroups.find(({ id }) => id === this.user.blood_group_id) || {};
          this.maritalStatus = this.maritalStatuses.find(({ id }) => id === this.user.marital_status_id) || {};
        } else {
          this.currentId = 0;
          this.selectedTitle = 'Create user';
          this.user = { ...defaultUser };
          this.gender = {};
          this.genotype = {};
          this.bloodGroup = {};
          this.maritalStatus = {};
          axios
            .get(`/codes/user`)
            .then(({ data: { data: { verification_no } } }) => {
              this.user.verification_no = verification_no;
            });
        }
        $(this.$refs.modal).modal('show');
      },
      deleteUser(user) {
        axios
          .delete(`/institution-users/${user.id}`)
          .then(({ data: { success, data } }) => {
            if (success) {
              this.showToast('Institution user deleted');
              this.localUsers = this.localUsers.filter(u => u.user_id !== user.id);
            }
          }).catch(({ response: { data: { data, message } } }) => {
          data.length <= 0 ? this.errors = message : this.errors = Object.values(data).flat().join('<br>');
        });
      },
      viewInstitutions() {
        return `/institutions`;
      },


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
      verifyUser(i) {
        let url_string = JSON.stringify({user_id: i.id, last_url: window.location.href, action: 'add_institution_user', institution_id: this.institution_id});
        window.location.href = process.env.MIX_BIOMETRIC_VERIFY_START_URL + '/' + this.b64EncodeUnicode(url_string);
      },
      searchUser() {
        this.selectedTitle = 'Search User';
        this.search_string = '';
        $(this.$refs.modalSearch).modal('show');
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
      }


    }
  }
</script>

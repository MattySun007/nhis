<template>
  <Page>
    <page-title title="HCPs"/>
    <div class="row">
      <div class="col-sm-12">
        <div class="panel panel-inverse">
          <div class="panel-heading">
            <h4 class="panel-title">HCP users</h4>
          </div>
          <div class="panel-body">
            <button
              v-if="canCreate"
              class="btn btn-sm btn-secondary"
              @click.stop.prevent="view(null)"
            >Add HCP user</button>
            <div class="table-responsive">
              <table class="table table-striped m-b-0">
                <thead>
                  <tr>
                    <th>First name</th>
                    <th>Last name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="!localUsers.length">
                    <td colspan="5" class="text-center">No users</td>
                  </tr>
                  <tr v-for="i in localUsers" :key="i.id">
                    <td>{{ i.user.first_name }}</td>
                    <td>{{ i.user.last_name }}</td>
                    <td>{{ i.user.phone }}</td>
                    <td>{{ i.user.email }}</td>
                    <td class="with-btn" nowrap>
                      <button
                        v-if="canUpdate"
                        @click.stop.prevent="view(i)"
                        class="btn btn-sm btn-secondary m-r-2"
                      >View/Edit</button>
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
              <form>
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
    </div>
  </Page>
</template>
<script>
import Page from './Page';
import PageTitle from '../components/header/PageTitle';
import AlertMixin from '../mixins/AlertMixin';
import PermissionMixin from '../mixins/PermissionMixin';

const defaultUser = {
  first_name: '',
  middle_name: '',
  last_name: '',
  phone: '',
  email: '',
  marital_status_id: null,
  genotype_id: null,
  blood_group_id: null,
  gender_id: null,
  status: 1
};

export default {
  name: 'HcpUsers',
  components: {
    Page,
    PageTitle
  },
  mixins: [AlertMixin, PermissionMixin],
  props: {
    hcp: {
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
      localUsers: this.users,
      selectedTitle: '',
      gender: {},
      bloodGroup: {},
      maritalStatus: {},
      genotype: {},
      user: {},
      currentId: 0
    };
  },
  computed: {
    formOk() {
      const {
        bloodGroups,
        genders,
        genotypes,
        maritalStatuses,
        user: {
          first_name,
          last_name,
          gender_id,
          marital_status_id,
          blood_group_id,
          genotype_id
        }
      } = this;

      const genderOk = gender_id ? genders.some(({ id }) => id === gender_id) : true;
      const bloodGroupOk = blood_group_id ? bloodGroups.some(({ id }) => id === blood_group_id) : true;
      const genotypeOk = genotype_id ? genotypes.some(({ id }) => id === genotype_id) : true;
      const maritalStatusOk = marital_status_id ? maritalStatuses.some(({ id }) => id === marital_status_id) : true;

      return !!first_name &&
        !!last_name &&
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
    this.canCreate = this.hasPermission('hcp-users:create');
    this.canUpdate = this.hasPermission('hcp-users:update');
  },
  methods: {
    save() {
      const copy = { ...this.user };
      copy.first_name = copy.first_name.toUpperCase();
      copy.middle_name = copy.middle_name.toUpperCase();
      copy.last_name = copy.last_name.toUpperCase();

      if (copy.id) {
        axios
        .put(`/hcps/${this.hcp.id}/users/${this.currentId}`, copy)
        .then(({ data: { success, data, message = 'Could not update' } }) => {
          this.showToast(message, success);
          if (success) {
            $(this.$refs.modal).modal('hide');
            this.localUsers = this.localUsers.map((user) => {
              if (data.id === user.id) return data;
              return user;
            });
          }
        });
      } else {
        axios
        .post(`/hcps/${this.hcp.id}/users`, copy)
        .then(({ data: { success, data, message = 'Could not create' } }) => {
          this.showToast(message, success);
          if (success) {
            $(this.$refs.modal).modal('hide');
            this.localUsers.push(data);
            this.user = { ...defaultUser };
          }
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
      }
      $(this.$refs.modal).modal('show');
    },
    hcpUsersLink({ id }) {
      return `/hcps/${id}/users`;
    }
  }
}
</script>

<template>
  <Page>
    <page-title title="Contributor Adoption"/>
    <div class="row">
      <div class="col-sm-6">
        <div class="panel panel-inverse">
          <div class="panel-heading">
            <h4 class="panel-title">Contributor Adoption - no user shows here until searched</h4>
          </div>
          <div class="panel-body">
            <button
              class="btn btn-sm btn-secondary"
              @click.stop.prevent="searchUser()"
            >Search Contributor</button>
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
                <tr v-if="!localUsers.length">
                  <td colspan="6" class="text-center">No users</td>
                </tr>
                <tr v-for="i in localUsers" :key="i.id">
                  <td>{{ i.last_name + ' ' + i.first_name + ' ' + i.middle_name }}</td>
                  <td>{{ i.verification_no }}</td>
                  <td>{{ i.phone }}</td>
                  <td>{{ i.email }}</td>
                  <td>{{ i.gender ? i.gender.gender : '' }}</td>
                  <td v-if="canCreate" class="with-btn" nowrap>
                    <button
                      @click.stop.prevent="adopt(i)"
                      class="btn btn-sm btn-secondary m-r-2"
                    >Adopt</button>
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="col-sm-6">
        <div class="panel panel-inverse">
          <div class="panel-heading">
            <h4 class="panel-title">Adoptees </h4>
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
                  <th>Gender</th>
                  <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="!localAdoptees.length">
                  <td colspan="6" class="text-center">No adoptees</td>
                </tr>
                <tr v-for="i in localAdoptees" :key="i.id">
                  <td>{{ i.adoptee_details.last_name + ' ' + i.adoptee_details.first_name + ' ' + i.adoptee_details.middle_name }}</td>
                  <td>{{ i.adoptee_details.verification_no }}</td>
                  <td>{{ i.adoptee_details.phone }}</td>
                  <td>{{ i.adoptee_details.email }}</td>
                  <td>{{ i.adoptee_details.gender.gender }}</td>
                  <td class="with-btn" nowrap>
                    <button
                      v-if="canDelete"
                      @click.stop.prevent="deleteAdoptee(i.id)"
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
                <label class="control-label col-md-4 col-sm-4">Search with name, verification_no, email, phone</label>
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

    </div>
  </Page>
</template>
<script>
  import Page from './Page';
  import PageTitle from '../components/header/PageTitle';
  import AlertMixin from '../mixins/AlertMixin';
  import PermissionMixin from '../mixins/PermissionMixin';

  export default {
    name: 'Adoption',
    props: {
      adoptees: {
        type: Array,
        required: true
      },
      user_id: {
        type: Number
      }
    },
    components: {
      Page,
      PageTitle
    },
    mixins: [AlertMixin, PermissionMixin],
    data() {
      return {
        canCreate: false,
        canDelete: false,
        localUsers: [],
        localAdoptees: this.adoptees,
        search_string: '',
        selectedTitle: '',
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
      formAddUserOk() {
        const {
          user
        } = this;
        const userOk = user.length > 0;
        return !!userOk;
      }
    },
    watch: {

    },
    mounted() {
      this.canCreate = this.hasPermission('adoption:create');
      this.canDelete = this.hasPermission('adoption:delete');
    },
    methods: {
      adopt(i) {
        console.info('user_id',this.user_id);
        axios
          .post(`/adoption`, {user_id: this.user_id, adoptee_id: i.id})
          .then(({ data: { success, data, message = 'Could not create' } }) => {
            this.showToast(message, success);
            if (success) {
              this.localAdoptees = data;
              this.localUsers = this.localUsers.filter(({ id }) => id !== i.id);
            }
          })
          .catch(({ response: { data: { data } } }) => {
          this.errors = Object.values(data).flat().join('<br>');
        });
      },
      /*fetchUsers() {
        this.localUsers = [];
        axios
          .get(`/options/users/0/0`)
          .then(({ data: { users } }) => {
            this.localUsers = users;
          });
      },*/
      searchUser() {
        this.selectedTitle = 'Search User';
        this.search_string = '';
        $(this.$refs.modal).modal('show');
      },
      deleteAdoptee(id) {
        axios
          .delete(`/adoption/${id}`)
          .then(({ data: { success, data } }) => {
            if (success) {
              this.showToast('Adoptee deleted');
              this.localAdoptees = data;
              this.searchResult();
            }
          }).catch(({ response: { data } }) => console.log("error", data));
      },
      searchResult() {
        const copy = {};
        copy.str = this.search_string;
        copy.is_adoptees = 1;
        axios
          .post(`/users/search`,copy)
          .then(({ data: { success, data, message } }) => {
            if (success) {
              $(this.$refs.modal).modal('hide');
              if (!data.length) {
                this.localUsers = [];
                Object.keys(data).forEach(k => {
                  this.localUsers.push(data[k]);
                });
              } else {
                this.localUsers = data;
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

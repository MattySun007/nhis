<template>
  <Page>
    <page-title title="Manage Permissions"/>
    <div class="row">
      <div class="col-sm-6">
        <div class="panel panel-inverse">
          <div class="panel-heading">
            <h4 class="panel-title">User/Contributor Permissions - no user shows here until searched</h4>
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
                  <th v-if="canManage">Actions</th>
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
                  <td v-if="canManage" class="with-btn" nowrap>
                    <button
                      :disabled="selectedUserId > 0"
                      @click.stop.prevent="selectUser(i)"
                      class="btn btn-sm btn-secondary m-r-2"
                    >Select User</button>
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="col-sm-3" v-if="isUserSelected">
        <div class="panel panel-inverse">
          <div class="panel-heading">
            <h4 class="panel-title">Assigned Permissions for - {{ user_text }}</h4>
          </div>
          <div v-if="errors.length" class="alert alert-warning" v-html="errors"></div>
          <div class="panel-body">
            <div class="table-responsive">
              <table class="table table-striped m-b-0">
                <thead>
                <tr>
                  <th>S/n</th>
                  <th>Name</th>
                  <th>Select</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="!userPerms.length">
                  <td colspan="3" class="text-center">No Permissions</td>
                </tr>
                <tr v-for="(i, j) in userPerms" :key="i.name" >
                  <td>{{ j+1 }}</td>
                  <td>{{ i.name }}</td>
                  <td class="with-btn" nowrap>
                    <input type="checkbox" class="form-control" v-model="selectedUserPerms" :value="i.name">
                  </td>
                </tr>
                <tr>
                  <td colspan="3" class="with-btn" nowrap>
                    <button
                      :disabled="!formDeletePermOk"
                      v-if="canManage"
                      @click="deletePerm()"
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

      <div class="col-sm-3">
        <div class="panel panel-inverse">
          <div class="panel-heading">
            <h4 class="panel-title">Available Permissions </h4>
          </div>
          <div v-if="errors.length" class="alert alert-warning" v-html="errors"></div>
          <div class="panel-body">
            <div class="table-responsive">
              <table class="table table-striped m-b-0">
                <thead>
                <tr>
                  <th>S/n</th>
                  <th>Name</th>
                  <th>Select</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="!localPerms.length">
                  <td colspan="3" class="text-center">No Permissions</td>
                </tr>
                <tr v-for="(i, j) in localPerms" :key="i.name" >
                  <td>{{ j+1 }}</td>
                  <td>{{ i.name }}</td>
                  <td class="with-btn" nowrap>
                    <input type="checkbox" class="form-control" v-model="selectedPerms" :value="i.name">
                  </td>
                </tr>
                <tr>
                  <td colspan="3" class="with-btn" nowrap>
                    <button
                      :disabled="!formAddPermOk"
                      v-if="canManage"
                      @click="addPerm()"
                      class="btn btn-sm btn-secondary m-r-2"
                    >Add</button>
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
            <div v-if="errors.length" class="alert alert-warning" v-html="errors"></div>
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
    name: 'Permissions',
    props: {
      perms: {
        type: Array,
        required: true
      },
      item_title: {
        type: String,
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
        canManage: false,
        localPerms: this.perms,
        localUsers: [],
        userPerms: [],
        selectedPerms: [],
        selectedUserPerms: [],
        search_string: '',
        selectedUserId: 0,
        selectedTitle: '',
        user_text: '',
        errors: '',
        permAdded: true,
        permDeleted: true
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
      formAddPermOk: {
        get: function(){
          const {
            selectedPerms,
            selectedUserId
          } = this;
          const selectedPermsOk =  Object.values(selectedPerms).length >= 1;
          const selectedUserIdOk = selectedUserId > 0;
          return  !!selectedUserIdOk && !!selectedPermsOk;
        },
        set: function(val){
          return val;
        }
      },
      formDeletePermOk: {
        get: function(){
          const {
            selectedUserPerms,
            selectedUserId
          } = this;
          const selectedUserPermsOk =  Object.values(selectedUserPerms).length >= 1;
          const selectedUserIdOk = selectedUserId > 0;
          return  !!selectedUserIdOk && !!selectedUserPermsOk;
        },
        set: function(val){
          return val;
        }
      },
      isUserSelected: {
        get: function(){
          const {
            selectedUserId
          } = this;
          const selectedUserIdOk = selectedUserId > 0;
          return  !!selectedUserIdOk;
        },
        set: function(val){
          return val;
        }
      }
    },
    watch: {
      selectedUserId: {
        handler() {
          this.permAdded = false;
          this.permDeleted = false;
          this.formAddPermOk = this.selectedUserId > 0 && Object.values(this.selectedPerms).length >= 1;
        }
      },
      selectedPerms: {
        handler() {
          this.permAdded = false;
          this.formAddPermOk = this.selectedUserId > 0 && Object.values(this.selectedPerms).length >= 1;
        }
      },
      selectedUserPerms: {
        handler() {
          this.permDeleted = false;
          this.formDeletePermOk = this.selectedUserId > 0 && Object.values(this.selectedUserPerms).length >= 1;
        }
      }
    },
    mounted() {
      this.canManage = this.hasPermission('permissions:manage');
    },
    methods: {
      addPerm() {
        const copy = [ ...this.selectedPerms ];
        let count = 0;
        let errors = [];
        const requests = copy.map((perm) =>
          axios
            .post(`/user-permissions-add`, {user_id: this.selectedUserId, permissions: perm})
            .then(({ data: { success, user_permissions, message } }) => {
              if (success) {
                count++;
                this.userPerms = user_permissions;
              }else{
                errors.push(message);
              }
            }).catch(({ response: { data: { message } } }) => {
            errors.push(message);
          }));
        Promise.all(requests).then(() => {
          this.selectedPerms = [];
          this.errors = errors.join();
          console.log('error',this.errors);
          if(count > 0){
            this.showToast(count +' permissions added for '+ this.user_text, true);
          }else{
            this.showToast('No permissions added for '+ this.user_text + ', possible duplicate entry!', true);
          }
        });
      },
      selectUser(i) {
        this.user_text = 'User: [ '+ i.last_name + ' ' + i.first_name + ' ] selected';
        this.selectedUserId = i.id;
        let params = Object.assign({}, {user_id: this.selectedUserId});
        axios.post(`/user-permissions-list`, params)
          .then(({ data: { user_permissions } }) => {
            this.userPerms = user_permissions;
          });
        this.showToast(this.user_text, true);
      },
      searchUser() {
        this.selectedTitle = 'Search User/Contributor';
        this.search_string = '';
        $(this.$refs.modal).modal('show');
      },
      deletePerm() {
        const copy = [ ...this.selectedUserPerms ];
        let count = 0;

        const requests = copy.map((perm) =>
          axios
            .post(`/user-permissions-delete`, {user_id: this.selectedUserId, permissions: perm})
            .then(({ data: { success, user_permissions, message } }) => {
              if (success) {
                count++;
                this.userPerms = user_permissions;
              }else{
              }
            }).catch(({ response: { data: { message } } }) => {
          }));
        Promise.all(requests).then(() => {
          this.selectedUserPerms = [];
          if(count > 0){
            this.showToast(count +' permissions removed for '+ this.user_text, true);
          }else{
            this.showToast('No permissions removed for '+ this.user_text, true);
          }
        });
      },
      searchResult() {
        const copy = {};
        copy.str = this.search_string;
        copy.by_user_type = 1;
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
          }).catch(({ response: { data } }) => {
            console.log(data);
          this.errors = Object.values(data).flat().join('<br>');
        });
      }
    }
  }
</script>

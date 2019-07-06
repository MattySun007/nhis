<template>
  <Page>
    <page-title title="User Biometrics"/>

    <div class="row" v-if="success">

      <div class="col-sm-12" v-if="data.item === 'completed'">
        <div class="panel panel-inverse">
          <div class="panel-heading">
            <h4 class="panel-title">Biometric completed</h4>
          </div>
          <div class="panel-body">

            <div class="table-responsive" v-if="!data.length">
              <table class="table table-striped m-b-0">
                <tr>
                  <th>Picture</th>
                  <td><img height="400px" width="400px" :src="data.img_url" ></td>
                </tr>
                <tr>
                  <th>Name</th>
                  <td>{{ data.user.last_name + ' '+ data.user.first_name}}</td>
                </tr>
                <tr>
                  <th>Verification No.</th>
                  <td>{{ data.user.verification_no }}</td>
                </tr>
                <tr>
                  <th>Biometric Date</th>
                  <td>{{ data.user.user_biometric.created_at }}</td>
                </tr>
                <tr>
                  <th>Biometric Status</th>
                  <td>{{ data.user.user_biometric.biometric_status === 1 ? 'Done' : 'Not done' }}</td>
                </tr>
                <tr>
                  <th>&nbsp;</th>
                  <td><a :href="exitBioLink()" class="btn btn-sm btn-secondary">Continue</a></td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="col-sm-12" v-if="data.item === 'exists'">
        <div class="panel panel-inverse">
          <div class="panel-heading">
            <h4 class="panel-title">Biometric completed - {{ message }}</h4>
          </div>
          <div class="panel-body">

            <div class="table-responsive" v-if="!data.length">
              <table class="table table-striped m-b-0">
                <tr>
                  <th>Current Picture</th>
                  <td><img height="400px" width="400px" :src="data.img_url" ></td>
                </tr>
                <tr>
                  <th>Name</th>
                  <td>{{ data.user.last_name + ' '+ data.user.first_name}}</td>
                </tr>
                <tr>
                  <th>Verification No.</th>
                  <td>{{ data.user.verification_no }}</td>
                </tr>
                <tr>
                  <th>Biometric Date</th>
                  <td>{{ data.user.user_biometric.created_at }}</td>
                </tr>
                <tr>
                  <th>Biometric Status</th>
                  <td>{{ data.user.user_biometric.biometric_status === 1 ? 'Done' : 'Not done' }}</td>
                </tr>
                <tr>
                  <th>&nbsp;</th>
                  <td><a :href="exitBioLink()" class="btn btn-sm btn-secondary">Continue</a></td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="col-sm-12" v-else-if="data.item === 'duplicate'">
        <div class="panel panel-inverse">
          <div class="panel-heading">
            <h4 class="panel-title">Biometric duplicate found - confirming the current image will override the former image captured</h4>
          </div>
          <div class="panel-body">

            <div class="row">
              <div class="col-sm-6">
                <div class="table-responsive" v-if="!data.length">
                  <table class="table table-striped m-b-0">
                    <tr>
                      <th>Old Picture</th>
                      <td><img height="400px" width="400px" :src="data.img_url" ></td>
                    </tr>
                  </table>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="table-responsive" v-if="!data.length">
                  <table class="table table-striped m-b-0">
                    <tr>
                      <th>Current Picture</th>
                      <td><img height="400px" width="400px" :src="data.data_url" ></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>
                        <button @click.stop.prevent="confirm()" class="btn btn-sm btn-secondary m-r-2">Confirm</button>
                      </td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>

    </div> <!-- end row -->

    <div class="row" v-else>

      <div class="col-sm-12">
        <div class="panel panel-inverse">
          <div class="panel-heading">
            <h4 class="panel-title">Biometric Error!</h4>
          </div>
          <div class="panel-body">

            <div class="table-responsive">
              <table class="table table-striped m-b-0">
                <tr>
                  <th><p>{{ message }}</p></th>
                </tr>
                <tr>
                  <td v-html="data"></td>
                </tr>
                <tr>
                  <td><a :href="startBioLink()" class="btn btn-sm btn-secondary">Re-start Biometrics</a></td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div> <!-- end row -->

    <!-- begin modal one -->
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



  </Page>
</template>
<script>
  import Page from './Page';
  import PageTitle from '../components/header/PageTitle';
  import AlertMixin from '../mixins/AlertMixin';
  import PermissionMixin from '../mixins/PermissionMixin';

  export default {
    name: 'Biometric',
    props: {
      data: {
        type: Object,
        required: true
      },
      success: {
        type: String,
        required: true
      },
      url: {
        type: String,
        required: true
      },
      message: {
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
        errors: ''
      };
    },
    computed: {

    },
    watch: {

    },
    mounted() {

    },
    methods: {
      addPerm() {

      },
      startBioLink() {
        return this.url;
      },
      exitBioLink() {
        return this.url;
      }
    }
  }
</script>

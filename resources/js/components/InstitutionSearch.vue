<template>
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
</template>
<script>

import AlertMixin from '../mixins/AlertMixin';
import PermissionMixin from '../mixins/PermissionMixin';

export default {
  name: 'InstitutionSearch',
  mixins: [AlertMixin, PermissionMixin],
  props: {
    selectedTitle: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      selectedTitle: '',
      errors: ''
    };
  },
  computed: {

  },
  watch: {

  },
  mounted() {
    this.canCreate = this.hasPermission('institutions:create');
    this.canUpdate = this.hasPermission('institutions:update');
  },
  methods: {
    searchInst() {
      this.selectedTitle = 'Search Institution';
      this.search_string = '';
      $(this.$refs.modal).modal('show');
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

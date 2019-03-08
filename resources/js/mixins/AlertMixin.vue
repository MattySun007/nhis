<script>
export default {
  name: 'AlertMixin',
  methods: {
    showToast(title, success = true) {
      this.$swal({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000,
        type: success ? 'info' : 'error',
        title,
        text: ''
      });
    },
    showConfirm(title, html) {
      return this.$swal({
        title,
        html,
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, go ahead'
      })
        .then(({ value }) => value);
    }
  }
};
</script>

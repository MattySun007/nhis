<script>
export default {
  name: 'PermissionMixin',
  data() {
    return {
      userPermissions: []
    };
  },
  mounted() {
    const perms = document.querySelector('meta[name="permissions"]').content;
    if (typeof perms === 'string') this.userPermissions = JSON.parse(perms);
  },
  methods: {
    hasPermission(perm) {
      return this.userPermissions.some(({ name }) => name === perm);
    }
  }
};
</script>

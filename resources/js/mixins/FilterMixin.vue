<script>
export default {
  name: 'FilterMixin',
  filters: {
    capitalize(value) {
      return !value ? '' : value.toString().charAt(0).toUpperCase() + value.slice(1);
    },
    toDate(date) {
      return moment(date).format('ddd, MMM Do YYYY');
    },
    toNaira(amount) {
      const opts = { style: 'currency', currency: 'NGN', minimumFractionDigits: 2 };
      return new Intl.NumberFormat('en-NG', opts).format(amount);
    }
  },
  methods: {
    applyFilter(filterName, val) {
      const self = this.$options.mixins.find(({ name }) => 'FilterMixin' === name);
      return self && self.filters[filterName] ? self.filters[filterName](val) : val;
    }
  }
}
</script>

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
    toNaira(amount, currency = 'NGN') {
      let locale;
      if (currency === 'NGN') locale = 'en-NG';
      if (currency === 'USD') locale = 'en-US';
      if (currency === 'GBP') locale = 'en-GB';

      const opts = { style: 'currency', currency, minimumFractionDigits: 2 };
      return new Intl.NumberFormat(locale, opts).format(amount);
    },
    toThousand(amount) {
      const opts = { minimumFractionDigits: 2 };
      return new Intl.NumberFormat('en-US', opts).format(amount);
    },
    toThousandNoDecimal(amount) {
      const opts = { minimumFractionDigits: 0 };
      return new Intl.NumberFormat('en-US', opts).format(amount);
    },
    formatPrice(value, dec, cur ) {
      dec = dec || 0
      if (value === null) {
        return 0
      }
      return cur + ' ' + (value/1).toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")
    },
  },
  methods: {
    applyFilter(filterName, val) {
      const self = this.$options.mixins.find(({ name }) => 'FilterMixin' === name);
      return self && self.filters[filterName] ? self.filters[filterName](val) : val;
    }
  }
}
</script>

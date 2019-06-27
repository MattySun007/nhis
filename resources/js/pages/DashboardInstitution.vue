<template>
  <Page>
    <!-- begin page-header -->
        <page-title title="Dashboard Institution" subtitle="...Institution user dashboard"/>
    <!-- end page-header -->

    <!-- begin row -->
    <div class="row">
      <!-- begin col-3 -->
      <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-red">
          <div class="stats-icon">
            <i class="fa fa-times"></i>
          </div>
          <div class="stats-info">
            <h4>TOTAL UNPAID CLAIMS</h4>
            <p>{{ this.unpaid_claims | toThousandNoDecimal }}</p>
          </div>
          <div class="stats-link">
            <a href="javascript:;">
              {{ this.unpaid_claims_text }}
              <i class="fa fa-arrow-alt-circle-right"></i>
            </a>
          </div>
        </div>
      </div>
      <!-- end col-3 -->
      <!-- begin col-3 -->
      <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-green-lighter">
          <div class="stats-icon">
            <i class="fa fa-check"></i>
          </div>
          <div class="stats-info">
            <h4>TOTAL PAID CLAIMS</h4>
            <p>{{ this.paid_claims | toThousandNoDecimal }}</p>
          </div>
          <div class="stats-link">
            <a href="javascript:;">
              {{ this.paid_claims_text }}
              <i class="fa fa-arrow-alt-circle-right"></i>
            </a>
          </div>
        </div>
      </div>
      <!-- end col-3 -->
      <!-- begin col-3 -->
      <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-orange">
          <div class="stats-icon">
            <i class="fa fa-hospital"></i>
          </div>
          <div class="stats-info">
            <h4>TOTAL MONTHLY CONTRIBUTION</h4>
            <p>{{ data.contribution_sum | toThousand }}</p>
          </div>
          <div class="stats-link">
            <a href="javascript:;">
              View Detail
              <i class="fa fa-arrow-alt-circle-right"></i>
            </a>
          </div>
        </div>
      </div>
      <!-- end col-3 -->
      <!-- begin col-3 -->
      <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-black-lighter">
          <div class="stats-icon">
            <i class="fa fa-user"></i>
          </div>
          <div class="stats-info">
            <h4>TOTAL ADOPTEES</h4>
            <p>{{ data.adoptee_count | toThousandNoDecimal }}</p>
          </div>
          <div class="stats-link">
            <a href="javascript:;">
              View Detail
              <i class="fa fa-arrow-alt-circle-right"></i>
            </a>
          </div>
        </div>
      </div>
      <!-- end col-3 -->
    </div>
    <!-- end row -->

  </Page>
</template>

<script>
import Page from './Page';
import LineChart from '../components/vue-chartjs/LineChart';
import DoughnutChart from '../components/vue-chartjs/DoughnutChart';
import PageTitle from '../components/header/PageTitle';
import AlertMixin from '../mixins/AlertMixin';
import FilterMixin from '../mixins/FilterMixin';
import PermissionMixin from '../mixins/PermissionMixin';

export default {
	name: 'Dashboard',
	components: {
    Page,
    PageTitle,
		LineChart,
		DoughnutChart
	},
  mixins: [AlertMixin,PermissionMixin,FilterMixin],
  props: {
    data: {
      type: Object,
      required: true
    }
  },
	data() {
		return {
      canManageClaims: false,
      paid_claims: 0,
      unpaid_claims: 0,
      paid_claims_text: '',
      unpaid_claims_text: '',
      subtitle: this.data.item_title
		}
	},
  mounted() {
    this.canManageClaims = this.hasPermission('claims:manage');
    this.paid_claims = this.canManageClaims ? this.data.total_paid_claims_count : this.data.personal_paid_claims_count;
    this.unpaid_claims = this.canManageClaims ? this.data.total_unpaid_claims_count : this.data.personal_unpaid_claims_count;
    this.paid_claims_text = this.canManageClaims ? this.data.paid_claims_text : this.data.personal_paid_claims_text;
    this.unpaid_claims_text = this.canManageClaims ? this.data.unpaid_claims_text : this.data.personal_unpaid_claims_text;
  },
  methods: {
    logData() {
      console.log('data', this.data);
    }
  }
}
</script>

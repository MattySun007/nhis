<template>
  <Page>
    <!-- begin page-header -->
    <page-title title="Dashboard Agency" subtitle="...Agency user dashboard"/>
    <!-- end page-header -->

    <!-- begin row -->
    <div class="row">
      <!-- begin col-3 -->
      <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-red">
          <div class="stats-icon">
            <i class="fa fa-users"></i>
          </div>
          <div class="stats-info">
            <h4>TOTAL USERS</h4>
            <p>{{ data.user_count | toThousandNoDecimal }}</p>
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
        <div class="widget widget-stats bg-orange">
          <div class="stats-icon">
            <i class="fa fa-hospital"></i>
          </div>
          <div class="stats-info">
            <h4>TOTAL HCPS</h4>
            <p>{{ data.hcp_count | toThousandNoDecimal }}</p>
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
        <div class="widget widget-stats bg-grey-darker">
          <div class="stats-icon">
            <i class="fa fa-tasks"></i>
          </div>
          <div class="stats-info">
            <h4>TOTAL INSTITUTIONS</h4>
            <p>{{ data.institution_count | toThousandNoDecimal }}</p>
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
      subtitle: this.data.item_title
		}
	},
  methods: {
    logData() {
      console.log('data', this.data);
    }
  }
}
</script>

<template>
  <div>
    <!-- begin #header -->
    <div id="header" class="header navbar-default">
      <!-- begin navbar-header -->
      <div class="navbar-header">
        <button
          type="button"
          class="navbar-toggle pull-right"
          v-on:click="toggleMobileRightSidebar"
          v-if="pageOptions.pageWithTwoSidebar"
        >
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <button
          type="button"
          class="navbar-toggle pull-left"
          v-on:click="toggleMobileSidebar"
          v-if="pageOptions.pageWithTwoSidebar"
        >
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <!--<a href="/" class="navbar-brand">
          <span class="navbar-logo"></span>
          NHIS
        </a>-->
        <a href="/" class="navbar-brand">
          <img src="images/logo.png" alt="logo"/>
          NHIS
        </a>
        <button
          type="button"
          class="navbar-toggle"
          v-on:click="toggleMobileSidebar"
          v-if="!pageOptions.pageWithTwoSidebar && (!pageOptions.pageWithTopMenu && !pageOptions.pageWithoutSidebar)"
        >
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <button
          type="button"
          class="navbar-toggle"
          v-on:click="toggleMobileTopMenu"
          v-if="pageOptions.pageWithTopMenu && pageOptions.pageWithoutSidebar"
        >
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <button
          type="button"
          class="navbar-toggle p-0 m-r-0"
          v-on:click="toggleMobileMegaMenu"
          v-if="pageOptions.pageWithMegaMenu"
        >
          <span class="fa-stack fa-lg text-inverse m-t-2">
            <i class="far fa-square fa-stack-2x"></i>
            <i class="fa fa-cog fa-stack-1x"></i>
          </span>
        </button>
      </div>
      <!-- end navbar-header -->
      <header-mega-menu v-if="pageOptions.pageWithMegaMenu"></header-mega-menu>

      <!-- begin header-nav -->
      <ul class="navbar-nav navbar-right">
        <!-- <li class="dropdown">
          <b-dropdown
            variant="link"
            menu-class="media-list dropdown-menu-right"
            toggle-class="f-s-14"
          >
            <template slot="button-content">
              <i class="fa fa-bell"></i>
              <span class="label">5</span>
            </template>
            <b-dropdown-header>NOTIFICATIONS (5)</b-dropdown-header>
            <b-dropdown-item href="javascript:;" class="media p-t-10 p-b-10">
              <div class="media-left">
                <i class="fa fa-bug media-object bg-silver-darker"></i>
              </div>
              <div class="media-body">
                <h6 class="media-heading">
                  Server Error Reports
                  <i class="fa fa-exclamation-circle text-danger"></i>
                </h6>
                <div class="text-muted f-s-11">3 minutes ago</div>
              </div>
            </b-dropdown-item>
            <b-dropdown-item href="javascript:;" class="media p-t-10 p-b-10">
              <div class="media-left">
                <img src="/images/user/user-1.jpg" class="media-object" alt>
                <i class="fab fa-facebook-messenger text-primary media-object-icon"></i>
              </div>
              <div class="media-body">
                <h6 class="media-heading">John Smith</h6>
                <p>Quisque pulvinar tellus sit amet sem scelerisque tincidunt.</p>
                <div class="text-muted f-s-11">25 minutes ago</div>
              </div>
            </b-dropdown-item>
            <b-dropdown-item href="javascript:;" class="media p-t-10 p-b-10">
              <div class="media-left">
                <img src="/images/user/user-2.jpg" class="media-object" alt>
                <i class="fab fa-facebook-messenger text-primary media-object-icon"></i>
              </div>
              <div class="media-body">
                <h6 class="media-heading">Olivia</h6>
                <p>Quisque pulvinar tellus sit amet sem scelerisque tincidunt.</p>
                <div class="text-muted f-s-11">35 minutes ago</div>
              </div>
            </b-dropdown-item>
            <b-dropdown-item href="javascript:;" class="media p-t-10 p-b-10">
              <div class="media-left">
                <i class="fa fa-plus media-object bg-silver-darker"></i>
              </div>
              <div class="media-body">
                <h6 class="media-heading">New User Registered</h6>
                <div class="text-muted f-s-11">1 hour ago</div>
              </div>
            </b-dropdown-item>
            <b-dropdown-item href="javascript:;" class="media p-t-10 p-b-10">
              <div class="media-left">
                <i class="fa fa-envelope media-object bg-silver-darker"></i>
                <i class="fab fa-google text-warning media-object-icon f-s-14"></i>
              </div>
              <div class="media-body">
                <h6 class="media-heading">New Email From John</h6>
                <div class="text-muted f-s-11">2 hour ago</div>
              </div>
            </b-dropdown-item>
            <b-dropdown-item class="dropdown-footer text-center">
              <a href="javascript:;">View more</a>
            </b-dropdown-item>
          </b-dropdown>
        </li> -->
        <li class="dropdown navbar-user">
          <b-dropdown variant="link" menu-class="dropdown-menu-right">
            <template slot="button-content">
              <div class="image image-icon bg-black text-grey-darker">
                <i class="fa fa-user"></i>
              </div>
              <span class="d-none d-md-inline">{{ name }}</span>
              <b class="caret"></b>
            </template>
            <b-dropdown-item href="javascript:;">Edit Profile</b-dropdown-item>
            <b-dropdown-item href="javascript:;">Setting</b-dropdown-item>
            <b-dropdown-divider></b-dropdown-divider>
            <b-dropdown-item href="/logout">Log Out</b-dropdown-item>
          </b-dropdown>
        </li>
      </ul>
      <!-- end header navigation right -->
    </div>
    <!-- end #header -->
  </div>
</template>

<script>
import PageOptions from '../PageOptions.vue';
import HeaderMegaMenu from './HeaderMegaMenu.vue';

export default {
  name: 'Header',
	components: {
		HeaderMegaMenu
  },
  data() {
		return {
      name: '',
			pageOptions: PageOptions
		}
  },
  mounted() {
    this.name = document.querySelector('meta[name="current-user-name"]').content;
  },
	methods: {
		toggleMobileSidebar() {
			this.pageOptions.pageMobileSidebarToggled = !this.pageOptions.pageMobileSidebarToggled;
		},
		toggleMobileRightSidebar() {
			this.pageOptions.pageMobileRightSidebarToggled = !this.pageOptions.pageMobileRightSidebarToggled;
		},
		toggleMobileTopMenu() {
			this.pageOptions.pageMobileTopMenu = !this.pageOptions.pageMobileTopMenu;
		},
		toggleMobileMegaMenu() {
			this.pageOptions.pageMobileMegaMenu = !this.pageOptions.pageMobileMegaMenu;
		},
		toggleRightSidebar() {
			this.pageOptions.pageRightSidebarToggled = !this.pageOptions.pageRightSidebarToggled;
		},
		checkForm: function(e) {
			e.preventDefault();
			this.$router.push({ path: '/extra/search' })
		}
	}
}
</script>

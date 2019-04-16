<template>
  <!-- begin sidebar nav -->
  <ul class="nav" v-if="menus">
    <li class="nav-header">Navigation</li>
    <sidebar-nav-list
      v-for="(menu, i) in menus"
      ref="sidebarNavList"
      :menu="menu"
      :scrollTop="scrollTop"
      :key="i"
      :status="menu.status"
      @collapse-other="handleCollapseOther(menu)"
    ></sidebar-nav-list>
    <!-- begin sidebar minify button -->
    <li>
      <a href="javascript:;" class="sidebar-minify-btn" v-on:click="handleSidebarMinify()">
        <i class="fa fa-angle-double-left"></i>
      </a>
    </li>
    <!-- end sidebar minify button -->
  </ul>
  <!-- end sidebar nav -->
</template>

<script>
import SidebarMenu from './SidebarMenu.vue';
import SidebarNavList from './SidebarNavList.vue';
import PageOptions from '../PageOptions.vue';

export default {
	name: 'SidebarNav',
	props: ['scrollTop'],
	components: {
		SidebarNavList
	},
	data () {
		return {
			pageOptions: PageOptions,
			permissions: [],
      permStrs: [],
      menus: []
		}
	},
  mounted() {
    this.permissions = JSON.parse(document.querySelector('meta[name="permissions"]').content);
    this.permStrs = this.permissions.map(({ name }) => name);
    this.menus = SidebarMenu
      .reduce((menu, path) => {
        const reducedPath = this.reducePath(path);
        return !!reducedPath ? menu.concat(reducedPath) : menu;
      }, []);
  },
	methods: {
		handleCollapseOther: function(menu) {
			for (var i = 0; i < this.menus.length; i++) {
				this.$refs.sidebarNavList[i].collapse(menu);
			}
		},
		handleSidebarMinify: function() {
			this.pageOptions.pageSidebarMinified = !this.pageOptions.pageSidebarMinified;
		},
		reducePath(path) {
      if (path.children) {
        path.children = path.children
          .reduce((menu, child) => {
            const reduced = this.reducePath(child);
            return !!reduced ? menu.concat(reduced) : menu;
          }, []);

        return !path.children.length && path.path === '#' ? null : path;
      }

      if (path.permissions && !path.permissions.some(perm => this.permStrs.some(p => p === perm))) return null;

      return path;
		}
	}
}
</script>

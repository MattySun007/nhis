<script>
  const sidebarMenu = [
    { path: '#', icon: 'fa fa-th', title: 'Institutions', permissions: ['institutions:read'],
      children: [
        { path: '/institutions', title: 'List', permissions: ['institutions:read'] }
      ]
    },
    { path: '#', icon: 'fa fa-th', title: 'HCPs', permissions: ['hcps:read'],
      children: [
        { path: '/hcps', title: 'List', permissions: ['hcps:read'] }
      ]
    }
  ]

  export default sidebarMenu;
</script>

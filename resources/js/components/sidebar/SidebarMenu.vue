<script>
  const sidebarMenu = [
    { path: '/dashboard', icon: 'fa fa-home', title: 'Dashboard'},
    { path: '#', icon: 'fa fa-th', title: 'Institutions', permissions: ['institutions:read'],
      children: [
        { path: '/institutions', title: 'List', permissions: ['institutions:read'] }
      ]
    },
    { path: '#', icon: 'fa fa-stethoscope', title: 'HCPs', permissions: ['hcps:read'],
      children: [
        { path: '/hcps', title: 'List', permissions: ['hcps:read'] }
      ]
    },
    { path: '#', icon: 'fa fa-users', title: 'Indv. Contributors', permissions: ['individual-contributors:read'],
      children: [
        { path: '/individual-contributors', title: 'List', permissions: ['individual-contributors:read'] }
      ]
    },
    { path: '#', icon: 'fa fa-cloud', title: 'Institution-Hcp', permissions: ['institution-hcp:read'],
      children: [
        { path: '/institution-hcp', title: 'List', permissions: ['institution-hcp:read'] }
      ]
    },
    { path: '#', icon: 'fa fa-user-plus', title: 'Agency Users', permissions: ['agency-users:read'],
      children: [
        { path: '/agency-users', title: 'List', permissions: ['agency-users:read'] }
      ]
    },
    { path: '#', icon: 'fa fa-ambulance', title: 'Treatment', permissions: ['treatments:read'],
      children: [
        { path: '/treatments', title: 'List', permissions: ['treatments:read'] }
      ]
    },
    { path: '#', icon: 'fa fa-lock', title: 'Access Rights', permissions: ['permissions:manage'],
      children: [
        { path: '/permissions', title: 'List', permissions: ['permissions:manage'] }
      ]
    },
    { path: '#', icon: 'fa fa-tasks', title: 'Adoption', permissions: ['adoption:read'],
      children: [
        { path: '/adoption', title: 'List', permissions: ['adoption:read'] }
      ]
    },
    { path: '#', icon: 'fa fa-coins', title: 'Claims',
      children: [
        { path: '/claims/unpaid', title: 'List Unpaid', permissions: ['claims:read','claims:manage'] },
        { path: '/claims/paid', title: 'List Paid', permissions: ['claims:read','claims:manage'] },
        { path: '/my/treatments/claims', title: 'My Claims' }
      ]
    }
  ]

  export default sidebarMenu;
</script>

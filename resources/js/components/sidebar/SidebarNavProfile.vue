<template>
	<ul class="nav">
		<li class="nav-profile">
			<a href="javascript:;" v-on:click="expand()">
				<div class="cover with-shadow"></div>
				<div class="image image-icon bg-black text-grey-darker">
					<i class="fa fa-user"></i>
				</div>
				<div class="info">
					<b class="caret pull-right"></b>
					{{ name }}
					<small>{{ user_type }}</small>
				</div>
			</a>
		</li>
		<!-- <li>
			<ul class="nav nav-profile" v-bind:class="{ 'd-block': this.stat == 'expand', 'd-none': this.stat == 'collapse' }">
				<li><a href="javascript:;"><i class="fa fa-cog"></i> Settings</a></li>
				<li><a href="javascript:;"><i class="fa fa-pencil-alt"></i> Send Feedback</a></li>
				<li><a href="javascript:;"><i class="fa fa-question-circle"></i> Helps</a></li>
			</ul>
		</li> -->
	</ul>
</template>

<script>

export default {
	name: 'SidebarNavProfile',
	data() {
		return {
			stat: '',
			name: '',
      user_type: ''
		}
	},
  mounted() {
    this.name = document.querySelector('meta[name="current-user-name"]').content;
    this.user_type = document.querySelector('meta[name="current-user-type"]').content;
  },
	methods: {
		expand: function() {
			this.stat = (this.stat == 'expand') ? 'collapse' : 'expand'
		}
  }
}
</script>

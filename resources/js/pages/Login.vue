<template>
  <Page>
    <!-- begin login-cover -->
    <div class="login-cover">
      <div class="login-cover-image" :style="{ backgroundImage: 'url('+ bg.activeImg +')' }"></div>
      <div class="login-cover-bg"></div>
    </div>
    <!-- end login-cover -->
    <!-- begin login -->
    <div class="login login-v2" data-pageload-addclass="animated fadeIn">
      <div class="login-header">
        <div class="brand">
          <img src="/images/logo.png" height="60">
        </div>
        <div class="icon">
          <i class="fa fa-lock"></i>
        </div>
      </div>
      <!-- begin login-content -->
      <div class="login-content">

        <form ref="form" action="/login" method="POST" class="margin-bottom-0" @submit.prevent="checkForm()" id="login_form">

          <div v-if="get_errors.length >= 1 || get_success.length >= 1" class="form-group m-b-20">
            <span v-if="get_errors.length >= 1" class="text text-warning">{{ get_errors }}</span>
            <span v-else-if="get_success.length >= 1" class="text text-success">{{ get_success }}</span>
          </div>

          <input type="hidden" name="_token" v-model="form.token">
          <div class="form-group m-b-20">
            <input type="email" id="email" name="email" class="form-control form-control-lg" placeholder="Provide Email" v-model.trim="form.email" required autofocus>
          </div>

          <div class="form-group m-b-20">
            <input type="password" name="password" id="password" class="form-control form-control-lg" placeholder="Password" v-model.trim="form.password" required>
          </div>

          <div class="checkbox checkbox-css m-b-20">
            <input type="checkbox" name="remember" id="remember">
            <label for="remember">Remember Me</label>
          </div>

          <div class="login-buttons">
            <button type="submit" class="btn btn-inverse btn-block btn-lg">Login</button>
          </div>
          <div class="m-t-20">
            Forgot Password? Click <a href="/password.request">here</a>
          </div>
        </form>



      </div>
      <!-- end login-content -->
    </div>
    <!-- end login -->
    <!-- begin login-bg -->
    <ul class="login-bg-list clearfix">
      <li v-bind:class="{ 'active': bg.bg1.active }">
        <a
          href="javascript:;"
          v-on:click="select('bg1')"
          style="background-image: url(/images/login-bg/login-bg-16.jpg)"
        ></a>
      </li>
      <li v-bind:class="{ 'active': bg.bg2.active }">
        <a
          href="javascript:;"
          v-on:click="select('bg2')"
          style="background-image: url(/images/login-bg/login-bg-17.jpg)"
        ></a>
      </li>
      <li v-bind:class="{ 'active': bg.bg3.active }">
        <a
          href="javascript:;"
          v-on:click="select('bg3')"
          style="background-image: url(/images/login-bg/login-bg-15.jpg)"
        ></a>
      </li>
      <li v-bind:class="{ 'active': bg.bg4.active }">
        <a
          href="javascript:;"
          v-on:click="select('bg4')"
          style="background-image: url(/images/login-bg/login-bg-14.jpg)"
        ></a>
      </li>
      <li v-bind:class="{ 'active': bg.bg5.active }">
        <a
          href="javascript:;"
          v-on:click="select('bg5')"
          style="background-image: url(/images/login-bg/login-bg-13.jpg)"
        ></a>
      </li>
      <li v-bind:class="{ 'active': bg.bg6.active }">
        <a
          href="javascript:;"
          v-on:click="select('bg6')"
          style="background-image: url(/images/login-bg/login-bg-12.jpg)"
        ></a>
      </li>
    </ul>
    <!-- end login-bg -->
  </Page>
</template>

<script>
  import Page from './Page';
  import FilterMixin from '../mixins/FilterMixin';
  import PageOptions from '../components/PageOptions';

  export default {
    name: 'Login',
    mixins: [FilterMixin],
    components: {
      Page
    },
    created() {
      PageOptions.pageEmpty = true;
    },
    beforeRouteLeave (to, from, next) {
      PageOptions.pageEmpty = false;
      next();
    },
    data() {
      return {
        name: 'Login Form',
        form: {
          email: '',
          password: '',
          token: ''
        },
        errors: [],
        successMsg: [],
        bg: {
          activeImg: '/images/login-bg/login-bg-16.jpg',
          bg1: {
            active: true,
            img: '/images/login-bg/login-bg-16.jpg'
          },
          bg2: {
            active: false,
            img: '/images/login-bg/login-bg-17.jpg'
          },
          bg3: {
            active: false,
            img: '/assets/img/login-bg/login-bg-15.jpg'
          },
          bg4: {
            active: false,
            img: '/images/login-bg/login-bg-14.jpg'
          },
          bg5: {
            active: false,
            img: '/images/login-bg/login-bg-13.jpg'
          },
          bg6: {
            active: false,
            img: '/images/login-bg/login-bg-12.jpg'
          }
        }
      }
    },
    mounted() {
      this.form.token = document.querySelector('meta[name="csrf-token"]').content;
    },
    computed: {
      get_errors(){
        return this.errors.flat().join('<br>');
      },
      get_success(){
        return this.successMsg.flat().join('<br>');
      }
    },
    methods: {
      /*checkForm() {
        axios
          .post('/login', this.form)
          .then(({ data: { success, data, message = 'could not log in' } }) => {
            if (success) {
              return true;
            }else{
              console.log("error1", data);
              this.errors = message;
              return false;
            }
          })
          .catch(({ response: { data } }) =>  this.errors = Object.values(data.errors).flat().join('<br>'));//this.error = Object.values(data).flat().join('<br>');
        return false;
      },*/
      checkForm: function () {

        this.errors = [];
        if (!this.form.email) {
          this.errors.push('Email required.');
        }
        if(!this.form.password) {
          this.errors.push('Password required.');
        }

        let errors = [];
        let successMsg = [];
        if(this.errors.length < 1){
          let formContents = jQuery("#login_form").serialize();

          axios.post('/vueLogin', formContents).then(function(response) {
            if(response.status === 200){
              if(response.data.status === 'error'){
                errors.push(response.data.error);
              }else{
                successMsg.push('Welcome '+ response.data.user.last_name + ' '+ response.data.user.first_name+ ', ...redirecting to your dashboard');
                setTimeout(function(){window.location.href=response.data.url;},6000);
              }
            }
          }, function() {
            errors.push(response.data.error);
          });
          this.errors = errors;
          this.successMsg = successMsg;
        }

      },
      select(x) {
        this.bg.bg1.active = false;
        this.bg.bg2.active = false;
        this.bg.bg3.active = false;
        this.bg.bg4.active = false;
        this.bg.bg5.active = false;
        this.bg.bg6.active = false;

        switch (x) {
          case 'bg1':
            this.bg.bg1.active = true;
            this.bg.activeImg = this.bg.bg1.img;
            break;
          case 'bg2':
            this.bg.bg2.active = true;
            this.bg.activeImg = this.bg.bg2.img;
            break;
          case 'bg3':
            this.bg.bg3.active = true;
            this.bg.activeImg = this.bg.bg3.img;
            break;
          case 'bg4':
            this.bg.bg4.active = true;
            this.bg.activeImg = this.bg.bg4.img;
            break;
          case 'bg5':
            this.bg.bg5.active = true;
            this.bg.activeImg = this.bg.bg5.img;
            break;
          case 'bg6':
            this.bg.bg6.active = true;
            this.bg.activeImg = this.bg.bg6.img;
            break;
        }
      }
    }
  }
</script>

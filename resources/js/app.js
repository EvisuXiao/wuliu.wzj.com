
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import locale from 'element-ui/lib/locale/lang/zh-CN' // lang i18n
Vue.use(ElementUI, { locale });

import './styles/index.scss' // global css
import 'normalize.css/normalize.css' // A modern alternative to CSS resets

import App from './App.vue';
import router from './router';
import store from './store'
import './icons' // icon
import './utils/permission' // permission control

const app = new Vue({
	el: '#app',
	router,
	store,
	render: h => h(App)
});
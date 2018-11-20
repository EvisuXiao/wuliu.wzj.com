import Vue from 'vue'
import Router from 'vue-router'
import NotFound from '../views/404'
import Dashboard from '../views/dashboard/index'
import Login from '../views/login/index'
import Register from '../views/register/index'
import Farmer from '../views/farmer/index'
import Company from '../views/company/index'
import User from '../views/user/index'
import {getUserInfo} from '../utils/auth'
/* Layout */
import Layout from '../views/layout/Layout'

// in development-env not use lazy-loading, because lazy-loading too many pages will cause webpack hot update too slow. so only in production use lazy-loading;
// detail: https://panjiachen.github.io/vue-element-admin-site/#/lazy-loading

Vue.use(Router);


/**
 * hidden: true                   if `hidden:true` will not show in the sidebar(default is false)
 * alwaysShow: true               if set true, will always show the root menu, whatever its child routes length
 *                                if not set alwaysShow, only more than one route under the children
 *                                it will becomes nested mode, otherwise not show the root menu
 * redirect: noredirect           if `redirect:noredirect` will no redirect in the breadcrumb
 * name:'router-name'             the name is used by <keep-alive> (must set!!!)
 * meta : {
    title: 'title'               the name show in submenu and breadcrumb (recommend set)
    icon: 'svg-name'             the icon show in the sidebar,
  }
 **/


export const constantRouterMap = [
	{path: '/login', component: Login, hidden: true},
	{path: '/register', component: Register, hidden: true},
	{path: '/404', component: NotFound, hidden: true},
	{
		path: '/',
		name: 'Dashboard',
		redirect: '/index',
		component: Layout,
		hidden: true,
		children: [{
			path: 'index',
			component: Dashboard,
		}]
	},
	{
		path: '/user',
		name: 'User',
		redirect: '/user/index',
		component: Layout,
		meta: {title: '用户', icon: 'link'},
		children: [
			{
				path: 'index',
				component: User,
				meta: {title: '用户列表', icon: 'link'}
			}
		]
	},
	{path: '*', redirect: '/404', hidden: true}
];

export default new Router({
	// mode: 'history', //后端支持可开
	scrollBehavior: () => ({y: 0}),
	routes: constantRouterMap
})

import Vue from 'vue'
import Router from 'vue-router'
import NotFound from '../views/404'
import Dashboard from '../views/dashboard/index'
import Login from '../views/login/index'
import Register from '../views/register/index'
import Register2 from '../views/register/index2'
import BankFarmer from '../views/bank/farmer'
import FarmerList from '../views/farmer/list'
import FarmerInfo from '../views/farmer/info'
import FarmerApply from '../views/farmer/apply'
import FarmerWithdraw from '../views/farmer/withdraw'
import DealerList from '../views/dealer/list'
import Company from '../views/company/index'
import User from '../views/user/index'
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
];

export default new Router({
	// mode: 'history', //后端支持可开
	scrollBehavior: () => ({y: 0}),
	routes: constantRouterMap
})

export const asyncRouterMap = {
	1: [
		{
			path: '/user',
			name: 'User',
			redirect: '/user/index',
			component: Layout,
			meta: {title: '用户列表', icon: 'link'},
			children: [
				{
					path: 'index',
					component: User,
					meta: {title: '用户列表', icon: 'link'}
				}
			]
		},
		{
			path: '/user',
			name: 'User-add',
			redirect: '/user/add',
			component: Layout,
			meta: {title: '用户列表', icon: 'link'},
			children: [
				{
					path: 'add',
					component: Register2,
					meta: {title: '添加用户', icon: 'link'}
				},
			]
		}
	],
	2: [
		{
			path: '/company',
			name: 'Company-farmer',
			redirect: '/company/farmer',
			component: Layout,
			meta: {title: '农户列表', icon: 'link'},
			children: [
				{
					path: 'farmer',
					component: FarmerList,
					meta: {title: '农户列表', icon: 'link'}
				}
			]
		},
		{
			path: '/company',
			name: 'Company-dealer',
			redirect: '/company/dealer',
			component: Layout,
			meta: {title: '核心企业', icon: 'link'},
			children: [
				{
					path: 'dealer',
					component: DealerList,
					meta: {title: '经销商列表', icon: 'link'}
				},
			]
		},
	],
	3: [
		{
			path: '/bank',
			name: 'Bank-apply',
			redirect: '/bank/farmer',
			component: Layout,
			meta: {title: '借款审核', icon: 'link'},
			children: [
				{
					path: 'farmer',
					component: BankFarmer,
					meta: {title: '农户申请', icon: 'link'}
				}
			]
		},
	],
	4: [
		{
			path: '/farmer',
			name: 'Farmer',
			redirect: '/farmer/info',
			component: Layout,
			meta: {title: '生产信息', icon: 'link'},
			children: [
				{
					path: 'info',
					component: FarmerInfo,
					meta: {title: '生产信息', icon: 'link'}
				}
			]
		},
		{
			path: '/farmer/loan',
			name: 'Farmer-loan',
			redirect: '/farmer/loan/apply',
			component: Layout,
			meta: {title: '借贷信息', icon: 'link'},
			children: [
				{
					path: 'apply',
					component: FarmerApply,
					meta: {title: '借款申请', icon: 'link'}
				},
				{
					path: 'list',
					component: FarmerWithdraw,
					meta: {title: '提款列表', icon: 'link'}
				}
			]
		},
	]
}

export const notFoundRouterMap = [
	{ path: '*', redirect: '/404', hidden: true }
]


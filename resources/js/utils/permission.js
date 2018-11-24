import router from '../router'
import NProgress from 'nprogress' // Progress 进度条
import 'nprogress/nprogress.css' // Progress 进度条样式
import {getUserInfo} from './auth' // 验权
import store from '../store/index'

const whiteList = ['/login', '/register'] // 不重定向白名单

router.beforeEach((to, from, next) => {
	NProgress.start()
	const userInfo = getUserInfo();
	if (userInfo) { // 判断是否有token
		if (to.path === '/login') {
			next({ path: '/' });
		} else {
			store.dispatch('generateRoutes', userInfo.role).then(() => { // 根据roles权限生成可访问的路由表
				router.addRoutes(store.getters.addRouters) // 动态添加可访问路由表
				next()
			}).catch(() => {
					next({path: '/'})
				}
			)
		}
	} else {
		if (whiteList.indexOf(to.path) !== -1) { // 在免登录白名单，直接进入
			next();
		} else {
			next('/login'); // 否则全部重定向到登录页
		}
	}
});

router.afterEach(() => {
	NProgress.done() // 结束Progress
})
import router from '../router'
import NProgress from 'nprogress' // Progress 进度条
import 'nprogress/nprogress.css' // Progress 进度条样式
import {getUserInfo} from './auth' // 验权

const whiteList = ['/login', '/register'] // 不重定向白名单
router.beforeEach((to, from, next) => {
	NProgress.start()
	if(whiteList.indexOf(to.path) !== -1 || getUserInfo()) {
		next()
	} else {
		next('/login')
	}
})

router.afterEach(() => {
	NProgress.done() // 结束Progress
})

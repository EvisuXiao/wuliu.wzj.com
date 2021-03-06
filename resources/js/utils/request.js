import axios from 'axios'
import { Message } from 'element-ui'
import store from '../store'
import { getToken } from './auth'

// 创建axios实例
const service = axios.create({
	// baseURL: 'http://wuliu.wzj.com', // api 的 base_url
	timeout: 30000 // 请求超时时间
});

// request拦截器
service.interceptors.request.use(
	config => {
		if (store.getters.token) {
			config.headers['X-Token'] = getToken() // 让每个请求携带自定义token 请根据实际情况自行修改
		}
		return config
	},
	error => {
		// Do something with request error
		console.log(error) // for debug
		Promise.reject(error)
	}
);

// response 拦截器
service.interceptors.response.use(
	response => {
		const res = response.data;
		if(res.code !== 1) {
			Message({
				message: res.message,
				type: 'error',
				duration: 5 * 1000
			});
			return Promise.reject('error')
		} else {
			return response.data
		}
	},
	error => {
		console.log('err' + error); // for debug
		Message({
			message: error.message,
			type: 'error',
			duration: 5 * 1000
		});
		return Promise.reject(error)
	}
);

export default service
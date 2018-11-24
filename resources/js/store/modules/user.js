import {constantRouterMap, asyncRouterMap, notFoundRouterMap} from "../../router";

const user = {
	state: {
		role: 0,
		routers: constantRouterMap,
		addRouters: []
	},

	mutations: {
		setRole: (state, role) => {
			state.role = role
		},
		setRouters: (state, role) => {
			const routers = asyncRouterMap[role];
			state.addRouters = routers;
			state.routers = constantRouterMap.concat(routers).concat(notFoundRouterMap)
		}
	},

	actions: {
		generateRoutes({ commit }, data) {
			return new Promise(resolve => {
				commit('setRouters', data);
				resolve()
			})
		}
	}
}

export default user

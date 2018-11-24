const getters = {
	sidebar: state => state.app.sidebar,
	device: state => state.app.device,
	role: state => state.user.role,
	user_routers: state => state.user.routers,
	addRouters: state => state.user.addRouters,
	dialogVisible: state => state.common.dialogVisible,
	dialogVisible2: state => state.common.dialogVisible2,
	dialogOpt: state => state.common.dialogOpt,
	rowTmp: state => state.common.rowTmp,
}
export default getters

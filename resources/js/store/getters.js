const getters = {
	sidebar: state => state.app.sidebar,
	device: state => state.app.device,
	dialogVisible: state => state.common.dialogVisible,
	dialogVisible2: state => state.common.dialogVisible2,
	dialogOpt: state => state.common.dialogOpt,
	rowTmp: state => state.common.rowTmp,
}
export default getters

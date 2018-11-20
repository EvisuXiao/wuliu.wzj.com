const common = {
	state: {
		dialogVisible: false,
		dialogVisible2: false,
		dialogOpt: 'add',
		rowTmp: {},
	},
	mutations: {
		setDialogVisible(state, n) {
			state.dialogVisible = n
		},
		setDialogVisible2(state, n) {
			state.dialogVisible2 = n
		},
		setDialogOpt(state, n) {
			state.dialogOpt = n
		},
		setRowTmp(state, n) {
			state.rowTmp = n
		},
	}
}

export default common
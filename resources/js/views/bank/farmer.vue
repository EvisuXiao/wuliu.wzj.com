<template>
	<div class="app-container">
		<data-tables :data.sync="data" ref="table" :table-props="tableProps" :filters="filters"
		             @row-click="clickThenSelect"
		             @selection-change="setMultiSelection">
			<el-row slot="tool" style="margin: 20px 0" type="flex" justify="end">
				<el-tooltip v-for="button in headerTool" :key="button.icon" placement="bottom" :content="button.label">
					<el-button plain :icon="button.icon" @click="button.handler"></el-button>
				</el-tooltip>
				<el-tooltip placement="bottom" content="刷新">
					<el-button plain icon="el-icon-refresh" @click="refreshTable"></el-button>
				</el-tooltip>
				<el-col :sm="3">
					<el-input placeholder="请输入过滤内容" prefix-icon="el-icon-search" v-model="filters[0].value"></el-input>
				</el-col>
			</el-row>
			<el-table-column type="selection" :fixed="true"></el-table-column>
			<el-table-column v-bind="rowTool.props">
				<template slot-scope="scope">
					<el-tooltip v-for="button in rowTool.buttons" :key="button.icon"
					            v-if="!button.hasOwnProperty('visible') || button.visible(scope.row)" placement="top"
					            :content="button.label">
						<el-button type="text" :icon="button.icon" @click="button.handler(scope.row)"></el-button>
					</el-tooltip>
				</template>
			</el-table-column>
			<el-table-column v-for="title in titles" :key="title.prop" v-bind="title">
				<template slot-scope="scope">
					<el-tag v-if="title.prop === 'status'" :type="statusClass(scope.row.status)">{{
						statusFormatter(scope.row.status) }}
					</el-tag>
					<span v-else>{{ scope.row[title.prop] }}</span>
				</template>
			</el-table-column>
		</data-tables>
		<el-dialog title="审核信息" :visible="dialog" :before-close="closeDialog">
			<el-form ref="form" :model="form" label-width="150px" size="small">
				<el-form-item label="信用评分">
					<el-col :span="8">
						<el-input disabled v-model="applyScore"></el-input>
					</el-col>
				</el-form-item>
				<el-form-item label="信用评级" prop="level">
					<el-col :span="8">
						<el-input disabled v-model="form.level"></el-input>
					</el-col>
				</el-form-item>
				<el-form-item label="审批金额" prop="crop">
					<el-col :span="8">
						<el-input disabled v-model="form.approval_amount"></el-input>
					</el-col>
				</el-form-item>
			</el-form>
			<div slot="footer" class="dialog-footer">
				<el-button @click="closeDialog" size="small">取 消</el-button>
				<el-button type="primary" @click="rowOpt(3, form.id)" size="small">确 定</el-button>
			</div>
		</el-dialog>
	</div>
</template>

<script>
	import request from '../../utils/request'
	import {DataTables} from 'vue-data-tables'

	export default {
		data() {
			return {
				data: [],
				multiSelection: [],
				titles: [],
				labelMaps: {},
				tableProps: {
					border: true,
					stripe: true,
					highlightCurrentRow: true,
					loading: this.listLoading,
					elementLoadingText: "加载中",
					defaultSort: {
						prop: 'id',
						order: 'descending'
					}
				},
				form: {
					id: 0,
					loan_month: 0,
					level: '',
				    approval_amount: 0,
				},
				applyScore: 0,
				dialog: false,
				listLoading: true,
				headerTool: [
					// {
					// 	label: '添加',
					// 	icon: 'el-icon-plus',
					// 	handler: () => {
					// 		this.openDialog('add')
					// 	}
					// }
				],
				rowTool: {
					props: {
						prop: 'rowTool',
						label: '操作',
						width: 120,
						fixed: true,
						align: 'center',
					},
					buttons: [
						{
							visible: row => {
								return row.status === 1
							},
							label: '通过',
							icon: 'el-icon-check',
							handler: row => {
								this.openDialog(row)
							},
						},
						{
							visible: row => {
								return row.status === 1
							},
							label: '驳回',
							icon: 'el-icon-close',
							handler: row => {
								this.rowOpt(2, row.id)
							},
						}
					]
				},
				filters: [
					{
						prop: [],
						value: ''
					}
				]
			}
		},
		components: {
			DataTables
		},
		created() {
			this.fetchData();
		},
		methods: {
			fetchData() {
				this.listLoading = true;
				request({
					url: '/bank/farmerApplyLabel'
				}).then(response => {
					this.titles = response.data;
					for(let title of this.titles) {
						if(title.hasOwnProperty('prop')) {
							this.filters[0].prop.push(title.prop);
						}
					}
				})
				this.refreshTable()
			},
			statusClass(status) {
				const map = {
					0: 'info',
					1: '',
					2: 'warning',
					3: 'success'
				};
				return map[status]
			},
			statusFormatter(status) {
				const map = {
					0: '未提交',
					1: '已提交',
					2: '被驳回',
					3: '已通过'
				};
				return map[status]
			},
			openDialog(row) {
				request({
					url: '/bank/applyScore',
					params: {
						id: row.id
					}
				}).then(response => {
					this.form.id = row.id;
					this.form.loan_month = row.apply_loan_month;
					this.form.level = response.data.level;
					this.form.approval_amount = Math.round(parseInt(row.apply_loan_amount) * parseFloat(response.data.score));
					this.applyScore = response.data.score;
					this.dialog = true;
				}).catch(() => {
				})
			},
			closeDialog() {
				this.form.id = 0;
				this.form.loan_month = 0;
				this.form.level = '';
				this.form.approval_amount = 0;
				this.applyScore = 0;
				this.dialog = false;
			},
			refreshTable() {
				request({
					url: '/bank/farmerApply'
				}).then(response => {
					this.data = response.data;
					this.listLoading = false
				})
			},
			clickThenSelect(row, event, column) {
				if(column.label !== '操作') {
					this.$refs.table.$children[1].toggleRowSelection(row)
				}
			},
			setMultiSelection(val) {
				this.multiSelection = [];
				for(let row of val) {
					this.multiSelection.push(row.id);
				}
			},
			rowOpt(opt, id) {
				if(id < 1) {
					this.$message({
						type: 'warning',
						message: '申请有误'
					});
					return;
				}
				const labelMsg = {
					'2': '驳回',
					'3': '通过'
				};
				const cfm = '是否' + labelMsg[opt] + '申请';
				this.$confirm(cfm, '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
				}).then(() => {
					request({
						url: '/bank/opt',
						method: 'PUT',
						data: {
							id: id,
							type: opt,
							info: this.form
						}
					}).then(response => {
						this.$message({
							type: 'success',
							message: response.message
						});
						if(opt === 3) {
							this.closeDialog();
						}
						this.refreshTable()
					}).catch(() => {
					})
				}).catch(() => {
				});
			}
		}
	}
</script>

<style scoped>

</style>
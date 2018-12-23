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
					{{ scope.row[title.prop] }}
				</template>
			</el-table-column>
		</data-tables>
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
								return row.repaid_status === 0 || row.repaid_status === 2
							},
							label: '合同执行',
							icon: 'el-icon-check',
							handler: row => {
								this.rowOpt(row.repaid_status === 0 ? 1 : 3, row.id)
							},
						},
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
					url: '/farmer/label'
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
			refreshTable() {
				request({
					url: '/company/farmer'
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
				this.$confirm('是否执行合同', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
				}).then(() => {
					request({
						url: '/company/repay',
						method: 'PUT',
						data: {
							id: id,
							opt: opt
						}
					}).then(response => {
						this.$message({
							type: 'success',
							message: response.message
						});
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
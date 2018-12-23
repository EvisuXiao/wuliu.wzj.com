<template>
	<div class="app-container">
		<el-row style="margin: 20px 0">
			<el-col :sm="3">
				<span>还款状态:</span>
				<el-tag :type="statusClass(repaid_status)">{{ statusFormatter(repaid_status) }}</el-tag>
			</el-col>
			<div v-if="repaid_time">
				<el-col :sm="3"></el-col>
				<el-col :sm="6">
					还款时间: {{ repaid_time }}
				</el-col>
			</div>
		</el-row>
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
			<!--<el-table-column v-bind="rowTool.props">-->
				<!--<template slot-scope="scope">-->
					<!--<el-tooltip v-for="button in rowTool.buttons" :key="button.icon"-->
					            <!--v-if="!button.hasOwnProperty('visible') || button.visible(scope.row)" placement="top"-->
					            <!--:content="button.label">-->
						<!--<el-button type="text" :icon="button.icon" @click="button.handler(scope.row)"></el-button>-->
					<!--</el-tooltip>-->
				<!--</template>-->
			<!--</el-table-column>-->
			<el-table-column v-for="title in titles" :key="title.prop" v-bind="title">
				<template slot-scope="scope">
					<el-tag v-if="title.prop === 'status'" :type="statusClass(scope.row.status)">{{
						statusFormatter(scope.row.status) }}
					</el-tag>
					<span v-else>{{ scope.row[title.prop] }}</span>
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
				repaid_status: 0,
				repaid_time: '',
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
					url: '/farmer/withdrawLabel'
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
					url: '/farmer/withdraw'
				}).then(response => {
					this.data = response.data.list;
					this.repaid_status = response.data.repaid_status;
					this.repaid_time = response.data.repaid_time;
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
			statusClass(status) {
				const map = {
					0: 'info',
					1: 'success',
					2: 'danger',
					3: 'warning'
				};
				return map[status]
			},
			statusFormatter(status) {
				const map = {
					0: '未还款',
					1: '已还款',
					2: '逾期未还款',
					3: '逾期已还款'
				};
				return map[status]
			}
		}
	}
</script>

<style scoped>

</style>
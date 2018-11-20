<template>
	<el-dialog :title="title" :visible.sync="dialogVisible" :before-close="closeDialog">
		<el-form ref="form" :model="form" :rules="rules" label-width="150px">
			<el-input type="hidden" v-model="form.id"></el-input>
			<el-form-item label="IP地址" prop="host">
				<el-col :span="12"><el-input v-model="form.host" clearable></el-input></el-col>
			</el-form-item>
			<el-form-item label="端口" prop="port">
				<el-col :span="12"><el-input v-model="form.port" clearable></el-input></el-col>
			</el-form-item>
			<el-form-item label="用户名" prop="username">
				<el-col :span="12"><el-input v-model="form.username" clearable></el-input></el-col>
			</el-form-item>
			<el-form-item label="密码" prop="password">
				<el-col :span="12"><el-input type="password" v-model="form.password" clearable></el-input></el-col>
			</el-form-item>
			<el-form-item label="标识" prop="name">
				<el-col :span="12"><el-input v-model="form.name" :placeholder="defaultName" clearable></el-input></el-col>
			</el-form-item>
		</el-form>
		<div slot="footer" class="dialog-footer">
			<el-button @click="closeDialog" size="small">取 消</el-button>
			<el-button type="primary" @click="onSubmit" size="small">提 交</el-button>
		</div>
	</el-dialog>
</template>

<script>
	import request from '../../utils/request'

	export default {
		data() {
			return {
				isAdd: true,
				title: '',
				form: {
					id: 0,
					name: '',
					host: '',
					port: 3306,
					username: '',
					password: ''
				},
				rules: {
					// name: [
					// 	{min: 5, max: 50, message: '任务描述长度在 5 到 50 个字符', trigger: ['blur', 'change']}
					// ],
					// topic_id: [
					// 	{type: 'number', min: 1, message: '请选择队列Topic名', trigger: 'change'},
					// ],
					// table_id: [
					// 	{type: 'array', message: '请选择监听表名', trigger: 'change'},
					// ]
				},
			}
		},
		watch: {
			dialogVisible: function(val) {
				this.resetForm();
				if(val) {
					if(this.$store.getters.dialogOpt === 'add') {
						this.isAdd = true;
						this.title = '新增实例';
					} else {
						this.isAdd = false;
						this.title = '编辑实例';
						for(let i in this.form) {
							if(this.$store.getters.rowTmp.hasOwnProperty(i)) {
								this.form[i] = this.$store.getters.rowTmp[i];
							}
						}
					}
				}
			}
		},
		computed:{
			dialogVisible: function() {
				return this.$store.getters.dialogVisible
			},
			defaultName: function() {
				if(this.form.host && this.form.port) {
					return this.form.host + ':' + this.form.port
				}
				return ''
			}
		},
		methods: {
			resetForm() {
				if(this.$refs['form'] !== undefined) {
					this.$refs['form'].resetFields();
				}
				this.form.id = 0;
			},
			closeDialog() {
				this.resetForm();
				this.$store.commit('setDialogVisible', false)
			},
			onSubmit() {
				this.$refs.form.validate((valid) => {
					let data = {};
					if(!this.isAdd) {
						for(let key in this.form) {
							if(this.form[key] !== this.$store.getters.rowTmp[key]) {
								data[key] = this.form[key];
							}
						}
						if(!data) {
							this.$message({
								type: 'warning',
								message: '实例没有改动'
							});
							return;
						}
						data.id = this.form.id;
					} else {
						data = Object.assign({}, this.form);
					}
					if(valid) {
						this.$confirm('是否提交实例信息', '提示', {
							confirmButtonText: '确定',
							cancelButtonText: '取消',
						}).then(() => {
							request({
								url: '/instance/info',
								method: this.isAdd ? 'post' : 'put',
								data: data
							}).then(response => {
								this.$message({
									type: 'success',
									message: response.message
								});
								this.closeDialog();
								location.reload()
							})
						}).catch(() => {});
					} else {
						this.$message({
							type: 'error',
							message: '实例信息有误'
						});
					}
				});
			}
		}
	}
</script>

<style scoped>

</style>
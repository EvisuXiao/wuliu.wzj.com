<template>
	<div class="app-container">
		<el-form ref="form" :model="form" label-width="150px" size="small">
			<el-form-item label="接收原料" prop="material">
				<el-col :span="4">
					<el-input v-model="form.material" clearable></el-input>
				</el-col>
			</el-form-item>
			<el-form-item label="制造工艺" prop="product">
				<el-col :span="4">
					<el-input v-model="form.product" clearable></el-input>
				</el-col>
			</el-form-item>
			<el-form-item label="产品销量" prop="sales">
				<el-col :span="4">
					<el-input v-model="form.sales" clearable></el-input>
				</el-col>
			</el-form-item>
			<el-form-item label="企业资产" prop="asset">
				<el-col :span="4">
					<el-input v-model="form.asset" clearable></el-input>
				</el-col>
			</el-form-item>
			<el-form-item label="企业负债" prop="debt">
				<el-col :span="4">
					<el-input v-model="form.debt" clearable></el-input>
				</el-col>
			</el-form-item>
			<el-form-item>
				<el-button type="primary" @click="onSubmit">保 存</el-button>
			</el-form-item>
		</el-form>
	</div>
</template>

<script>
	import request from '../../utils/request'

	export default {
		data() {
			return {
				form: {
					id: 0,
					material: '',
					product: '',
					sales: 0,
					asset: 0,
					debt: 0
				},
			}
		},
		created() {
			this.getInfo();
		},
		methods: {
			getInfo() {
				request({
					url: '/company/info'
				}).then(response => {
					const data = response.data;
					for(let i in this.form) {
						if(data.hasOwnProperty(i)) {
							this.form[i] = data[i];
						}
					}
				})
			},
			onSubmit() {
				this.$confirm('是否保存信息', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
				}).then(() => {
					request({
						url: '/company/info',
						method: 'put',
						data: this.form
					}).then(response => {
						this.$message({
							type: 'success',
							message: response.message
						});
						this.getInfo()
					})
				}).catch(() => {
				});
			}
		}
	}
</script>

<style scoped>

</style>
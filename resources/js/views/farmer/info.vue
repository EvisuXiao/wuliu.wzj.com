<template>
	<div class="app-container">
		<el-form ref="form" :model="form" label-width="150px" size="small">
			<el-form-item label="农作物" prop="crop">
				<el-col :span="4">
					<el-input v-model="form.crop" clearable></el-input>
				</el-col>
			</el-form-item>
			<el-form-item label="土地面积(亩)" prop="land">
				<el-col :span="4">
					<el-input v-model="form.land" clearable></el-input>
				</el-col>
			</el-form-item>
			<el-form-item label="产出能力(吨/亩)" prop="productivity">
				<el-col :span="4">
					<el-input v-model="form.productivity" clearable></el-input>
				</el-col>
			</el-form-item>
			<el-form-item label="生产成本(万元)" prop="cost">
				<el-col :span="4">
					<el-input v-model="form.cost" clearable></el-input>
				</el-col>
			</el-form-item>
			<el-form-item label="个人资产(万元)" prop="asset">
				<el-col :span="4">
					<el-input v-model="form.asset" clearable></el-input>
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
					land: 0,
					crop: '',
					productivity: 0,
					cost: 0,
					asset: 0
				},
			}
		},
		created() {
			this.getInfo();
		},
		methods: {
			getInfo() {
				request({
					url: '/farmer/info'
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
						url: '/farmer/info',
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
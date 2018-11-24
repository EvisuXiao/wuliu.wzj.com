<template>
	<div class="app-container">
		<el-form ref="form" :model="form" label-width="150px" size="small">
			<el-form-item label="项目余额" prop="crop">
				<el-col :span="4">
					<el-input disabled v-model="form.rest_amount" clearable></el-input>
				</el-col>
			</el-form-item>
			<el-form-item label="订单数量(千克)" prop="quantity">
				<el-col :span="4">
					<el-input :disabled="formDisabled" v-model="form.quantity" clearable></el-input>
				</el-col>
			</el-form-item>
			<el-form-item label="单价(元/千克)" prop="price">
				<el-col :span="4">
					<el-input :disabled="formDisabled" v-model="form.price" clearable></el-input>
				</el-col>
			</el-form-item>
			<el-form-item label="用途" prop="purpose">
				<el-col :span="4">
					<el-input :disabled="formDisabled" v-model="form.purpose" clearable></el-input>
				</el-col>
			</el-form-item>
			<el-form-item label="合同金额">
				<el-col :span="4">
					<el-input disabled :value="loanAmount"></el-input>
				</el-col>
			</el-form-item>
			<el-form-item label="可提取金额">
				<el-col :span="4">
					<el-input disabled :value="form.available_amount"></el-input>
				</el-col>
			</el-form-item>
			<el-form-item label="审批状态" prop="crop">
				<el-col :span="4">
					<el-tag :type="statusTag"> {{ statusShow }} </el-tag>
				</el-col>
			</el-form-item>
			<el-form-item>
				<el-button :disabled="formDisabled" type="primary" @click="onSubmit">申 请</el-button>
				<el-button :disabled="form.status !== 3" type="primary" @click="dialog = true">提 款</el-button>
			</el-form-item>
		</el-form>
		<el-dialog title="提款" :visible="dialog" :before-close="closeDialog">
			<el-form ref="form2" :model="form2" label-width="150px" size="small">
				<el-form-item label="金额" prop="crop">
					<el-col :span="8">
						<el-input v-model="form2.amount" clearable></el-input>
					</el-col>
				</el-form-item>
				<el-form-item label="用途" prop="crop">
					<el-col :span="8">
						<el-input v-model="form2.purpose" clearable></el-input>
					</el-col>
				</el-form-item>
			</el-form>
			<div slot="footer" class="dialog-footer">
				<el-button @click="closeDialog" size="small">取 消</el-button>
				<el-button type="primary" @click="onWithdraw" size="small">确 定</el-button>
			</div>
		</el-dialog>
	</div>
</template>

<script>
	import request from '../../utils/request'

	export default {
		data() {
			return {
				form: {
					id: 0,
					rest_amount: 0,
					quantity: 0,
					price: 0,
					purpose: '',
					loan_amount: 0,
					available_amount: 0,
					status: 0
				},
				form2: {
					amount: 0,
					purpose: ''
				},
				dialog: false
			}
		},
		created() {
			this.getInfo();
		},
		computed:{
			formDisabled: function() {
				return this.form.status === 1 || this.form.status === 3;
			},
			statusShow: function() {
				const map = {
					0: '未提交',
					1: '已提交',
					2: '被驳回',
					3: '已通过'
				};
				return map[this.form.status];
			},
			statusTag: function() {
				const map = {
					0: 'info',
					1: '',
					2: 'warning',
					3: 'success'
				};
				return map[this.form.status];
			},
			loanAmount: function() {
				return this.form.loan_amount = Math.round(this.form.price * this.form.quantity);
			}
		},
		methods: {
			getInfo() {
				request({
					url: '/farmer/apply'
				}).then(response => {
					const data = response.data;
					for(let i in this.form) {
						if(data.hasOwnProperty(i)) {
							this.form[i] = data[i];
						}
					}
				})
			},
			closeDialog() {
				this.form2.amount = 0;
				this.form2.purpose = '';
				this.dialog = false;
			},
			onSubmit() {
				this.$confirm('是否提交申请', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
				}).then(() => {
					request({
						url: '/farmer/apply',
						method: this.form.id ? 'put' : 'post',
						data: this.form
					}).then(response => {
						this.$message({
							type: 'success',
							message: response.message
						});
						this.getInfo()
					})
				}).catch(() => {});
			},
			onWithdraw() {
				this.$confirm('是否确定提款', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
				}).then(() => {
					if(this.form2.amount > this.form.available_amount) {
						this.$message({
							type: 'warning',
							message: '提款金额超出额度'
						});
						return false;
					}
					request({
						url: '/farmer/withdraw',
						method: 'post',
						data: this.form2
					}).then(response => {
						this.$message({
							type: 'success',
							message: response.message
						});
						this.closeDialog()
						this.getInfo()
					})
				}).catch(() => {});
			},
		}
	}
</script>

<style scoped>

</style>
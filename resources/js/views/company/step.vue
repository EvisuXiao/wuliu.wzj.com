<template>
	<div>
		<el-steps :active="active" process-status="finish" finish-status="success" space="30%" align-center>
			<el-step title="用户信息"></el-step>
			<el-step title="基本信息"></el-step>
			<el-step title="完成"></el-step>
		</el-steps>
		<div align="center" :style="{ marginTop: '12px',display: uDisplay }">
			<el-form ref="userForm" :model="userForm" label-width="150px" size="small">
				<el-form-item label="用户名" prop="user_name">
					<el-col :span="4">
						<el-input v-model="userForm.username" clearable></el-input>
					</el-col>
				</el-form-item>
				<el-form-item label="密码" prop="password">
					<el-col :span="4">
						<el-input type="password" v-model="userForm.password" clearable></el-input>
					</el-col>
				</el-form-item>
				<el-form-item label="邮箱" prop="mail">
					<el-col :span="4">
						<el-input v-model="userForm.mail" clearable></el-input>
					</el-col>
				</el-form-item>
				<el-form-item label="注册类别">
					<el-col :span="4">
						<el-select v-model="option" placeholder="请选择">
							<el-option
									v-for="item in options"
									:key="item.value"
									:label="item.label"
									:value="item.value">
							</el-option>
						</el-select>
					</el-col>
				</el-form-item>
			</el-form>
		</div>
		<div align="center" :style="{ marginTop: '12px',display: fDisplay }">
			<el-form ref="farmerForm" :model="farmerForm" label-width="150px" size="small">
				<el-form-item label="姓名" prop="name">
					<el-col :span="4">
						<el-input v-model="farmerForm.name" clearable></el-input>
					</el-col>
				</el-form-item>
				<el-form-item label="身份证号" prop="id_card">
					<el-col :span="4">
						<el-input v-model="farmerForm.id_card" clearable></el-input>
					</el-col>
				</el-form-item>
				<el-form-item label="地址" prop="address">
					<el-col :span="4">
						<el-input v-model="farmerForm.address" clearable></el-input>
					</el-col>
				</el-form-item>
				<el-form-item label="电话" prop="phone">
					<el-col :span="4">
						<el-input v-model="farmerForm.phone" clearable></el-input>
					</el-col>
				</el-form-item>
			</el-form>
		</div>
		<div align="center" :style="{ marginTop: '12px',display: cDisplay }">
			<el-form ref="companyForm" :model="companyForm" label-width="150px" size="small">
				<el-form-item label="企业名称" prop="name">
					<el-col :span="4">
						<el-input v-model="companyForm.name" clearable></el-input>
					</el-col>
				</el-form-item>
				<el-form-item label="注册号" prop="reg_no">
					<el-col :span="4">
						<el-input v-model="companyForm.reg_no" clearable></el-input>
					</el-col>
				</el-form-item>
				<el-form-item label="地址" prop="address">
					<el-col :span="4">
						<el-input v-model="companyForm.address" clearable></el-input>
					</el-col>
				</el-form-item>
				<el-form-item label="电话" prop="phone">
					<el-col :span="4">
						<el-input v-model="companyForm.phone" clearable></el-input>
					</el-col>
				</el-form-item>
				<el-form-item label="注册资金" prop="asset">
					<el-col :span="4">
						<el-input v-model="companyForm.asset" clearable></el-input>
					</el-col>
				</el-form-item>
			</el-form>
		</div>
		<div align="center" :style="{ marginTop: '12px',display: eDisplay }">
			<el-form ref="endForm" :model="endForm" label-width="150px" size="small">
				<el-form-item label="验证码" prop="code">
					<el-col :span="4">
						<el-input v-model="endForm.code" clearable></el-input>
					</el-col>
				</el-form-item>
			</el-form>
		</div>
		<el-button style="margin-top: 12px;" @click="previous">上一步</el-button>
		<el-button style="margin-top: 12px;" @click="next">下一步</el-button>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				active: 0,
				options: [
					{
						value: 1,
						label: '农户'
					},
					{
						value: 2,
						label: '企业'
					},
					{
						value: 3,
						label: '银行'
					}
				],
				option: 1,
				userForm: {
					user_name: '',
					real_name: '',
					password: '',
					mail: '',
					phone: ''
				},
				farmerForm: {
					name: '',
					id_card: '',
					address: '',
					phone: ''
				},
				companyForm: {
					name: '',
					reg_no: '',
					address: '',
					phone: '',
					asset: 0
				},
				endForm: {
					code: ''
				},
			}
		},
		methods: {
			previous() {
				if(this.active > 0 && this.active < 3) {
					this.active--
				}
			},
			next() {
				if(this.active < 3) {
					this.active++
					if(this.active === 3) {
						this.$message({
							message: '注册成功',
							type: 'success'
						});
						setTimeout(function() {
							location.reload()
						}, 3000);
					}
				}
			}
		},
		computed: {
			uDisplay: function() {
				if(this.active === 0) {
					return '';
				}
				return 'none';
			},
			fDisplay: function() {
				if(this.active === 1 && this.option === 1) {
					return '';
				}
				return 'none';
			},
			cDisplay: function() {
				if(this.active === 1 && this.option === 2) {
					return '';
				}
				return 'none';
			},
			eDisplay: function() {
				if(this.active >= 2) {
					return '';
				}
				return 'none';
			}
		}
	}
</script>

<style scoped>

</style>
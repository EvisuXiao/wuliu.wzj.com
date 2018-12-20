<template>
	<div>
		<el-steps :active="active" process-status="finish" finish-status="success" space="30%" align-center>
			<el-step title="用户信息"></el-step>
			<el-step title="基本信息"></el-step>
			<el-step title="更多信息"></el-step>
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
				<el-form-item label="邮箱" prop="email">
					<el-col :span="4">
						<el-input v-model="userForm.email" clearable></el-input>
					</el-col>
				</el-form-item>
				<el-form-item label="注册类别">
					<el-col :span="4">
						<el-select v-model="userForm.role" placeholder="请选择">
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
		<div align="center" :style="{ marginTop: '12px',display: f1Display }">
			<el-form ref="farmer1Form" :model="farmer1Form" label-width="150px" size="small">
				<el-form-item label="姓名" prop="name">
					<el-col :span="4">
						<el-input v-model="farmer1Form.name" clearable></el-input>
					</el-col>
				</el-form-item>
				<el-form-item label="身份证号" prop="id_card">
					<el-col :span="4">
						<el-input v-model="farmer1Form.id_card" clearable></el-input>
					</el-col>
				</el-form-item>
				<el-form-item label="地址" prop="address">
					<el-col :span="4">
						<el-input v-model="farmer1Form.address" clearable></el-input>
					</el-col>
				</el-form-item>
				<el-form-item label="电话" prop="phone">
					<el-col :span="4">
						<el-input v-model="farmer1Form.phone" clearable></el-input>
					</el-col>
				</el-form-item>
			</el-form>
		</div>
		<div align="center" :style="{ marginTop: '12px',display: f2Display }">
			<el-form ref="farmer2Form" :model="farmer2Form" label-width="150px" size="small">
				<el-form-item label="农作物" prop="crop">
					<el-col :span="4">
						<el-input v-model="farmer2Form.crop" clearable></el-input>
					</el-col>
				</el-form-item>
				<el-form-item label="土地面积(亩)" prop="land">
					<el-col :span="4">
						<el-input v-model="farmer2Form.land" clearable></el-input>
					</el-col>
				</el-form-item>
				<el-form-item label="产能(千克/亩)" prop="productivity">
					<el-col :span="4">
						<el-input v-model="farmer2Form.productivity" clearable></el-input>
					</el-col>
				</el-form-item>
				<el-form-item label="生产成本(元)" prop="cost">
					<el-col :span="4">
						<el-input v-model="farmer2Form.cost" clearable></el-input>
					</el-col>
				</el-form-item>
				<el-form-item label="个人资产" prop="asset">
					<el-col :span="4">
						<el-input v-model="farmer2Form.asset" clearable></el-input>
					</el-col>
				</el-form-item>
			</el-form>
		</div>
		<div align="center" :style="{ marginTop: '12px',display: d1Display }">
			<el-form ref="dealer1Form" :model="dealer1Form" label-width="150px" size="small">
				<el-form-item label="经销商名称" prop="name">
					<el-col :span="4">
						<el-input v-model="dealer1Form.name" clearable></el-input>
					</el-col>
				</el-form-item>
				<el-form-item label="注册号" prop="reg_no">
					<el-col :span="4">
						<el-input v-model="dealer1Form.reg_no" clearable></el-input>
					</el-col>
				</el-form-item>
				<el-form-item label="地址" prop="address">
					<el-col :span="4">
						<el-input v-model="dealer1Form.address" clearable></el-input>
					</el-col>
				</el-form-item>
				<el-form-item label="电话" prop="phone">
					<el-col :span="4">
						<el-input v-model="dealer1Form.phone" clearable></el-input>
					</el-col>
				</el-form-item>
			</el-form>
		</div>
		<div align="center" :style="{ marginTop: '12px',display: d2Display }">
			<el-form ref="dealer2Form" :model="dealer2Form" label-width="150px" size="small">
				<el-form-item label="注册资金" prop="asset">
					<el-col :span="4">
						<el-input v-model="dealer2Form.asset" clearable></el-input>
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
	import Request from '../../utils/request'
	export default {
		data() {
			return {
				active: 0,
				options: [
					{
						value: 4,
						label: '农户'
					},
					{
						value: 5,
						label: '经销商'
					}
				],
				userForm: {
					username: '',
					password: '',
					email: '',
					role: 4
				},
				farmer1Form: {
					name: '',
					id_card: '',
					address: '',
					phone: '',
				},
				farmer2Form: {
					land: 0,
					crop: '',
					productivity: 0,
					cost: 0,
					asset: 0
				},
				dealer1Form: {
					name: '',
					reg_no: '',
					address: '',
					phone: '',
				},
				dealer2Form: {
					asset: 0
				},
				endForm: {
					code: ''
				},
			}
		},
		methods: {
			previous() {
				if(this.active > 0 && this.active < 4) {
					this.active--
				}
			},
			next() {
				if(this.active < 4) {
					this.active++
					if(this.active === 4) {
						this.register()
					}
				}
			},
			register() {
				let data = {
					user: this.userForm
				};
				if(this.userForm.role === 4) {
					data.info = this.farmer1Form;
					data.extend = this.farmer2Form;
				} else if(this.userForm.role === 5) {
					data.info = this.dealer1Form;
					data.extend = this.dealer2Form;
				}
				Request({
					url: '/admin/register',
					method: 'POST',
					data: data
				}).then(response => {
					this.$message({
						message: response.message,
						type: 'success'
					});
					setTimeout(function() {
						location.reload()
					}, 3000);
				}).catch(() => {
				})
			}
		},
		computed: {
			uDisplay: function() {
				if(this.active === 0) {
					return '';
				}
				return 'none';
			},
			f1Display: function() {
				if(this.active === 1 && this.userForm.role === 4) {
					return '';
				}
				return 'none';
			},
			f2Display: function() {
				if(this.active === 2 && this.userForm.role === 4) {
					return '';
				}
				return 'none';
			},
			d1Display: function() {
				if(this.active === 1 && this.userForm.role === 5) {
					return '';
				}
				return 'none';
			},
			d2Display: function() {
				if(this.active === 2 && this.userForm.role === 5) {
					return '';
				}
				return 'none';
			},
			eDisplay: function() {
				if(this.active >= 3) {
					return '';
				}
				return 'none';
			}
		}
	}
</script>

<style scoped>

</style>
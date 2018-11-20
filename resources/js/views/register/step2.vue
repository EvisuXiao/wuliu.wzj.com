<template>
	<div>
		<el-steps :active="active" process-status="finish" finish-status="success" space="30%" align-center>
			<el-step title="用户信息"></el-step>
			<el-step title="基本信息"></el-step>
			<el-step title="更多信息"></el-step>
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
		<div align="center" :style="{ marginTop: '12px',display: c1Display }">
			<el-form ref="company1Form" :model="company1Form" label-width="150px" size="small">
				<el-form-item label="名称" prop="name">
					<el-col :span="4">
						<el-input v-model="company1Form.name" clearable></el-input>
					</el-col>
				</el-form-item>
				<el-form-item label="注册号" prop="reg_no">
					<el-col :span="4">
						<el-input v-model="company1Form.reg_no" clearable></el-input>
					</el-col>
				</el-form-item>
				<el-form-item label="地址" prop="address">
					<el-col :span="4">
						<el-input v-model="company1Form.address" clearable></el-input>
					</el-col>
				</el-form-item>
				<el-form-item label="电话" prop="phone">
					<el-col :span="4">
						<el-input v-model="company1Form.phone" clearable></el-input>
					</el-col>
				</el-form-item>
			</el-form>
		</div>
		<div align="center" :style="{ marginTop: '12px',display: c2Display }">
			<el-form ref="company2Form" :model="company2Form" label-width="150px" size="small">
				<el-form-item label="接收原料" prop="material">
					<el-col :span="4">
						<el-input v-model="company2Form.material" clearable></el-input>
					</el-col>
				</el-form-item>
				<el-form-item label="制造工艺" prop="product">
					<el-col :span="4">
						<el-input v-model="company2Form.product" clearable></el-input>
					</el-col>
				</el-form-item>
				<el-form-item label="注册资金" prop="asset">
					<el-col :span="4">
						<el-input v-model="company2Form.asset" clearable></el-input>
					</el-col>
				</el-form-item>
			</el-form>
		</div>
		<div align="center" :style="{ marginTop: '12px',display: b1Display }">
			<el-form ref="bank1Form" :model="bank1Form" label-width="150px" size="small">
				<el-form-item label="银行名称" prop="name">
					<el-col :span="4">
						<el-input v-model="bank1Form.name" clearable></el-input>
					</el-col>
				</el-form-item>
				<el-form-item label="类别">
					<el-col :span="4">
						<el-select v-model="bank1Form.bank" placeholder="请选择">
							<el-option
									v-for="item in bankOptions"
									:key="item"
									:label="item"
									:value="item">
							</el-option>
						</el-select>
					</el-col>
				</el-form-item>
				<el-form-item label="支行" prop="branch">
					<el-col :span="4">
						<el-input v-model="bank1Form.branch" clearable></el-input>
					</el-col>
				</el-form-item>
				<el-form-item label="地址" prop="address">
					<el-col :span="4">
						<el-input v-model="bank1Form.address" clearable></el-input>
					</el-col>
				</el-form-item>
				<el-form-item label="电话" prop="phone">
					<el-col :span="4">
						<el-input v-model="bank1Form.phone" clearable></el-input>
					</el-col>
				</el-form-item>
			</el-form>
		</div>
		<div align="center" :style="{ marginTop: '12px',display: b2Display }">
			<el-form ref="bank2Form" :model="bank2Form" label-width="150px" size="small">
				<el-form-item label="银行资产" prop="asset">
					<el-col :span="4">
						<el-input v-model="bank2Form.asset" clearable></el-input>
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
						value: 2,
						label: '核心企业'
					},
					{
						value: 3,
						label: '银行'
					}
				],
				bankOptions: [
					'中国银行',
					'中国工商银行',
					'中国建设银行',
					'中国交通银行',
					'中国农业银行'
				],
				userForm: {
					username: '',
					password: '',
					email: '',
					role: 2
				},
				company1Form: {
					name: '',
					reg_no: '',
					address: '',
					phone: '',
				},
				company2Form: {
					material: '',
					product: '',
					asset: 0
				},
				bank1Form: {
					name: '',
					bank: '',
					branch: '',
					address: '',
					phone: '',
				},
				bank2Form: {
					asset: 0
				}
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
						this.register()
					}
				}
			},
			register() {
				let data = {
					user: this.userForm
				};
				if(this.userForm.role === 2) {
					data.info = this.company1Form;
					data.extend = this.company2Form;
				} else if(this.userForm.role === 3) {
					data.info = this.bank1Form;
					data.extend = this.bank2Form;
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
			c1Display: function() {
				if(this.active === 1 && this.userForm.role === 2) {
					return '';
				}
				return 'none';
			},
			c2Display: function() {
				if(this.active === 2 && this.userForm.role === 2) {
					return '';
				}
				return 'none';
			},
			b1Display: function() {
				if(this.active === 1 && this.userForm.role === 3) {
					return '';
				}
				return 'none';
			},
			b2Display: function() {
				if(this.active === 2 && this.userForm.role === 3) {
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
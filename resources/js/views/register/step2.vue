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
				<el-form-item label="邮箱" prop="mail">
					<el-col :span="4">
						<el-input v-model="userForm.mail" clearable></el-input>
					</el-col>
				</el-form-item>
				<el-form-item label="注册类别">
					<el-col :span="4">
						<el-select v-model="userForm.type" placeholder="请选择">
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
			<el-form ref="dealer1Form" :model="dealer1Form" label-width="150px" size="small">
				<el-form-item label="企业名称" prop="name">
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
			<el-form ref="bank1Form" :model="bank2Form" label-width="150px" size="small">
				<el-form-item label="银行资产" prop="asset">
					<el-col :span="4">
						<el-input v-model="bank2Form.asset" clearable></el-input>
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
					mail: '',
					type: 2
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
					reg_no: '',
					bank: '',
					branch: '',
					address: '',
					phone: '',
				},
				bank2Form: {
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
			c1Display: function() {
				if(this.active === 1 && this.userForm.type === 2) {
					return '';
				}
				return 'none';
			},
			c2Display: function() {
				if(this.active === 2 && this.userForm.type === 2) {
					return '';
				}
				return 'none';
			},
			b1Display: function() {
				if(this.active === 1 && this.userForm.type === 3) {
					return '';
				}
				return 'none';
			},
			b2Display: function() {
				if(this.active === 2 && this.userForm.type === 3) {
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
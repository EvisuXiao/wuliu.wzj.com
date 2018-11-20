<template>
	<div class="login-container">
		<el-form ref="loginForm" :model="loginForm" :rules="loginRules" class="login-form" auto-complete="on" label-position="left">
			<h3 class="title">供应链金融管理后台</h3>
			<el-form-item prop="username">
                <span class="svg-container svg-container_login"><svg-icon icon-class="user" /></span>
				<el-input v-model="loginForm.username" name="username" type="text" auto-complete="on" placeholder="请输入用户名" />
			</el-form-item>
			<el-form-item prop="password">
                <span class="svg-container"><svg-icon icon-class="password" /></span>
				<el-input
						type="password"
						v-model="loginForm.password"
						name="password"
						auto-complete="on"
						placeholder="请输入密码"
						@keyup.enter.native="handleLogin" />
			</el-form-item>
			<el-form-item>
				<router-link to="/register">
					<el-button type="primary" style="width:48%;">注 册</el-button>
				</router-link>
				<el-button :loading="loading" type="primary" style="width:48%;" @click.native.prevent="handleLogin">
					登 录
				</el-button>
			</el-form-item>
		</el-form>
	</div>
</template>

<script>
	import Request from '../../utils/request'
	import Farmer from '../farmer/index'
	import Company from '../company/index'
	import User from '../user/index'
	import Layout from '../layout/Layout'

	export default {
		data() {
			return {
				loginForm: {
					username: '',
					password: ''
				},
				loginRules: {
					username: [
						{ required: true, message: '请输入用户名', trigger: 'blur' },
						{ min: 5, message: '用户名长度不小于5个字符', trigger: 'blur' }
					],
					password: [
						{ required: true, message: '请输入密码', trigger: 'blur' },
						{ min: 5, message: '密码长度不小于5个字符', trigger: 'blur' }
					],
				},
				menu: {
					1: [
						{
							path: '/user',
							name: 'User',
							redirect: '/user/index',
							component: Layout,
							meta: {title: '用户', icon: 'link'},
							children: [
								{
									path: 'index',
									component: User,
									meta: {title: '用户列表', icon: 'link'}
								}
							]
						},
					],
					2: [
						{
							path: '/company',
							name: 'Company',
							redirect: '/company/index',
							component: Layout,
							meta: {title: '企业', icon: 'link'},
							children: [
								{
									path: 'index',
									component: Company,
									meta: {title: '企业列表', icon: 'link'}
								}
							]
						},
					],
					3: [],
					4: [
						{
							path: '/farmer',
							name: 'Farmer',
							redirect: '/farmer/index',
							component: Layout,
							meta: {title: '农户', icon: 'link'},
							children: [
								{
									path: 'index',
									component: Farmer,
									meta: {title: '农户列表', icon: 'link'}
								}
							]
						},
					],
					5: [

					]
				},
				loading: false,
			}
		},
		methods: {
			handleLogin() {
				this.$refs.loginForm.validate(valid => {
					if (valid) {
						this.loading = true
						Request({
							url: '/admin/login',
							method: 'POST',
							data: this.loginForm
						}).then(response => {
							this.$router.addRoutes(this.menu[response.data.role])
							this.$router.push({path: '/'})
							this.loading = false
						}).catch(() => {
							this.loading = false
						})
					}
				})

			}
		}
	}
</script>

<style rel="stylesheet/scss" lang="scss">
	$bg: #2d3a4b;
	$light_gray: #eee;

	/* reset element-ui css */
	.login-container {
		.el-input {
			display: inline-block;
			height: 47px;
			width: 85%;
			input {
				background: transparent;
				border: 0px;
				-webkit-appearance: none;
				border-radius: 0px;
				padding: 12px 5px 12px 15px;
				color: $light_gray;
				height: 47px;
				&:-webkit-autofill {
					-webkit-box-shadow: 0 0 0px 1000px $bg inset !important;
					-webkit-text-fill-color: #fff !important;
				}
			}
		}
		.el-form-item {
			border: 1px solid rgba(255, 255, 255, 0.1);
			background: rgba(0, 0, 0, 0.1);
			border-radius: 5px;
			color: #454545;
		}
	}

</style>

<style rel="stylesheet/scss" lang="scss" scoped>
	$bg: #2d3a4b;
	$dark_gray: #889aa4;
	$light_gray: #eee;
	.login-container {
		position: fixed;
		height: 100%;
		width: 100%;
		background-color: $bg;
		.login-form {
			position: absolute;
			left: 0;
			right: 0;
			width: 520px;
			padding: 35px 35px 15px 35px;
			margin: 120px auto;
		}
		.tips {
			font-size: 14px;
			color: #fff;
			margin-bottom: 10px;
			span {
				&:first-of-type {
					margin-right: 16px;
				}
			}
		}
		.svg-container {
			padding: 6px 5px 6px 15px;
			color: $dark_gray;
			vertical-align: middle;
			width: 30px;
			display: inline-block;
			&_login {
				font-size: 20px;
			}
		}
		.title {
			font-size: 26px;
			font-weight: 400;
			color: $light_gray;
			margin: 0px auto 40px auto;
			text-align: center;
			font-weight: bold;
		}
		.show-pwd {
			position: absolute;
			right: 10px;
			top: 7px;
			font-size: 16px;
			color: $dark_gray;
			cursor: pointer;
			user-select: none;
		}
	}
</style>

<template>
	<div class="app-container">
		<div class="dashboard-text">用户名: {{ username }}</div>
		<div class="dashboard-text">角色: {{ role }}</div>
		<div class="dashboard-text">邮箱: {{ email }}</div>
		<div class="dashboard-text">最近登录时间: {{ lastLogin }}</div>
	</div>
</template>

<script>
	import {getUserInfo} from '../../utils/auth'
	export default {
		data() {
			return {
				username: '',
				role: '',
				email: '',
				lastLogin: '',
				roleMap: {
					1: '管理员',
					2: '核心企业',
					3: '银行',
					4: '农户',
					5: '经销商'
				}
			}
		},
		created() {
			const info = getUserInfo();
			if(info) {
				this.username = info.username;
				this.role = this.roleMap[info.role];
				this.email = info.email;
				this.lastLogin = info.logged_at;
			}
		},
	}
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
	.dashboard {
		&-container {
			margin: 30px;
		}
		&-text {
			font-size: 30px;
			line-height: 46px;
		}
	}
</style>

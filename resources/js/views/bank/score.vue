<template>
	<div class="app-container">
		<el-form ref="form" :model="form" label-width="150px" size="small">
			<el-form-item label="企业销量" prop="company_sales">
				<el-col :span="4">
					<el-input disabled v-model="form.company_sales"></el-input>
				</el-col>
			</el-form-item>
			<el-form-item label="企业资产" prop="company_asset">
				<el-col :span="4">
					<el-input disabled v-model="form.company_asset"></el-input>
				</el-col>
			</el-form-item>
			<el-form-item label="企业负债" prop="company_debt">
				<el-col :span="4">
					<el-input disabled v-model="form.company_debt"></el-input>
				</el-col>
			</el-form-item>
			<el-form-item label="土地面积" prop="farmer_land">
				<el-col :span="4">
					<el-input disabled v-model="form.farmer_land"></el-input>
				</el-col>
			</el-form-item>
			<el-form-item label="产出能力" prop="farmer_productivity">
				<el-col :span="4">
					<el-input disabled v-model="form.farmer_productivity"></el-input>
				</el-col>
			</el-form-item>
			<el-form-item label="个人资产" prop="farmer_asset">
				<el-col :span="4">
					<el-input disabled v-model="form.farmer_asset"></el-input>
				</el-col>
			</el-form-item>
			<el-form-item label="人工效率" prop="apply_efficiency">
				<el-col :span="4">
					<el-input disabled v-model="form.apply_efficiency"></el-input>
				</el-col>
			</el-form-item>
			<el-form-item label="订单数量" prop="apply_quantity">
				<el-col :span="4">
					<el-input disabled v-model="form.apply_quantity"></el-input>
				</el-col>
			</el-form-item>
			<el-form-item label="单价" prop="apply_price">
				<el-col :span="4">
					<el-input disabled v-model="form.apply_price"></el-input>
				</el-col>
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
					company_sales: 0,
					company_asset: 0,
					company_debt: 0,
					farmer_land: 0,
					farmer_productivity: 0,
					farmer_asset: 0,
					apply_efficiency: 0,
					apply_quantity: 0,
					apply_price: 0
				},
			}
		},
		created() {
			this.getInfo();
		},
		methods: {
			getInfo() {
				request({
					url: '/bank/score'
				}).then(response => {
					const data = response.data;
					for(let i in this.form) {
						if(data.hasOwnProperty(i)) {
							this.form[i] = data[i];
						}
					}
				})
			}
		}
	}
</script>

<style scoped>

</style>
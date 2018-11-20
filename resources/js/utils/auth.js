import Cookies from 'js-cookie'

const TokenKey = 'XSRF-TOKEN'
const UserKey = 'userInfo'

export function getToken() {
	return Cookies.get(TokenKey)
}

export function getUserInfo() {
	return Cookies.get(UserKey)
}
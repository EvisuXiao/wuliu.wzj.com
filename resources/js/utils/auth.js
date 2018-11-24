import Cookies from 'js-cookie'

const TokenKey = 'XSRF-TOKEN'
const UserKey = 'userInfo'

export function getToken() {
	return Cookies.get(TokenKey)
}

export function getUserInfo() {
	const info = Cookies.get(UserKey);
	return info ? JSON.parse(info) : '';
}
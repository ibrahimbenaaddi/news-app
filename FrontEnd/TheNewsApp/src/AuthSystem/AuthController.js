import axios from 'axios';

export default class AuthController {
    async login(data) {
        const response = await axios({
            method: 'post',
            url: 'http://newsapp.op/api/admin/login',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            data: data

        }).catch(function (error) {
            return error.response;
        });
        if (response.data.status) {
            localStorage.setItem('token', response.data.token);
        }
        return response.data;

    }
    async logout() {
        const token = localStorage.getItem('token');
        const response = await axios({
            method: 'post',
            url: 'http://newsapp.op/api/admin/logout',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }

        }).catch(function (error) {
            return error.response;
        });
        if (response.data.status) {
            localStorage.removeItem('token');
        }
        return response.data;

    }
}
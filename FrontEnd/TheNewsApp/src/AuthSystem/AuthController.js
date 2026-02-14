import api from './api.js'

export default class AuthController {

    async login(data) {
        const response = await api({
            method: 'post',
            // url: '/backend/api/admin/login', // for sanctum
            url: '/backend/api/jwt/admin/login', // for JWT
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            data: data

        }).catch(function (error) {
            return error.response;
        });
        if (response.data.status) {
            localStorage.setItem('admin', JSON.stringify(response.data.admin));
        }
        return response.data;

    }
    async logout() {
        const response = await api({
            method: 'post',
            // url: '/backend/api/admin/logout', // fro sanctum
            url: '/backend/api/jwt/admin/logout', // for JWT
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            }

        }).catch(function (error) {
            // for Sanctum
            // if (error.response.status === 401 || error.response.status === 419) {
            //     window.location.reload();
            //     return;
            // }
            return error.response 
        });

        if (response.data.status) {
            localStorage.removeItem('admin');
        }
        return response.data;

    }
}
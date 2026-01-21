import axios from 'axios';
axios.defaults.withCredentials = true;
// for Sanctum
// function getCoockies() {
//     let csrfToken;
//     let cookies = document.cookie;
//     let arrCookies = cookies.split(';');
//     arrCookies.forEach(element => {
//         if (element.includes('XSRF-TOKEN')) {
//             csrfToken = element.split('=')[1];
//         }
//     })
//     if (csrfToken) {
//         return { find: true, xsrf: csrfToken }
//     }
//     return { find: false, xcsrf: null }
// }

// axios.interceptors.request.use(async (req) => {
//     if (req.method === "get") {
//         return req;
//     }
//     let { find, xcsrf } = getCoockies();
//     if (!find) {
//         await axios.get("/backend/sanctum/csrf-cookie", { withCredentials: true });
//         xcsrf = getCoockies().xcsrf
//     }
//     req.headers["X-XSRF-TOKEN"] = xcsrf;
//     return req;
// });

export default class AuthController {

    async login(data) {
        const response = await axios({
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
        const response = await axios({
            method: 'post',
            // url: '/backend/api/admin/logout', // fro sanctum
            url: '/backend/api/jwt/admin/logout', // for JWT
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            }

        }).catch(function (error) {
            if (error.response.status === 401 || error.response.status === 419) {
                window.location.reload();
                return;
            }
            return error.response;
        });
        if (response.data.status) {
            localStorage.removeItem('admin');
        }
        return response.data;

    }
}
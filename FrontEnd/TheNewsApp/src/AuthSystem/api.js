import axios from 'axios'

const api = axios.create({
    withCredentials: true,
});

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

// api.interceptors.request.use(async (req) => {
//     if (req.method === "get") {
//         return req;
//     }
//     let { find, xcsrf } = getCoockies();
//     if (!find) {
//         await api.get("/backend/sanctum/csrf-cookie", { withCredentials: true });
//         xcsrf = getCoockies().xcsrf
//     }
//     req.headers["X-XSRF-TOKEN"] = xcsrf;
//     return req;
// });

// for refreshToken

api.interceptors.response.use((res) => {
    return res;
}, async (error) => {
    const status = error.response.status;
    const url = error.config.url;
    if (status === 401 && url !== '/backend/api/jwt/admin/refreshToken' && url !== '/backend/api/jwt/admin/amIAuth') {
        try {
            await api({ method: 'get', url: '/backend/api/jwt/admin/refreshToken' });
        } catch {
            window.location.reload();
        }
        return Promise.reject({ response: { data: { status: false, message: 'Failed ,Try Again' } } });
    }

    return Promise.reject(error);
}
);

export default api;
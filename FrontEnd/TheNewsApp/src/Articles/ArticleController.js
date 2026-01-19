import axios from 'axios';
axios.defaults.withCredentials = true;

function getCoockies() {
    let csrfToken;
    let cookies = document.cookie;
    let arrCookies = cookies.split(';');
    arrCookies.forEach(element => {
        if (element.includes('XSRF-TOKEN')) {
            csrfToken = element.split('=')[1];
        }
    })
    if (csrfToken) {
        return { find: true, xsrf: csrfToken }
    }
    return { find: false, xcsrf: null }
}

axios.interceptors.request.use(async (req) => {

    if (req.method === "get") {
        return req;
    }
    let { find, xcsrf } = getCoockies();
    if (!find) {
        await axios.get("/backend/sanctum/csrf-cookie", { withCredentials: true });
        xcsrf = getCoockies().xcsrf
    }
    req.headers["X-XSRF-TOKEN"] = xcsrf;
    return req;
});



export default class ArticleContorller {
    async getArticles(page) {
        try {
            const response = await axios({
                method: 'get',
                url: `/backend/api/articles?page=${page}`,
                headers: {
                    'Content-type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            if (response.data.status) {
                return response.data
            }
            return false
        } catch {
            return false
        }


    }

    async getArticleById(articleID, page = 1) {
        try {
            const response = await axios({
                method: 'get',
                url: `/backend/api/articles/${articleID}?page=${page}`,
                headers: {
                    'Content-type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            if (response.data.status) {
                return response.data
            }
            return false
        } catch {
            return false
        }
    }

    async getArticleByTitle(title, page = 1) {
        try {
            const response = await axios({
                method: 'get',
                url: `/backend/api/articles/title/${title}?page=${page}`,
                headers: {
                    'Content-type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            if (response.data.status) {
                return response.data
            }
            return false
        } catch {
            return false
        }
    }

    async updateArticle(articleData, articleID) {
        articleData.append('_method', 'PATCH');
        const response = await axios({
            method: 'post',
            url: `/backend/api/admin/edit/articles/${articleID}`,
            headers: {
                'Content-Type': 'multipart/form-data'
            },
            data: articleData
        }).catch(function (error) {
            if (error.response.status === 401 || error.response.status === 419) {
                window.location.reload();
                return;
            }
            return error.response
        })
        return response.data
    }

    async storeArticle(articleData) {
        const response = await axios({
            method: 'post',
            url: `/backend/api/admin/articles`,
            headers: {
                'Content-Type': 'multipart/form-data'
            },
            data: articleData
        }).catch(function (error) {
            if (error.response.status === 401 || error.response.status === 419) {
                window.location.reload();
                return;
            }
            return error.response
        });
        return response.data
    }

    async deleteArticle(articleID) {
        const response = await axios({
            method: 'delete',
            url: `/backend/api/admin/delete/articles/${articleID}`,
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        }).catch(function (error) {
            if (error.response.status === 401 || error.response.status === 419) {
                window.location.reload();
                return;
            }
            return error.response;
        });
        return response.data
    }
}
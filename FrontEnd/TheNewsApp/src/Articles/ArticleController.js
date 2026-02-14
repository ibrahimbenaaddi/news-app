import api from '../AuthSystem/api.js'

export default class ArticleContorller {
    async getArticles(page) {
        try {
            const response = await api({
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
            const response = await api({
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
            const response = await api({
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
        const response = await api({
            method: 'post',
            // url: `/backend/api/admin/edit/articles/${articleID}`, // for Sanctum
            url: `/backend/api/jwt/admin/edit/articles/${articleID}`, // for JWT
            headers: {
                'Content-Type': 'multipart/form-data'
            },
            data: articleData
        }).catch(function (error) {
            // for sanctum
            // if (error.response.status === 401 || error.response.status === 419) {
            //     window.location.reload();
            //     return;
            // }
            return error.response
        })
        return response.data
    }

    async storeArticle(articleData) {
        const response = await api({
            method: 'post',
            // url: `/backend/api/admin/articles`, // for Sanctum
            url: `/backend/api/jwt/admin/articles`, // fro JWT
            headers: {
                'Content-Type': 'multipart/form-data'
            },
            data: articleData
        }).catch(function (error) {
            // for Sanctum
            // if (error.response.status === 401 || error.response.status === 419) {
            //     window.location.reload();
            //     return;
            // }
            return error.response
        });
        return response.data
    }

    async deleteArticle(articleID) {
        const response = await api({
            method: 'delete',
            // url: `/backend/api/admin/delete/articles/${articleID}`, // for Sanctum
            url: `/backend/api/jwt/admin/delete/articles/${articleID}`, // for JWT
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        }).catch(function (error) {
            // for Sanctum
            // if (error.response.status === 401 || error.response.status === 419) {
            //     window.location.reload();
            //     return;
            // }
            return error.response;
        });
        return response.data
    }
}
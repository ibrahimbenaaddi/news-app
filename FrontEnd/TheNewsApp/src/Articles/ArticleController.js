import api from '../AuthSystem/api.js'

export async function getArticles(page, search = null) {
    try {
        let apiUrl = `/backend/api/v1/articles?page=${page}`;
        if (search) {
            apiUrl += `&search=${encodeURIComponent(search)}`;
        }
        const response = await api({
            method: 'get',
            url: apiUrl,
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

export async function getArticleById(articleID) {
    try {
        const response = await api({
            method: 'get',
            url: `/backend/api/v1/articles/${articleID}`,
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


export async function updateArticle(articleData, articleID) {
    articleData._method = 'PATCH';
    const response = await api({
        method: 'post',
        // url: `/backend/api/v1/admin/articles/${articleID}`, // for Sanctum
        url: `/backend/api/v1/jwt/admin/articles/${articleID}`, // for JWT
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

export async function storeArticle(articleData) {
    const response = await api({
        method: 'post',
        // url: `/backend/api/v1/admin/articles`, // for Sanctum
        url: `/backend/api/v1/jwt/admin/articles`, // fro JWT
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

export async function destroyArticle(articleID) {
    const response = await api({
        method: 'delete',
        // url: `/backend/api/v1/admin/articles/${articleID}`, // for Sanctum
        url: `/backend/api/v1/jwt/admin/articles/${articleID}`, // for JWT
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

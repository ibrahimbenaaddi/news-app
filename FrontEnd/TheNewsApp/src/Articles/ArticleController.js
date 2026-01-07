import axios from 'axios';

export default class ArticleContorller {
    async getArticles(page) {
        try {
            const response = await axios({
                method: 'get',
                url: `http://newsapp.op/api/articles?page=${page}`,
                headers: {
                    'Content-type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            if (response.data.status) {
                return response.data
            }
            return false
        } catch (error) {
            console.error(error.message);
            return false
        }


    }

    async getArticleById(articleID,page = 1) {
        try {
            const response = await axios({
                method: 'get',
                url: `http://newsapp.op/api/articles/${articleID}?page=${page}`,
                headers: {
                    'Content-type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            if (response.data.status) {
                return response.data
            }
            return false
        } catch (error) {
            console.error(error.message);
            return false
        }
    }

    async getArticleByTitle(title, page = 1 ) {
        try {
            const response = await axios({
                method: 'get',
                url: `http://newsapp.op/api/articles/title/${title}?page=${page}`,
                headers: {
                    'Content-type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            if (response.data.status) {
                return response.data
            }
            return false
        } catch (error) {
            console.error(error.message);
            return false
        }
    }
}
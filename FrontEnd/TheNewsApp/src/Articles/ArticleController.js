import axios from 'axios';

export default class ArticleContorller
{
    async getArticles()
    {
        try{
            const response = await axios({
                method : 'get',
                url : 'http://newsapp.op/api/articles',
                headers : {
                    'Content-type' : 'application/json',
                    'Accept' : 'application/json'
                }
            })
            if(response.data.status){
                return response.data.articles
            }
            return false
        }catch(error){
            console.error(error.message);
            return false
        }


    }
}
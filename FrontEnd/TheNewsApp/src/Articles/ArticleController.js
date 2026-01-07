import axios from 'axios';

export default class ArticleContorller
{
    async getArticles(page)
    {
        try{
            const response = await axios({
                method : 'get',
                url : `http://newsapp.op/api/articles?page=${page}`,
                headers : {
                    'Content-type' : 'application/json',
                    'Accept' : 'application/json'
                }
            })
            if(response.data.status){
                return response.data
            }
            return false
        }catch(error){
            console.error(error.message);
            return false
        }


    }
}
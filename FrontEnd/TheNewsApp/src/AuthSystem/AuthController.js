import axios from 'axios';

export default class AuthController{
    async login(data){
        const response = await axios({
                method : 'post',
                url : 'http://newsapp.op/api/admin/login',
                headers : {
                    'Content-Type' : 'application/json',
                    'Accept' : 'application/json'
                },
                data : data

        }).catch(function (error){
            return error.response;
        });
        return response.data;

    }
}
import { Navigate, Outlet } from 'react-router-dom'
import { useState, useEffect } from 'react';
import axios from 'axios'
axios.defaults.withCredentials = true;

export default function RouteProtected() {
    const [auth, setAuth] = useState(false);
    const [ isLoading , setIsLoading ] = useState(true);

    const checkIfAuth = async () => {
        axios({
            // url: '/backend/api/admin/amIAuth', // fro Sanctum
            url: '/backend/api/jwt/admin/amIAuth', // fro JWT
            method: 'get',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        }).then(response => { setAuth(response.data.status); setIsLoading(false)})
            .catch(function () { setAuth(false) ; setIsLoading(false)});

    };
    useEffect(() => {
        checkIfAuth();
    }, [])

    if(isLoading){
        return <h1>isLoading...</h1>
    }
    return auth ? <Outlet /> : <Navigate to="/Admin/login" replace /> 

}
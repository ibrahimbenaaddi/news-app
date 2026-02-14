import { Navigate, Outlet } from 'react-router-dom'
import { useState, useEffect } from 'react';
import Login from '../Components/Admin/Login.jsx'
import api from './api.js'

export default function RouteProtected() {
    const [auth, setAuth] = useState(false);
    const [ isLoading , setIsLoading ] = useState(true);

    const checkIfAuth = async () => {
        api({
            // url: '/backend/api/admin/amIAuth', // for Sanctum
            url: '/backend/api/jwt/admin/amIAuth', // for JWT
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
    return auth ? <Navigate to='/Admin/Dashboard' /> : <Login />

}
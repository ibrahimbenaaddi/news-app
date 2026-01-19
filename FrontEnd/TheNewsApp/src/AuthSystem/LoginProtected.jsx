import { Navigate, Outlet } from 'react-router-dom'
import { useState, useEffect } from 'react';
import Login from '../Components/Admin/Login.jsx'
import axios from 'axios'
axios.defaults.withCredentials = true;

export default function RouteProtected() {
    const [auth, setAuth] = useState(false);
    const [ isLoading , setIsLoading ] = useState(true);

    const checkIfAuth = async () => {
        axios({
            url: '/backend/api/admin/amIAuth',
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
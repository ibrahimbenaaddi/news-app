import { useState, useEffect } from 'react'
import { AuthContext } from './AuthContext.jsx'

export default function AuthProvider({ children }) {
    const [auth, setAuth] = useState(false);

    useEffect(() => {
        const auth = JSON.parse(localStorage.getItem("Authentication"));
        return () => setAuth(auth);
    }, [])
    return <AuthContext.Provider value={{ auth, setAuth }}>
        {children}
    </AuthContext.Provider>
}
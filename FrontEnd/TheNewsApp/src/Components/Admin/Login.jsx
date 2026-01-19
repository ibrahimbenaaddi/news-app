import Styled from 'Styled-Components'
import { useState, useEffect } from 'react'
import { useNavigate } from 'react-router-dom'
import AuthController from '../../AuthSystem/AuthController.js'

export default function Login() {

    const AuthService = new AuthController();
    const navigate = useNavigate();
    const [loginForm, setLoginForm] = useState({ email: '', password: '' });
    const [error, setError] = useState([]);

    useEffect(() => { document.title = 'NewsApp - Login' }, [])

    // handleInputs
    const handleInputs = (email, pass) => {
        if (!email.trim()) {
            setError(prev => [...prev, 'email is required']);
            return false
        }
        if (!pass.trim()) {
            setError(prev => [...prev, 'paasword is required']);
            return false;
        }
        setLoginForm({ email: email, password: pass })
        return true
    }

    useEffect(() => {
        if (error.length === 0) return;
        const timeout = setTimeout(() => setError([]), 3500);
        return () => clearTimeout(timeout);
    }, [error])

    const login = async (e) => {
        e.preventDefault();
        setError([])
        if (handleInputs(loginForm.email, loginForm.password)) {
            const response = await AuthService.login(loginForm);
            if (response.status) {
                navigate('/Admin/Dashboard');
                return;
            }
            setError(prev => [...prev, response.message]);
        }
    }

    return <StyleComponent>
        <div className="login-container">
            <div className="logo-container">
                <div className="logo">
                    <i className="fas fa-newspaper"></i>
                    <h1>NewsApp</h1>
                </div>
            </div>
            <div className="login-card">
                <div className="card-header">
                    <h2>Welcome Back</h2>
                    <p>Sign in to your admin account</p>
                </div>
                {
                    error.length > 0 && <div className="alert alert-danger errorList mb-2">
                        <ul style={{ listStyleType: 'none' }}>
                            {
                                error.map((err, index) => <li className="p-2 mb-2" key={index} >{err}</li>)
                            }
                        </ul>
                    </div>
                }
                <form id="loginForm" onSubmit={login}>
                    <div className="form-group">
                        <label className="form-label" htmlFor="email">email Address</label>
                        <div className="input-with-icon">
                            <i className="fas fa-envelope input-icon"></i>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                placeholder="Enter your email"
                                className="form-input"
                                value={loginForm.email}
                                onChange={(e) => setLoginForm({ ...loginForm, email: e.target.value })}
                            />
                        </div>
                    </div>

                    <div className="form-group">
                        <label className="form-label" htmlFor="password">password</label>
                        <div className="input-with-icon">
                            <i className="fas fa-lock input-icon"></i>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                className="form-input"
                                placeholder="Enter your password"
                                onChange={(e) => setLoginForm({ ...loginForm, password: e.target.value })}
                            />
                        </div>
                    </div>
                    <button type="submit" className="submit-btn" id="submitBtn">
                        <span>Sign In</span>
                        <i className="fas fa-sign-in-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </StyleComponent>
}

const StyleComponent = Styled.div`
 * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', system-ui, sans-serif;
}

body {
    background-color: #121212;
    color: #e0e0e0;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    background-image:
        radial-gradient(circle at 20% 80%, rgba(58, 134, 255, 0.1) 0%, transparent 20%),
        radial-gradient(circle at 80% 20%, rgba(108, 99, 255, 0.1) 0%, transparent 20%);
}

/* Main content wrapper */
.content-wrapper {
    flex: 1;
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

/* Logo Container */
.logo-container {
    display: flex;
    width: 100%;
    text-align: center;
    margin-bottom: 2.5rem;
    justify-content: center;
}

.logo {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 1rem;
}

.logo i {
    font-size: 2.5rem;
    color: #3a86ff;
}

.logo h1 {
    font-size: 2.2rem;
    font-weight: 700;
    background: linear-gradient(to right, #3a86ff, #6c63ff);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

/* Login Container */
.login-container {
    width: 100%;
    animation: fadeIn 0.8s ease-out;
}

/* Fade animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Login Card */
.login-card {
    width: 100%;
    max-width: 670px;
    margin: 0 auto;
    background: linear-gradient(to bottom right, #2d2d2d, #1e1e1e);
    border-radius: 16px;
    padding: 2.5rem;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.card-header {
    text-align: center;
    margin-bottom: 2rem;
}

.card-header h2 {
    font-size: 1.8rem;
    margin-bottom: 0.5rem;
    color: #fff;
}

.card-header p {
    color: #888;
    font-size: 0.95rem;
}

/* Form Styles */
.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    margin-bottom: 0.6rem;
    font-weight: 600;
    color: #e0e0e0;
    font-size: 0.95rem;
}

.input-with-icon {
    position: relative;
}

.input-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #888;
    font-size: 1.1rem;
}

.form-input {
    width: 100%;
    padding: 1rem 1rem 1rem 3rem;
    background-color: #2d2d2d;
    border: 2px solid #444;
    border-radius: 10px;
    color: #e0e0e0;
    font-size: 1rem;
    transition: all 0.3s;
}

.form-input:focus {
    outline: none;
    border-color: #3a86ff;
    box-shadow: 0 0 0 3px rgba(58, 134, 255, 0.2);
}

.form-input::placeholder {
    color: #888;
}

/* Submit Button */
.submit-btn {
    width: 100%;
    padding: 1rem;
    background: linear-gradient(to right, #3a86ff, #6c63ff);
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-bottom: 1.5rem;
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(58, 134, 255, 0.3);
}

.submit-btn:active {
    transform: translateY(0);
}


/* Responsive Design */
@media (max-width: 480px) {
    .login-card {
        padding: 2rem 1.5rem;
    }

    .logo h1 {
        font-size: 1.8rem;
    }

    .logo i {
        font-size: 2rem;
    }

    .footer {
        padding: 1.5rem 1rem;
    }
}

`;
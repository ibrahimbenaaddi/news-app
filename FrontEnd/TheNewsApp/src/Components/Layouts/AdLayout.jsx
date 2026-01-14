import Styled from 'Styled-Components'
import { Outlet , Link , useNavigate} from 'react-router-dom'
import { useEffect , useState} from 'react'
import AuthController from '../../AuthSystem/AuthController.js'

export default function AdLayout() {
    const [error , setError] = useState(null);
    const AuthService = new AuthController();
    const navigate = useNavigate();
    useEffect(() => { document.title = 'NewsApp Admin Dashboard' }, [])
    useEffect(() => { 
        error && alert(`Failed To Logout : ${error}`) ;
        return () => setError(null);
        }, [error]);

    
    // logout
    const logout = async (e) => {
        e.preventDefault();
        const response = await AuthService.logout();
        if(response.status){
            navigate('/Admin/login');
            return;
        };
        setError(response.message)
    }
    return <StyleComponent>
        <aside className="sidebar">
            <div className="logo">
                <i className="fas fa-newspaper"></i>
                <div>
                    <h1>NewsApp</h1>
                    <span>Admin Dashboard</span>
                </div>
            </div>

            <ul className="menu">
                <div className="menu-section">Main</div>
                <li><Link to="/Admin/Dashboard" className="menu-item"><i className="fas fa-tachometer-alt"></i> <span>Dashboard</span></Link>
                </li>
                <li><Link to="/Admin/Add/Article" className="menu-item"><i className="fas fa-edit"></i> <span>Write Article</span></Link></li>
                <li>
                    <form onSubmit={logout}>
                        <button type="submit" className="menu-item" style={{
                            background: 'none',
                            border: 'none',
                            width: '100%',
                            textAlign: 'left',
                            cursor: 'pointer'
                        }}>
                            <i className="fas fa-sign-out-alt"></i> <span>Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </aside>

        <main className="main-content">
            <div className="top-bar">
                <div className="page-title">
                    <h2>Dashboard Overview</h2>
                    <p>Welcome back, Admin! Here's what's happening with your news app today.</p>
                </div>

                <div className="user-info">
                    <div className="user-avatar">
                        <i className="fa-solid fa-user-tie"></i>
                    </div>
                    <div>
                        <div className="user-name">Admin User</div>
                        <div className="user-role">Administrator</div>
                    </div>
                </div>
            </div>

            <Outlet />
        </main>
    </StyleComponent>
}

const StyleComponent = Styled.div`
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', system-ui, sans-serif;
    }

    :root {
        --border-color: #444;
    }

    /* Global Body Styles */
    body {
        background-color: #121212;
        color: #e0e0e0;
        min-height: 100vh;
    }

    /* Sidebar */
    .sidebar {
        width: 250px;
        background-color: #1e1e1e;
        padding: 1.5rem 0;
        box-shadow: 4px 0 12px rgba(0, 0, 0, 0.4);
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        overflow-y: auto;
        z-index: 100;
    }

    .logo {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 0 1.5rem 2rem;
        border-bottom: 1px solid #444;
        margin-bottom: 1.5rem;
    }

    .logo i {
        font-size: 2rem;
        color: #3a86ff;
    }

    .logo h1 {
        font-size: 1.5rem;
        font-weight: 700;
        background: linear-gradient(to right, #3a86ff, #6c63ff);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .logo span {
        font-size: 0.8rem;
        color: #888;
    }

    .menu {
        list-style: none;
    }

    .menu-section {
        padding: 1rem 1.5rem 0.5rem;
        font-size: 0.8rem;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .menu-item {
        padding: 0.9rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 12px;
        color: #e0e0e0;
        text-decoration: none;
        transition: all 0.3s;
        border-left: 4px solid transparent;
    }

    .menu-item:hover,
    .menu-item.active {
        background-color: rgba(58, 134, 255, 0.1);
        border-left-color: #3a86ff;
        color: #3a86ff;
    }

    .menu-item i {
        width: 20px;
        text-align: center;
    }

    /* Main Content */
    .main-content {
        margin-left: 250px;
        padding: 2rem;
        min-height: 100vh;
        width: calc(100% - 250px);
        display: flex;
        flex-direction: column;
    }

    /* Top Bar */
    .top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #444;
    }

    .page-title h2 {
        font-size: 1.8rem;
        color: #fff;
    }

    .page-title p {
        color: #888;
        margin-top: 0.3rem;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .user-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(to right, #3a86ff, #6c63ff);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: white;
    }

    .user-name {
        font-weight: 600;
    }

    .user-role {
        font-size: 0.85rem;
        color: #888;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    .stat-card {
        background: linear-gradient(to bottom right, #2d2d2d, #1e1e1e);
        border-radius: 12px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.4);
        transition: transform 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
    }

    .stat-icon.articles {
        background: rgba(58, 134, 255, 0.15);
        color: #3a86ff;
    }

    .stat-info h3 {
        font-size: 1.8rem;
    }

    .stat-info p {
        font-size: 0.9rem;
        color: #888;
    }

    /* Dashboard Sections */
    .dashboard-section {
        margin-bottom: 2.5rem;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .section-title {
        font-size: 1.4rem;
        color: #fff;
    }

    /* Buttons */
    .btn {
        padding: 0.8rem 1.8rem;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
        font-size: 1rem;
    }

    .btn-primary {
        background-color: #3a86ff;
        color: white;
    }

    .btn-primary:hover {
        background-color: #2667cc;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(58, 134, 255, 0.3);
    }

    .btn-secondary {
        background-color: transparent;
        border: 1px solid #444;
        color: #e0e0e0;
    }

    .btn-secondary:hover {
        background-color: rgba(255, 255, 255, 0.05);
    }

    .btn-success {
        background-color: #4CAF50;
        color: white;
    }

    .btn-success:hover {
        background-color: #3d8b40;
    }

    /* Tables */
    .table-container {
        background: linear-gradient(to bottom right, #2d2d2d, #1e1e1e);
        border-radius: 12px;
        overflow-x: auto;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
    }

    table {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
    }

    th,
    td {
        word-wrap: break-word;
        overflow-wrap: break-word;
        word-break: break-word;
        padding: 1rem;
        border-bottom: 1px solid #444;
    }

    th {
        font-size: 0.85rem;
        color: #888;
        text-transform: uppercase;
    }

    tr:hover {
        background: rgba(255, 255, 255, 0.03);
    }

    .action-btns {
        display: flex;
        gap: 0.5rem;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.05);
        border: none;
        color: #e0e0e0;
        cursor: pointer;
        transition: 0.3s;
    }

    .action-btn.edit:hover {
        color: #3a86ff;
    }

    .action-btn.delete:hover {
        color: #F44336;
    }

    /* Footer */
    .footer {
        margin-top: auto;
        text-align: center;
        padding: 1.5rem;
        color: #888;
        border-top: 1px solid #444;
        background: #1e1e1e;
    }

    /* Article Creation Specific Styles */
    .container {
        margin: 0 auto;
        width: 100%;
    }

    /* Article Form */
    .article-form {
        background-color: #1e1e1e;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        flex: 1;
    }

    /* Form Groups */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #e0e0e0;
    }

    .form-input,
    .form-textarea,
    .form-select {
        width: 100%;
        padding: 1rem;
        background-color: #2d2d2d;
        border: 2px solid #444;
        border-radius: 8px;
        color: #e0e0e0;
        font-size: 1rem;
        transition: all 0.3s;
    }

    .form-input:focus,
    .form-textarea:focus,
    .form-select:focus {
        outline: none;
        border-color: #3a86ff;
        box-shadow: 0 0 0 3px rgba(58, 134, 255, 0.2);
    }

    .form-textarea {
        min-height: 300px;
        resize: vertical;
        font-family: inherit;
        line-height: 1.6;
    }

    /* Category Select */
    .form-select {
        cursor: pointer;
        background-position: right 1rem center;
        padding-right: 3rem;
    }

    /* Image Upload */
    .image-upload {
        border: 2px dashed #444;
        border-radius: 8px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .image-upload:hover {
        border-color: #3a86ff;
        background-color: rgba(58, 134, 255, 0.05);
    }

    .upload-icon {
        font-size: 3rem;
        color: #888;
        margin-bottom: 1rem;
    }

    .upload-text {
        color: #888;
        margin-bottom: 0.5rem;
    }

    .upload-hint {
        font-size: 0.9rem;
        color: #888;
    }

    .image-preview {
        display: none;
        margin-top: 1rem;
        text-align: center;
    }

    .image-preview img {
        max-width: 100%;
        max-height: 200px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 2rem;
    }

    /* Character Counter */
    .char-counter {
        text-align: right;
        color: #888;
        font-size: 0.85rem;
        margin-top: 0.5rem;
    }

    /* Notification */
    .notification {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #4CAF50;
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        z-index: 1000;
        display: none;
        align-items: center;
        gap: 10px;
    }

    .notification.show {
        display: flex;
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    /* Status Badges */
    .status {
        display: inline-block;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .status.published {
        background-color: rgba(76, 175, 80, 0.15);
        color: #4CAF50;
    }

    .status.draft {
        background-color: rgba(255, 152, 0, 0.15);
        color: #FF9800;
    }

    .status.pending {
        background-color: rgba(33, 150, 243, 0.15);
        color: #2196F3;
    }

    .status.scheduled {
        background-color: rgba(156, 39, 176, 0.15);
        color: #9C27B0;
    }
        .list{
        font-size:10px;
        width : 38px;
        height : 33px;
    }
    .link{
        width : 38px;
        height : 33px;
        background-color: #252c39ff;
        cursor: pointer;
    }
    /* Responsive Design */
    @media (max-width: 1024px) {
        .sidebar {
            width: 70px;
        }

        .logo h1,
        .logo span,
        .menu-item span,
        .menu-section {
            display: none;
        }

        .menu-item {
            justify-content: center;
        }

        .main-content {
            margin-left: 70px;
            width: calc(100% - 70px);
        }
    }

    @media (max-width: 768px) {
        .main-content {
            padding: 1.5rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .top-bar {
            flex-direction: column;
            align-items: flex-start;
            gap: 1.5rem;
        }

        .user-info {
            align-self: flex-end;
        }

        .article-form {
            padding: 1.5rem;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }
`;
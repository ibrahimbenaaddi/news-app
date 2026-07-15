import { storeArticle } from '../../Articles/ArticleController.js'
import { Link, useNavigate } from 'react-router-dom'
import { useState, useEffect } from 'react'

export default function EditArticle() {

    const navigate = useNavigate();
    const [error, setError] = useState([]);
    const [article, setArticle] = useState({
        image: null,
        body: '',
        category: '',
        title: ''
    });

    useEffect(() => {
        if (error.length === 0) return;
        window.scrollTo({ top: 0, behavior: 'smooth' });
        const timeout = setTimeout(() => setError([]), 3500);
        return () => clearTimeout(timeout);
    }, [error]);

    const handleChnages = (e) => {
        const { name, value, files, type } = e.target;
        setArticle(prev => ({
            ...prev,
            [name]: type === 'file' ? files[0] : value,
        }));
    };
    const validationsInputs = ({ title, body, category }) => {
        if (title.trim().length <= 14 || title.trim().length >= 99) {
            setError(prev => [...prev, 'title is required and must be in range 15~100 char']);
            return false;
        }
        if (!category.trim()) {
            setError(prev => [...prev, 'category is required']);
            return false;
        }
        if (body.trim().length <= 349) {
            setError(prev => [...prev, 'description is required and must be greater Than 350 char']);
            return false;
        }
        return true;
    }
    
    const SubmitArticle = async (e, article) => {
        e.preventDefault()
        if (validationsInputs(article)) {
            const response = await storeArticle(article);
            if (response.status) {
                navigate('/Admin/Dashboard');
                return;
            }
            setError(prev => [...prev, response.message || 'Failed to Store New Article']);
        }
    }

    return <>    <div className="container">
        <div className="header">
            <div className="logo">
                <i className="fas fa-newspaper"></i>
                <h1>NewsApp</h1>
            </div>
            <h2>Create New Article</h2>
            <p>Write and publish your story</p>
        </div>
        {
            error.length > 0 && <div className="alert alert-danger errorList mb-2">
                <ul style={{ listStyleType: 'none' }}>
                    {
                        error.map((err, index) => <li className="p-2" key={index} >{err}</li>)
                    }
                </ul>
            </div>
        }

        <form className="article-form" id="articleForm" encType="multipart/form-data" onSubmit={(e) => SubmitArticle(e, article)}>

            <div className="form-group">
                <label className="form-label" htmlFor="articleTitle">Article Title</label>
                <input type="text" className="form-input" id="articleTitle" name='title' value={article.title} onChange={handleChnages} placeholder="Enter your article title" />

            </div>

            <div className="form-group">
                <label className="form-label" htmlFor="articleCategory">Category</label>
                <select className="form-select" id="articleCategory" name='category' value={article.category} onChange={handleChnages} >
                    <option value="">Select a category</option>
                    <option value="Technology">Technology</option>
                    <option value="Business">Business</option>
                    <option value="Health">Health</option>
                    <option value="Sports">Sports</option>
                    <option value="Entertainment">Entertainment</option>
                    <option value="Environment">Environment</option>
                </select>
            </div>

            <div className="form-group">
                <label className="form-label" htmlFor="articleBody">Article Content</label>
                <textarea className="form-textarea" name='body' value={article.body} onChange={handleChnages} id="articleBody" placeholder="Write your article content here..." ></textarea>
            </div>

            <div className="form-group">
                <h5 className="form-label" htmlFor='imageFile' >Featured Image</h5>
                <label className="image-upload w-100" id="imageUpload" >
                    <div className="upload-icon">
                        <i className="fas fa-cloud-upload-alt"></i>
                    </div>
                    <div className="upload-text">
                        Click to upload or drag and drop
                    </div>
                    <div className="upload-hint">
                        JPG, PNG or GIF
                    </div>
                    <input type="file" id="imageFile" accept=".png,.jpeg,.jpg" name='image' onChange={handleChnages} style={{ display: 'none' }} />
                </label>
            </div>

            <div className="form-actions">
                <Link to="/Admin/Dashboard" className="btn btn-secondary" id="cancelBtn">
                    <i className="fas fa-times"></i> Cancel
                </Link>
                <button type="submit" className="btn btn-primary" id="publishBtn">
                    <i className="fas fa-paper-plane"></i> Publish Article
                </button>
            </div>
        </form>
    </div>
    </>
}
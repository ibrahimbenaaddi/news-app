import { getArticleById, updateArticle } from '../../Articles/ArticleController.js'
import { useParams, Link, useNavigate } from 'react-router-dom'
import { useState, useEffect } from 'react'

export default function EditArticle() {

    const navigate = useNavigate();
    const [article, setArticle] = useState({
        title: '',
        body: '',
        category: '',
        image: null
    });
    const [error, setError] = useState([]);
    const [errorsupport, setErrorsupport] = useState(null);
    const [isLoading, setIsLoading] = useState(true);
    const { articleID } = useParams()

    const fetchArticleById = async (id) => {
        try {
            const response = await getArticleById(id);
            if (!response?.status || !response?.data) {
                setErrorsupport('Failed To retrive the article');
                return;
            }
            setArticle({
                title: response.data.title,
                body: response.data.description,
                category: response.data.category,
            });
            document.title = response.data.title;
        } catch (error) {
            setErrorsupport(error.message || 'SomeThings go wrong ?');
        } finally {
            setIsLoading(false);
        }
    }

    useEffect(() => {
        fetchArticleById(articleID);
    }, [articleID]);
    useEffect(() => {
        if (error.length === 0) return;

        window.scrollTo({
            top: 0,
            behavior: 'smooth',
        });

        const timeout = setTimeout(() => setError([]), 3500);

        return () => clearTimeout(timeout);
    }, [error]);

    const handleChanges = (e) => {
        const { name, value, files, type } = e.target;
        setArticle({
            [name]: type === 'file' ? files[0] : value,
        });
    };
    const validationsInputs = ({ title, body }) => {
        if (title?.trim()) {
            if (title.trim().length <= 14 || title.trim().length >= 99) {
                setError(prev => [...prev, 'title is required and must be in range 15~100 char']);
                return false;
            }
        }
        if (body?.trim()) {
            if (body?.trim().length <= 349) {
                setError(prev => [...prev, 'description is required and must be greater Than 350 char']);
                return false;
            }
        }
        return true;
    }

    const update = async (e, article, id) => {
        e.preventDefault();
        if (validationsInputs(article)) {
            const response = await updateArticle(article, id);
            if (response.status) {
                navigate('/Admin/Dashboard');
                return;
            }
            setError(prev => [...prev, response.message || 'Failed To Update Article']);
        }
    }

    return <>
        {
            errorsupport ? <h5>{errorsupport}</h5> :
                isLoading ? <h1>isLoading...</h1>
                    : <div className="container">
                        <div className="header">
                            <div className="logo">
                                <i className="fas fa-newspaper"></i>
                                <h1>NewsApp</h1>
                            </div>
                            <h2>Edit The Article</h2>
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
                        <form className="article-form" id="articleForm" encType="multipart/form-data" onSubmit={(e) => update(e, article, articleID)} >
                            <div className="form-group">
                                <label className="form-label" htmlFor="articleTitle">Article Title</label>
                                <input type="text" className="form-input" id="articleTitle" name="title" value={article.title} onChange={handleChanges} placeholder="Enter your article title" />

                            </div>

                            <div className="form-group">
                                <label className="form-label" htmlFor="articleCategory">Category</label>
                                <select className="form-select" id="articleCategory" name="category" value={article.category} onChange={handleChanges} >
                                    <option value="">Select a category</option>
                                    <option value="Technology" >Technology</option>
                                    <option value="Business" >Business</option>
                                    <option value="Health" >Health</option>
                                    <option value="Sports" >Sports</option>
                                    <option value="Entertainment" >Entertainment</option >
                                    <option value="Environment" > Environment</option >
                                </select >
                            </div >

                            <div className="form-group">
                                <label className="form-label" htmlFor="articleBody">Article Content</label>
                                <textarea className="form-textarea" id="articleBody" placeholder="Write your article content here..." name="body" value={article.body} onChange={handleChanges} ></textarea>
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
                                    <input type="file" id="imageFile" accept=".png,.jpeg,.jpg" name='image' onChange={handleChanges} style={{ display: 'none' }} />
                                </label>
                            </div>

                            <div className="form-actions">
                                <Link to="/Admin/Dashboard" className="btn btn-secondary" id="cancelBtn">
                                    <i className="fas fa-times"></i> Cancel
                                </Link>
                                <button type="submit" className="btn btn-primary" id="publishBtn">
                                    <i className="fas fa-paper-plane"></i> Update Article
                                </button>
                            </div>
                        </form >
                    </div >
        }

    </>
}
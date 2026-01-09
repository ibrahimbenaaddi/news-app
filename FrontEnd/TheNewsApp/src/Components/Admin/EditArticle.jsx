import ArticleController from '../../Articles/ArticleController.js'
import { useParams, Link, useNavigate } from 'react-router-dom'
import { useState, useEffect, useRef } from 'react'

export default function EditArticle() {
    const ArticleService = new ArticleController();
    const navigate = useNavigate();
    const [{ title, description, category }, setArticle] = useState({ title: '', description: '', category: '' });
    const [error, setError] = useState([]);
    const [errorsupport, setErrorsupport] = useState(null);
    const [isLoading, setIsLoading] = useState(true);

    const articleImage = useRef();
    const articleDescription = useRef();
    const articleCategory = useRef();
    const articleTitle = useRef();

    const { articleID } = useParams()

    useEffect(() => {
        ArticleService.getArticleById(articleID).then(res => {
            if (!res) {
                return setErrorsupport('No Article Found pls ContactUs');
            }
            setArticle(res.article);
            setIsLoading(false);
        });
        return () => window.scrollTo({ top: 0, behavior: 'smooth' });
    }, [articleID]);

    useEffect(() => {
        const timeout = setTimeout(() => setError([]), 3500);
        return () => clearTimeout(timeout);
    }, [error]);

    // handleInputs
    const handleInputs = (title, description) => {
        if (title.trim().length < 14 || title.trim().length > 99) {
            setError(prev => [...prev, 'title is must be in range 15~100 char']);
            return false;
        }
        if (description.trim().length < 349) {
            setError(prev => [...prev, 'description must be greater Than 350 char']);
            return false;
        }
        return true;
    }

    const dataForm = () => {
        const articleData = new FormData();
        const fields = [
            { name: 'image', ref: articleImage, type: 'file' },
            { name: 'title', ref: articleTitle, type: 'text' },
            { name: 'body', ref: articleDescription, type: 'text' },
            { name: 'category', ref: articleCategory, type: 'text' },

        ];

        fields.forEach(({ name, ref, type }) => {
            if (type === 'file') {
                if (ref.current?.files?.[0]) {
                    articleData.append(name, ref.current.files[0]);
                }
            } else {
                if (ref.current?.value) {
                    articleData.append(name, ref.current.value);
                }
            }
        });
        return articleData;
    }

    const updateArticle = async (e) => {
        e.preventDefault()
        if (!handleInputs(articleTitle.current.value, articleDescription.current.value)) {
            setError(prev => [...prev, 'Faild to Update The Article']);
            return;
        }

        const response = await ArticleService.updateArticle(dataForm(), articleID);
        if (response.status) {
            navigate('/Admin/Dashboard');
            return;
        }
        setError(prev => [...prev, response.message]);

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
                        <form className="article-form" id="articleForm" encType="multipart/form-data">
                            <div className="form-group">
                                <label className="form-label" htmlFor="articleTitle">Article Title</label>
                                <input type="text" className="form-input" id="articleTitle" ref={articleTitle} defaultValue={title} placeholder="Enter your article title" />

                            </div>

                            <div className="form-group">
                                <label className="form-label" htmlFor="articleCategory">Category</label>
                                <select className="form-select" id="articleCategory" ref={articleCategory} defaultValue={category}>
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
                                <textarea className="form-textarea" id="articleBody" name="description" placeholder="Write your article content here..." defaultValue={description} ref={articleDescription}></textarea>
                            </div>

                            <div className="form-group">
                                <label className="form-label">Featured Image</label>
                                <div className="image-upload" id="imageUpload">
                                    <div className="upload-icon">
                                        <i className="fas fa-cloud-upload-alt"></i>
                                    </div>
                                    <div className="upload-text">
                                        Click to upload or drag and drop
                                    </div>
                                    <div className="upload-hint">
                                        JPG, PNG or GIF
                                    </div>
                                    <input type="file" id="imageFile" accept=".png,.jpeg,.jpg" ref={articleImage} />
                                </div>
                            </div>

                            <div className="form-actions">
                                <Link to="/Admin/Dashboard" className="btn btn-secondary" id="cancelBtn">
                                    <i className="fas fa-times"></i> Cancel
                                </Link>
                                <button type="submit" className="btn btn-primary" id="publishBtn" onClick={updateArticle}>
                                    <i className="fas fa-paper-plane"></i> Update Article
                                </button>
                            </div>
                        </form >
                    </div >
        }

    </>
}
import ArticleController from '../../Articles/ArticleController.js'
import { Link, useNavigate } from 'react-router-dom'
import { useState, useEffect, useRef } from 'react'

export default function EditArticle() {
    const ArticleService = new ArticleController();

    const navigate = useNavigate();
    const [error, setError] = useState([]);

    const articleImage = useRef();
    const articleDescription = useRef();
    const articleCategory = useRef();
    const articleTitle = useRef();

    useEffect(() => {
        const timeout = setTimeout(() => setError([]), 3500);
        return () => clearTimeout(timeout);
    }, [error]);

    // handleInputs
    const handleInputs = (title, description) => {
        if (title.trim().length <= 14 || title.trim().length >= 99) {
            setError(prev => [...prev, 'title is must be in range 15~100 char']);
            return false;
        }
        if (description.trim().length <= 349) {
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

    const storeArticle = async (e) => {
        e.preventDefault()
        if (!handleInputs(articleTitle.current.value, articleDescription.current.value)) {
            setError(prev => [...prev, 'Faild to Store The Article']);
            return;
        }

        const response = await ArticleService.storeArticle(dataForm());
        if (response.status) {
            navigate('/Admin/Dashboard');
            return;
        }
        setError(prev => [...prev, response.message]);

    }

    return <>    <div class="container">
        <div class="header">
            <div class="logo">
                <i class="fas fa-newspaper"></i>
                <h1>NewsApp</h1>
            </div>
            <h2>Create New Article</h2>
            <p>Write and publish your story</p>
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

        <form class="article-form" id="articleForm" encType="multipart/form-data">

            <div class="form-group">
                <label class="form-label" for="articleTitle">Article Title</label>
                <input type="text" class="form-input" id="articleTitle" ref={articleTitle} placeholder="Enter your article title" />

            </div>

            <div class="form-group">
                <label class="form-label" for="articleCategory">Category</label>
                <select class="form-select" id="articleCategory" ref={articleCategory} >
                    <option value="">Select a category</option>
                    <option value="Technology">Technology</option>
                    <option value="Business">Business</option>
                    <option value="Health">Health</option>
                    <option value="Sports">Sports</option>
                    <option value="Entertainment">Entertainment</option>
                    <option value="Environment">Environment</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="articleBody">Article Content</label>
                <textarea class="form-textarea" ref={articleDescription} id="articleBody" placeholder="Write your article content here..." ></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Featured Image</label>
                <div class="image-upload" id="imageUpload">
                    <div class="upload-icon">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <div class="upload-text">
                        Click to upload or drag and drop
                    </div>
                    <div class="upload-hint">
                        JPG, PNG or GIF
                    </div>
                    <input type="file" id="imageFile" accept=".png,.jpeg,.jpg" ref={articleImage} />
                </div>
            </div>

            <div class="form-actions">
                <Link to="/Admin/Dashboard" class="btn btn-secondary" id="cancelBtn">
                    <i class="fas fa-times"></i> Cancel
                </Link>
                <button type="submit" class="btn btn-primary" id="publishBtn" onClick={storeArticle}>
                    <i class="fas fa-paper-plane"></i> Publish Article
                </button>
            </div>
        </form>
    </div>
    </>
}
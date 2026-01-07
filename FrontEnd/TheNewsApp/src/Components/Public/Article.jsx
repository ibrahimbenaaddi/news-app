import Styled from 'Styled-Components';
import { Link, useParams } from 'react-router-dom'
import ArticleController from '../../Articles/ArticleController.js'
import { useEffect, useState } from 'react'

export default function Article() {
    const ArticleService = new ArticleController();

    const [article, setArticle] = useState(null);
    const [relatedArticles, setRelatedArticles] = useState([]);
    const [errorsupport, setErrorsupport] = useState(null);
    const [isLoading, setIsLoading] = useState(true);
    const [numberPages, setNumberPages] = useState(1)
    const [currentPage, setCurrentPage] = useState(1)

    const { articleID } = useParams()

    useEffect(() => {
        ArticleService.getArticleById(articleID).then(res => {
            if (!res) {
                return setErrorsupport('No Article Found pls ContactUs');
            }
            document.title = res.article.title;
            setArticle(res.article);
            setRelatedArticles(res.relatedArticles.articles);
            setNumberPages(res.relatedArticles.pagination.last_page);
            setCurrentPage(res.relatedArticles.pagination.current_page)
            setIsLoading(false);
        });
        return () => window.scrollTo({ top: 0, behavior: 'smooth' });
    }, [articleID]);

    // for relatedArticles Pagination
    useEffect(() => {
        ArticleService.getArticleById(articleID, currentPage).then(res => {
            if (!res) {
                return setRelatedArticles(null);
            }
            setRelatedArticles(res.relatedArticles.articles);
            setCurrentPage(res.relatedArticles.pagination.current_page)

        });
    }, [currentPage]);

    // Links of pagination
    const linksArr = (number, Cpage) => {
        let links = [];
        for (let i = 1; i <= number; i++) {
            links.push(<li className={`page-item border border-black list ${Cpage == i ? 'active' : ''}`}><button onClick={() => setCurrentPage(i)} className="page-link text-center link">{i}</button></li>);
        }
        return links;
    }

    // forward and backward

    const forward = (Cpage, Npage) => {
        if (Cpage == Npage) {
            setCurrentPage(1);
            return;
        }
        setCurrentPage(Cpage + 1)

    }
    const backward = (Cpage, Npage) => {
        if (Cpage == 1) {
            setCurrentPage(Npage);
            return;
        }
        setCurrentPage(Cpage - 1);

    }

    return <StyleComponent>
        {/* navbare */}
        <nav className="navbar">
            <Link to="/" className="logo">
                <i className="fas fa-newspaper"></i>
                <h1>NewsApp</h1>
            </Link>

            <div className="nav-links">
                <Link to="/" className="back-btn">
                    <i className="fas fa-arrow-left"></i> Back to News
                </Link>
            </div>
        </nav>
        {/* mainContent */}
        <main className="container">
            {
                errorsupport ? <h5>{errorsupport}</h5> :
                    isLoading ? <h1>isLoading...</h1>
                        : <>
                            <header className="article-header">
                                <span className="category-badge">{article.category}</span>
                                <h1 className="article-title">{article.title}</h1>
                            </header>

                            <div className="article-hero">
                                <img src={article.image} />
                            </div>

                            <article className="article-content">
                                <p>{article.description}</p>
                            </article>
                            <section className="related-section" >
                                <h2 className="section-title">Related Articles</h2>
                                <div className="related-articles">
                                    {
                                        relatedArticles.length > 0 ? relatedArticles.map(({ image, category, title, id }, index) =>
                                            <article className="related-card" key={index}>
                                                <img src={image} />
                                                <div className="related-card-content">
                                                    <span className="category-badge">{category}</span>
                                                    <h3>{title}</h3>
                                                    <Link to={`/Article/${id}`} className="read-more">Read More <i className="fas fa-arrow-right"></i></Link>
                                                </div>
                                            </article>) : "No Articles Related To this Article"
                                    }
                                </div>

                            </section>
                        </>
            }
            {
                numberPages > 1 && relatedArticles.length > 0 ? <div className="d-flex flex-row-reverse mt-2">
                <nav>
                    <ul className="pagination pagination-lg">
                        <li className="page-item border border-black list" >
                            <button onClick={() => backward(Number(currentPage), Number(numberPages))} className="page-link text-center link" >&#8617;</button>
                        </li>
                        {
                            linksArr(numberPages, currentPage)
                        }
                        <li className="page-item border border-black" >
                            <button onClick={() => forward(Number(currentPage), Number(numberPages))} className="page-link text-center link">&#8618;</button>
                        </li>
                    </ul>
                </nav>
            </div> : ""
            }

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

    body {
        background-color: #121212;
        color: #e0e0e0;
        min-height: 100vh;
        line-height: 1.6;
    }

    /* Navbar */
    .navbar {
        background-color: #1e1e1e;
        padding: 1rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
        position: sticky;
        top: 0;
        z-index: 100;
    }

    .logo {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
    }

    .logo i {
        font-size: 1.8rem;
        color: #3a86ff;
    }

    .logo h1 {
        font-size: 1.8rem;
        font-weight: 700;
        background: linear-gradient(to right, #3a86ff, #6c63ff);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .nav-links {
        display: flex;
        align-items: center;
        gap: 2rem;
    }

    .nav-links a {
        color: #e0e0e0;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .nav-links a:hover {
        color: #3a86ff;
    }

    .back-btn {
        background-color: #3a86ff;
        color: white;
        border: none;
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: background-color 0.3s;

    }

    .back-btn:hover {
        background-color: #2667cc;
    }

    /* Main Content */
    .container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 2rem;
    }

    /* Article Header */
    .article-header {
        margin-bottom: 3rem;
        position: relative;
    }

    .category-badge {
        display: inline-block;
        background-color: #3a86ff;
        color: white;
        padding: 0.5rem 1.2rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }

    .article-title {
        font-size: 2.5rem;
        line-height: 1.3;
        margin-bottom: 1.5rem;
        color: #fff;
    }

    .article-meta {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 2rem;
        color: #888;
        font-size: 0.95rem;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .author-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid #444;
    }

    .author-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(to right, #3a86ff, #6c63ff);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: white;
        font-size: 1.2rem;
    }

    .author-details h3 {
        margin-bottom: 0.3rem;
        color: #fff;
    }

    .author-details p {
        color: #888;
        font-size: 0.9rem;
    }

    /* Article Hero Image */
    .article-hero {
        width: 100%;
        height: 400px;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 3rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }

    .article-hero img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Article Content */
    .article-content {
        font-size: 1.1rem;
        line-height: 1.8;
        margin-bottom: 3rem;
    }

    .article-content p {
        word-wrap: break-word;
        overflow-wrap: break-word;
        word-break: break-word;
        margin-bottom: 1.5rem;
    }

    .article-content h2 {
        font-size: 1.8rem;
        margin: 2.5rem 0 1.5rem;
        color: #fff;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #444;
    }

    .article-content h3 {
        font-size: 1.4rem;
        margin: 2rem 0 1rem;
        color: #fff;
    }

    .article-content blockquote {
        border-left: 4px solid #3a86ff;
        padding-left: 1.5rem;
        margin: 2rem 0;
        font-style: italic;
        color: #888;
        font-size: 1.2rem;
    }

    .article-content ul,
    .article-content ol {
        margin: 1.5rem 0;
        padding-left: 1.5rem;
    }

    .article-content li {
        margin-bottom: 0.8rem;
    }

    /* Related Articles */
    .related-section {
        margin-top: 4rem;
        padding-top: 2rem;
        border-top: 1px solid #444;
    }

    .section-title {
        font-size: 1.8rem;
        margin-bottom: 2rem;
        color: #fff;
    }

    .related-articles {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
    }

    .related-card {
        background: linear-gradient(to bottom right, #2d2d2d, #1e1e1e);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
        transition: transform 0.3s;
    }

    .related-card:hover {
        transform: translateY(-5px);
    }

    .related-card img {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }

    .related-card-content {
        padding: 1.5rem;
    }

    .related-card h3 {
        font-size: 1.2rem;
        margin-bottom: 0.8rem;
        color: #fff;
    }

    .related-card p {
        color: #888;
        font-size: 0.95rem;
        margin-bottom: 1rem;
    }

    .related-card .read-more {
        color: #3a86ff;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 6px;
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
    @media (max-width: 768px) {
        .navbar {
            flex-direction: column;
            gap: 1rem;
            padding: 1rem;
        }

        .nav-links {
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .article-title {
            font-size: 2rem;
        }

        .container {
            padding: 1.5rem;
        }

    }
`;
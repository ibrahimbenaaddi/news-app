import Styled from 'Styled-Components'
import { Link, useParams, useNavigate } from 'react-router-dom'
import ArticleController from '../../Articles/ArticleController.js'
import { useEffect, useState } from 'react'

export default function Home() {
    const ArticleService = new ArticleController();
    let navigate = useNavigate()


    const [articles, setArticles] = useState(null);
    const [errorsupport, setErrorsupport] = useState(null);
    const [error, setError] = useState(null);
    const [isLoading, setIsLoading] = useState(true);
    const [numberPages, setNumberPages] = useState(1)

    const { currentPage } = useParams();

    useEffect(() => {
        document.title = 'NewsApp - Latest Articles';
        ArticleService.getArticles(currentPage).then(res => {
            if (!res) {
                return setErrorsupport('No Article Found pls ContactUs');
            }
            setArticles(res.articles);
            setNumberPages(res.pagination.last_page);
            setIsLoading(false);
        });
    }, [currentPage]);

    useEffect(() => {
        const timeOut = setTimeout(() => setError(null), 3500);
        return () => clearTimeout(timeOut);
    }, [error])

    // Links of pagination
    const linksArr = (number,Cpage) => {
        let links = [];
        for (let i = 1; i <= number; i++) {
            links.push(<li className={`page-item border border-black list ${Cpage == i ? 'active' : '' }`}><button onClick={() => navigate(`/page/${i}`) } className="page-link text-center link">{i}</button></li>);
        }
        return links;
    }

    // forward and backward

    const forward = (Cpage ,Npage) =>{
        if(Cpage == Npage ){
            navigate(`/page/1`);
            return;
        }
        navigate(`/page/${Cpage+1}`);

    }
    const backward = (Cpage ,Npage) =>{
        if(Cpage == 1 ){
            navigate(`/page/${Npage}`);
            return;
        }
        navigate(`/page/${Cpage-1}`);

    }
    return <StyledComponent>
        {/* navbare */}
        <nav className="navbar">
            <Link to="/" className="logo">
                <i className="fas fa-newspaper"></i>
                <h1>NewsApp</h1>
            </Link>
            <div>
                <div className="search-container">
                    <input type="text" className="search-input" name='title' placeholder="Search for news, topics, or authors..." />
                    <button className="search-btn" type="submit">
                        <i className="fas fa-search"></i> Search
                    </button>
                </div>
            </div>
        </nav>
        {/* mainContent */}
        <main className="container" >
            <div className="articles-grid position-relative">
                {/* Article Card Template */}
                {
                    errorsupport ? <h5>{errorsupport}</h5>
                        : isLoading ? <h1>isLoading...</h1>
                            : error ? <h5>{error}</h5> : <>
                                {
                                    articles.map(({ title, category, image, date ,id}, index) => <article className="article-card" key={index}>
                                        <img src={image} className="card-image" />
                                        <div className="card-content">
                                            <span className="card-category">{category}</span>
                                            <h3 className="card-title">{title}</h3>
                                            <div className="card-footer">
                                                <span className="card-date">{date}</span>
                                                <Link to={`/Article/${id}`} className="read-more">Read More <i className="fas fa-arrow-right"></i></Link>
                                            </div>
                                        </div>
                                    </article>)
                                }
                                <div className="position-absolute bottom-0 end-0">
                                    <nav>
                                        <ul className="pagination pagination-lg">
                                            <li className="page-item border border-black list" >
                                                <button onClick={()=> backward(Number(currentPage),Number(numberPages))} className="page-link text-center link" >&#8617;</button>
                                            </li>
                                            {
                                                linksArr(numberPages,currentPage)
                                            }
                                            <li className="page-item border border-black" >
                                                <button onClick={()=> forward(Number(currentPage),Number(numberPages))} className="page-link text-center link">&#8618;</button>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>

                            </>
                }
            </div>
        </main>
    </StyledComponent >
}
const StyledComponent = Styled.div`
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', system-ui, sans-serif;
    }
    /* Navbar Styles */
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
        text-decoration: none
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

    .search-container {
        display: flex;
        width: 100%;
        max-width: 500px;
    }

    .search-input {
        flex: 1;
        padding: 0.75rem 1rem;
        border: none;
        border-radius: 8px 0 0 8px;
        background-color: #2d2d2d;
        color: #e0e0e0;
        font-size: 1rem;
        outline: none;
    }

    .search-input::placeholder {
        color: #aaa;
    }

    .search-btn {
        background-color: #3a86ff;
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 0 8px 8px 0;
        cursor: pointer;
        font-weight: 600;
        transition: background-color 0.3s;
    }

    .search-btn:hover {
        background-color: #2667cc;
    }

    /* Main Content */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

    .page-title {
        margin-bottom: 2rem;
        font-size: 2rem;
        text-align: center;
        color: #fff;
    }

    /* Cards Grid */
    .articles-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 2rem;
    }

    /* Card Styles */
    .article-card {
        background: linear-gradient(to bottom right, #2d2d2d, #1a1a1a);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
        transition: transform 0.3s, box-shadow 0.3s;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .article-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.7);
    }

    .card-image {
        height: 200px;
        width: 100%;
        object-fit: cover;
        border-bottom: 3px solid #3a86ff;
    }

    .card-category {
        display: inline-block;
        background-color: #3a86ff;
        color: white;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 1rem;
        align-self: flex-start;
    }

    .card-content {
        padding: 1.5rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .card-title {
        font-size: 1.4rem;
        margin-bottom: 1rem;
        color: #fff;
        line-height: 1.4;
        word-wrap: break-word;
        overflow-wrap: break-word;
        word-break: break-word;
    }

    .card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
        padding-top: 1rem;
        border-top: 1px solid #444;
    }

    .card-date {
        color: #888;
        font-size: 0.9rem;
    }

    .read-more {
        color: #3a86ff;
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: color 0.3s;
    }

    .read-more:hover {
        color: #6c63ff;
    }

    .read-more i {
        font-size: 0.9rem;
        transition: transform 0.3s;
    }

    .read-more:hover i {
        transform: translateX(4px);
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

        .search-container {
            max-width: 100%;
        }

        .articles-grid {
            grid-template-columns: 1fr;
        }

        .container {
            padding: 1.5rem;
        }
    }
`;
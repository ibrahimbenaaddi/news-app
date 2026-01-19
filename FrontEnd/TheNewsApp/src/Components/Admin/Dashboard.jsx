import ArticleController from '../../Articles/ArticleController.js'
import { Link, useSearchParams } from 'react-router-dom'
import { useState, useEffect } from 'react'

export default function Dashboard() {
    const ArticleService = new ArticleController();
    const [searchParams, setSearchParams] = useSearchParams();

    const [articles, setArticles] = useState(null);
    const [errorsupport, setErrorsupport] = useState(null);
    const [isLoading, setIsLoading] = useState(true);
    const [numberPages, setNumberPages] = useState(1);
    const [totalArticles, setTotalArticles] = useState(1);

    const currentPage = Number(searchParams.get("page")) || 1;

    // fetchAllArticles
    const fetchAllArticles = async () => {
        ArticleService.getArticles(currentPage).then(res => {
            if (!res) {
                return setErrorsupport('No Article Found pls ContactUs');
            }
            setArticles(res.articles);
            setTotalArticles(res.pagination.total);
            setNumberPages(res.pagination.last_page);
            setIsLoading(false);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
    useEffect(() => {
        fetchAllArticles()
    }, [currentPage]);

    // Links of pagination
    const linksArr = (number, Cpage) => {
        let links = [];
        for (let i = 1; i <= number; i++) {
            links.push(<li className={`page-item border border-black list ${Cpage == i ? 'active' : ''}`}><button onClick={() => setSearchParams({ page: i })} className="page-link text-center link">{i}</button></li>);
        }
        return links;
    };

    // forward and backward
    const forward = (Cpage, Npage) => {
        if (Cpage == Npage) {
            setSearchParams({ page: 1 });
            return;
        }
        setSearchParams({ page: Cpage + 1 })
    }
    const backward = (Cpage, Npage) => {
        if (Cpage == 1) {
            setSearchParams({ page: Npage })
            return;
        }
        setSearchParams({ page: Cpage - 1 })
    };

    const deleteArticle = async (id) => {
        const resultat = window.confirm('are you sure you delete this article')
        if (resultat) {
            const response = await ArticleService.deleteArticle(id);
            if (response.status) {
                alert(`The article ID : ${id} , deleted successfully`);
                fetchAllArticles();
                return;
            }
            alert(response.message);
        }
    }
    return <>
        {
            errorsupport ? <h5>{errorsupport}</h5>
                : isLoading ? <h1>isLoading...</h1>
                    : <>
                        <div className="stats-grid">
                            <div className="stat-card">
                                <div className="stat-icon articles">
                                    <i className="fas fa-newspaper"></i>
                                </div>
                                <div className="stat-info">
                                    <h3>{totalArticles}</h3>
                                    <p>Total Articles</p>
                                </div>
                            </div>
                        </div>

                        <div className="dashboard-section">
                            <div className="section-header">
                                <h3 className="section-title">Recent Articles</h3>
                                <Link to="/Admin/Add/Article" className="btn btn-primary"><i className="fas fa-plus"></i> Add New Article</Link>
                            </div>

                            <div className="table-container">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Category</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {
                                            articles.map(({ id, title, category, date }, index) => <tr key={index} >
                                                <td>{id}</td>
                                                <td>{title}</td>
                                                <td>{category}</td>
                                                <td>{date}</td>
                                                <td>
                                                    <div className="action-btns">
                                                        <Link to={`/Admin/Edit/Article/${id}`} className="action-btn edit btn btn-primary"><i className="fas fa-edit"></i></Link>
                                                        <div>
                                                            <button type="submit" className="action-btn delete btn btn-danger" onClick={() => deleteArticle(id)} ><i className="fas fa-trash"></i></button>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>)
                                        }

                                    </tbody>
                                </table>
                            </div>
                            {
                                numberPages > 1 ? <div className="d-flex flex-row-reverse mt-2">
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
                                </div> : ''
                            }
                        </div>

                    </>
        }

    </>
}

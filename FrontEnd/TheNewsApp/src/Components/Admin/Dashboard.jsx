import { getArticles, destroyArticle } from '../../Articles/ArticleController.js'
import { Link, useSearchParams } from 'react-router-dom'
import { useState, useEffect } from 'react'

export default function Dashboard() {
    const [searchParams, setSearchParams] = useSearchParams();
    const currentSearch = searchParams.get("search") || "";
    const currentPage = Number(searchParams.get("page")) || 1;
    const [articles, setArticles] = useState([]);
    const [errorsupport, setErrorsupport] = useState(null);
    const [isLoading, setIsLoading] = useState(true);
    const [numberPages, setNumberPages] = useState(1);
    const [search, setSearch] = useState(currentSearch);
    const [error, setError] = useState(null);
    const [total, setTotal] = useState(1);
    const fetchArticles = async (page, search = null) => {
        setIsLoading(true);
        setErrorsupport(null);
        try {
            const response = await getArticles(page, search);
            if (!response?.status) {
                throw new Error("Failed To Retrive Articles");
            }
            if (response.data?.length == 0) {
                throw new Error("No Articles Found");
            }
            setArticles(response.data);
            setNumberPages(response.pagination.last_page);
            setTotal(response.pagination.total)
        } catch (error) {
            setErrorsupport(error.message || 'Something went wrong!');
        } finally {
            setIsLoading(false);
        }
    }

    useEffect(() => {
        fetchArticles(currentPage, currentSearch); // here keep search with pagination
    }, [currentPage, currentSearch]);

    const handleChange = (e) => {
        const value = e.target.value;
        if (!value.trim()) {
            updatePage(1);
        }
        setSearch(value);
    }

    const updatePage = (page, search = null) => {
        let params = {};
        if (search) {
            params.search = search;
        }
        params.page = page;
        setSearchParams(params);
    }

    const find = (e, page, search) => {
        e.preventDefault();
        if (!search.trim()) {
            setError('Plz enter A Solid search');
            return;
        }
        window.scrollTo({ top: 0, behavior: 'smooth' })
        updatePage(page, search)
    };

    useEffect(() => {
        const timeOut = setTimeout(() => setError(null), 3500);
        return () => clearTimeout(timeOut);
    }, [error]);

    const linksArr = (number, Cpage, search = null) => {
        let links = [];
        for (let i = 1; i <= number; i++) {
            links.push(<li key={i} className={`page-item border border-black list ${Cpage == i ? 'active' : ''}`}><button onClick={() => updatePage(i, search)} className="page-link text-center link">{i}</button></li>);
        }
        return links;
    };
    const forward = (Cpage, Npage, search = null) => {
        updatePage(Cpage === Npage ? 1 : Cpage + 1, search)
    }
    const backward = (Cpage, Npage, search = null) => {
        updatePage(Cpage === 1 ? Npage : Cpage - 1, search)
    };

    const deleteArticle = async (id) => {
        const resultat = window.confirm('are you sure you delete this article')
        if (resultat) {
            const response = await destroyArticle(id);
            if (response.status) {
                alert(`The article ID : ${id} , deleted successfully`);
                fetchAllArticles();
                return;
            }
            alert(response.message);
        }
    }
    return <>
        <div className="section-header">
            <h3 className="section-title">Recent Articles</h3>
            <div className="search-container">
                <form onSubmit={(e) => find(e, 1, search)}>
                    <input type="text" className="search-input" value={search} onChange={handleChange} placeholder="Search by title, category..." />
                    <button type="submit" className="search-btn">
                        <i className="fas fa-search"></i> Search
                    </button>
                </form>
                <Link to="/Admin/Add/Article" className="btn btn-primary ms-1"><i className="fas fa-plus"></i></Link>
            </div>
        </div>
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
                                    <h3>{total}</h3>
                                    <p>Total Articles</p>
                                </div>
                            </div>
                        </div>

                        <div className="dashboard-section">
                            {error && <h5 className="alert alert-warning m-3 p-2">{error}</h5>}
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
                                                <button onClick={() => backward(Number(currentPage), Number(numberPages), search)} className="page-link text-center link" >&#8617;</button>
                                            </li>
                                            {
                                                linksArr(numberPages, currentPage, search)
                                            }
                                            <li className="page-item border border-black" >
                                                <button onClick={() => forward(Number(currentPage), Number(numberPages), search)} className="page-link text-center link">&#8618;</button>
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

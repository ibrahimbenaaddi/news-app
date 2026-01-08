import { BrowserRouter, Routes, Route } from 'react-router-dom'
import PubLayout from './Components/Layouts/PubLayout.jsx'
import AdLayout from './Components/Layouts/AdLayout.jsx'
import Home from './Components/Public/Home.jsx'
import Article from './Components/Public/Article.jsx'
import Login from './Components/Admin/Login.jsx'
import Dashboard from './Components/Admin/Dashboard.jsx'
import EditArticle from './Components/Admin/EditArticle.jsx'
import StoreArticle from './Components/Admin/StoreArticle.jsx'

export default function App() {

  return (
    <BrowserRouter>
      <Routes>
        <Route element={<PubLayout />}>
          <Route index element={<Home />} />
          <Route path='/Article/:articleID' element={<Article />} />
        </Route>

        {/* forAdmin */}
        <Route path="/Admin/login" element={<Login />} />

        {/* Admin Panel */}
        <Route element={<AdLayout /> } >
          <Route path="/Admin/Dashboard" element={<Dashboard />} />
          <Route path="/Admin/Edit/Article/:articleID" element={<EditArticle />} />
          <Route path="/Admin/Add/Article" element={<StoreArticle />} />
        </Route>

      </Routes>

    </BrowserRouter>
  )
}



import { BrowserRouter, Routes, Route } from 'react-router-dom'
import PubLayout from './Components/Layouts/PubLayout.jsx'
import Home from './Components/Public/Home.jsx'
import Article from './Components/Public/Article.jsx'
import Login from './Components/Admin/Login.jsx'
import Dashboard from './Components/Admin/Dashboard.jsx'

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
        <Route path="/Admin/Dashboard" element={<Dashboard />} />
      </Routes>

    </BrowserRouter>
  )
}



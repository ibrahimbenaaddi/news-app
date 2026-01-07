import { BrowserRouter , Routes , Route ,Navigate } from 'react-router-dom'
import PubLayout from './Components/Layouts/PubLayout.jsx'
import Home from './Components/Public/Home.jsx'
import Article from './Components/Public/Article.jsx'

export default function App() {

  return (
    <BrowserRouter>
        <Routes>
          <Route element={<PubLayout />}>
              {/* Npage : number page for pagination */}
              <Route index element={ <Navigate to="/page/1" /> } />
              <Route path='/page/:currentPage' element={ <Home /> } />
              <Route path='/Article/:articleID' element={ <Article /> } />
          </Route>
        </Routes>
    </BrowserRouter>
  )
}



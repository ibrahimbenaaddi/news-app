import { BrowserRouter , Routes , Route } from 'react-router-dom'
import PubLayout from './Components/Layouts/PubLayout.jsx'
import Home from './Components/Public/Home.jsx'

export default function App() {

  return (
    <BrowserRouter>
        <Routes>
          <Route element={<PubLayout />}>
              <Route path='/' element={ <Home /> } />
          </Route>
        </Routes>
    </BrowserRouter>
  )
}



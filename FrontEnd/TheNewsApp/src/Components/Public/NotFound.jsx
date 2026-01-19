import Styled from 'Styled-Components'
import { Link } from 'react-router-dom'

export default function NotFound() {
    return <StyleComponent>
        <div className="container">
                <h1>404-Page-NotFound</h1>
                <Link to='/' className='btn btn-primary link'>Go To Home</Link>
        </div>
    </StyleComponent>
}
const StyleComponent = Styled.div`
    .container {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 4px;
    height : 100vh;
    }
    .link{

        margin-top:10px;
        text-decoration : none ;
        width: 250px;
        font-size : 22px;
    }
`;
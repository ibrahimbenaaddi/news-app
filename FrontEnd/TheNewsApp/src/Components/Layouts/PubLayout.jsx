import { Outlet } from 'react-router-dom'

export default function PubLayout() {

    return <>
        <Outlet />
        <footer className="footer">
            <p>© 2025/2026 NewsApp. All rights reserved. | Stay informed with the latest news from around the world.</p>
        </footer>
    </>

}

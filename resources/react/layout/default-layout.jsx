import React, { lazy } from 'react'
import { useSelector } from 'react-redux'
import { CSpinner } from '@coreui/react'
import { AppContent, AppSidebar, AppHeader } from '../components/index'

const POSLazy = lazy(() => import('../views/pos/'))
const ProductsLazy = lazy(() => import('../views/products'))

const DefaultLayout = () => {
    const user = useSelector((state) => state.user)
    const auth_token = useSelector((state) => state.auth_token)

    if (!user)
        return (
            <div className={`loading-overlay ${auth_token ? '' : 'bg-dark'}`}>
                <CSpinner color="primary" variant="grow" />
            </div>
        )

    if (user.role === 'admin')
        return (
            <div>
                <AppSidebar />
                <div className="wrapper d-flex flex-column min-vh-100">
                    <AppHeader />
                    <div className="body flex-grow-1">
                        <AppContent />
                    </div>
                </div>
            </div>
        )
    if (user.role === 'cashier') return <POSLazy />
    if (user.role === 'production')
        return (
            <div>
                <ProductsLazy />
            </div>
        )

    return <>Hello world</>
}

export default DefaultLayout

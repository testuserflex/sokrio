import store from '@/state/store'

export default [{
        path: '/',
        name: 'default',
        meta: { authRequired: false },
        component: () =>
            import ('./views/dashboards/default'),
    },
    {
        path: '/login',
        name: 'login',
        component: () =>
            import ('./views/account/login'),
        meta: {
            beforeResolve(routeTo, routeFrom, next) {
                // If the user is already logged in
                if (store.getters['auth/token']) {
                    // Redirect to the home page instead
                    next({ name: 'default' })
                } else {
                    // Continue to the login page
                    next()
                }
            },
        },
    },
    {
        path: '/logout',
        name: 'logout',
        meta: {
            authRequired: true,
            beforeResolve(routeTo, routeFrom, next) {

                store.dispatch('auth/logout')

                const authRequiredOnPreviousRoute = routeFrom.matched.some(
                        (route) => route.push('/login')
                    )
                    // Navigate back to previous page, or home as a fallback
                next(authRequiredOnPreviousRoute ? { name: 'default' } : {...routeFrom })
            },
        },
    },
    {
        path: '/products/add_product',
        name: 'Add Products',
        meta: { authRequired: true },
        component: () =>
            import ('./views/products/add_product')
    },
    {
        path: '/units/view',
        name: 'Product Units',
        meta: { authRequired: true },
        component: () =>
            import ('./views/products/units')
    },
    {
        path: '/categories/view',
        name: 'Product Categories',
        meta: { authRequired: true },
        component: () =>
            import ('./views/products/categories')
    },
    {
        path: '/business/products',
        name: 'Business Products',
        meta: { authRequired: true },
        component: () =>
            import ('./views/products/business')
    },
    {
        path: '/products/view',
        name: 'Products',
        meta: { authRequired: true },
        component: () =>
            import ('./views/products/products')
    },
    {
        path: '/products/units',
        name: 'ProductUnits',
        meta: { authRequired: true },
        component: () =>
            import ('./views/products/product_units')
    },
    {
        path: '/services/view',
        name: 'Services',
        meta: { authRequired: true },
        component: () =>
            import ('./views/products/services')
    },
    {
        path: '/suppliers/view',
        name: 'Suppliers',
        meta: { authRequired: true },
        component: () =>
            import ('./views/stock/suppliers')
    },
    {
        path: '/supplier/deposits',
        name: 'Supplier Deposits',
        meta: { authRequired: true },
        component: () =>
            import ('./views/stock/supplier_deposit')
    },
    {
        path: '/stock/view',
        name: 'Stock',
        meta: { authRequired: true },
        component: () =>
            import ('./views/stock/stock')
    },
    {
        path: '/stock/allstock',
        name: 'All Stock',
        meta: { authRequired: true },
        component: () =>
            import ('./views/stock/allstock')
    },
    {
        path: '/stock/stockvalue',
        name: 'Stock Value',
        meta: { authRequired: true },
        component: () =>
            import ('./views/stock/stockvalue')
    },
    {
        path: '/stock/outof_stock',
        name: 'Out of Stock',
        meta: { authRequired: true },
        component: () =>
            import ('./views/stock/outof_stock')
    },
    {
        path: '/stock/in_stock',
        name: 'In Stock',
        meta: { authRequired: true },
        component: () =>
            import ('./views/stock/in_stock')
    },
    {
        path: '/purchase/new',
        name: 'New Purchase',
        meta: { authRequired: true },
        component: () =>
            import ('./views/stock/new_purchase')
    },
    {
        path: '/stock/reconcile',
        name: 'Reconcile Stock',
        meta: { authRequired: true },
        component: () =>
            import ('./views/stock/reconcile')
    },
    {
        path: '/users/groups',
        name: 'Buy/sell',
        meta: { authRequired: true },
        component: () =>
            import ('./views/users/roles')
    },
    {
        path: '/users',
        name: 'Users',
        meta: { authRequired: true },
        component: () =>
            import ('./views/users/users')
    },
    {
        path: '/sales/order',
        name: 'NewOrder',
        meta: { authRequired: true },
        component: () =>
            import ('./views/sales/sales')
    },
]
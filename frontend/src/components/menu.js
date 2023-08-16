export const menuItems = [{
        id: 1,
        perm: "view_dashboard",
        label: "Home",
        icon: "bx-home-circle",
        link: "/"
    },
    // {
    //     id: 7,
    //     isLayout: true
    // },
    {
        id: 7,
        perm: "new_sale",
        actionpage: "1",        
        label: "New Record",
        icon: "bx-cart",
        link: "/sales/order"
    }, 
    {
        id: 12,        
        perm: "stock_tab",
        label: "menuitems.stock.text",
        icon: "bx-store",
        subItems: [{
                id: 13,
                perm: "view_shop_stock",
                label: "menuitems.stock.list.level",
                link: "/stock/view",
                parentId: 12
            },
            {
                id: 14,
                perm: "add_shop_stock",
                actionpage: "1", 
                label: "menuitems.stock.list.addstock",
                link: "/purchase/new",
                parentId: 12
            },
            {
                id: 17,
                perm: "reconcile_shop_stock",
                actionpage: "1",  
                label: "menuitems.stock.list.reconcile",
                link: "/stock/reconcile",
                parentId: 12
            },
        ]
    },   
    {
        id: 29,
        perm: "products_tab",
        label: "Products",
        icon: "bx-grid",
        subItems: [{
            id: 28,
            perm: "add_products",
            actionpage: "1",
            label: "Add Products",
            link: "/products/add_product",
            parentId: 29
        },
        {
            id: 30,
            perm: "view_products",
            label: "menuitems.products.list.view",
            link: "/products/view",
            parentId: 29
        },            
        {
            id: 31,
            perm: "view_product_categories",
            label: "menuitems.products.list.categories",
            link: "/categories/view",
            parentId: 29
        },
        {
            id: 33,
            perm: "view_units",
            label: "menuitems.products.list.units",
            parentId: 29,
            link: "/units/view",
        },
        {
                id: 35,
                perm: "view_services",
                label: "Services",
                parentId: 29,
                link: "/services/view",
            }
        ]
    },
   
    

];
const adminRoutes = [
    {
        path: '/admin', meta: {title: 'Admin-Gaea'}, component: () => import('../components/AdminLayout'), redirect: '/admin/user/list',
        children: [
            {
                path: 'user/list', name: 'admin-user-list', component: () => import('../pages/admin/UserList'),
            },
            {
                path: 'log/list', name: 'admin-log-list', component: () => import('../pages/admin/LogList'),
            }
        ]
    }
];
export default adminRoutes;
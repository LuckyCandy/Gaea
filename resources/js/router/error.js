const errorRoutes = [
    {
        path: '*', meta: {title: 'NotFound'}, component: () => import('../components/Error'), name: '404'
    }
];
export default errorRoutes;
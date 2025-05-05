import './page/list';
import './page/detail';

Shopware.Module.register('blog-category', {
    type: 'plugin',
    name: 'blog-module.plugin.name',
    title: 'blog-module.plugin.title',
    description: 'Manage blog categories',
    color: '#176aaf',
    entity: 'blog_category',

    routes: {
        index: {
            component: 'blog-category-list',
            path: 'index',
            name: 'blog.category.index'
        },
        create: {
            component: 'blog-category-detail',
            path: 'create',
            name: 'blog.category.create',
            meta: {
                parentPath: 'blog.category.index',
            },
        },
        detail: {
            component: 'blog-category-detail',
            name: 'blog.category.detail',
            path: 'detail/:id?',
            meta: {
                parentPath: 'blog.category.index'
            },
            props: {
                default(route) {
                    return {
                        blogCategoryId: route.params.id,
                    };
                },
            },
        }
    },

    navigation: [{
        label: 'blog-module.plugin.name',
        color: '#176aaf',
        path: 'blog.category.index',
        icon: 'default-communication-speech-bubbles',
        parent: 'sw-catalogue',
        position: 100
    }]
});
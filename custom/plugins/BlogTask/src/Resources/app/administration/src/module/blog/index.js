import './page/list';
import './page/detail';

Shopware.Module.register('blog-module', {
    type: 'plugin',
    name: 'blog.module.name',
    title: 'blog.module.title',
    description: 'blog.module.description',
    color: '#176aaf',
    entity: 'blog',

    routes: {
        index: {
            component: 'blog-list',
            path: 'index',
        },
        create: {
            component: 'blog-detail',
            path: 'create',
            meta: {
                parentPath: 'blog.module.index',
            },
        },
        detail: {
            component: 'blog-detail',
            path: 'detail/:id?',
            meta: {
                parentPath: 'blog.module.index'
            },
            props: {
                default(route) {
                    return {
                        blogId: route.params.id,
                    };
                },
            },
        }
    },

    navigation: [{
        label: 'blog.module.label',
        color: '#176aaf',
        path: 'blog.module.index',
        icon: 'default-communication-speech-bubbles',
        position: 100,
        parent: 'sw-catalogue'
    }]
});
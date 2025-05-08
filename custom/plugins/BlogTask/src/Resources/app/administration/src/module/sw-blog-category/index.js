import deDE from "./snippet/de-DE.json";
import enGB from "./snippet/en-GB.json";
const { Module } = Shopware;
Shopware.Component.register("sw-blog-category-list", () =>
    import("./page/sw-blog-category-list")
);

Shopware.Component.register("sw-blog-category-detail", () =>
    import("./page/sw-blog-category-detail")
);

Module.register("sw-blog-category", {
    type: "plugin",
    name: "Blog Category",
    title: "Blog Category",
    description: "Blog Category",
    color: "#176aaf",
    snippets: {
        "de-DE": deDE,
        "en-GB": enGB,
    },
    routes: {
        index: {
            component: "sw-blog-category-list",
            path: "index",
            name: "sw.blog.category.index",
        },
        create: {
            component: "sw-blog-category-detail",
            path: "create",
            name: "sw.blog.category.detail",
            meta: {
                parentPath: "sw.blog.category.index",
                privilege: "blog-category.creator",
            },
        },
        detail: {
            component: "sw-blog-category-detail",
            path: "detail/:id",
            name: "sw.blog.category.detail",
            meta: {
                parentPath: "sw.blog.category.index",
                privilege: "blog-category.viewer",
            },
            props: {
                default(route) {
                    return {
                        blogCategoryId: route.params.id,
                    };
                },
            },
        },
    },
    navigation: [
        {
            path: "sw.blog.category.index",
            label: "sw-blog-category.general.mainMenuItemList",
            id: "sw-blog-category",
            parent: "sw-catalogue",
            color: "#176aaf",
            position: 100,
        },
    ],
});
import deDE from "./snippet/de-DE.json";
import enGB from "./snippet/en-GB.json";
const { Module } = Shopware;
Shopware.Component.register("sw-event-category-list", () =>
    import("./page/sw-event-category-list")
);
Shopware.Component.register("sw-event-category-detail", () =>
    import("./page/sw-event-category-detail")
);
Module.register("sw-event-category", {
    type: "plugin",
    name: "Event Category",
    title: "Event Category",
    description: "Event Category",
    color: "#176aaf",
    snippets: {
        "de-DE": deDE,
        "en-GB": enGB,
    },
    routes: {
        index: {
            component: "sw-event-category-list",
            path: "index",
            name: "sw.event.category.index",
        },
        create: {
            component: "sw-event-category-detail",
            path: "create",
            name: "sw.event.category.detail",
            meta: {
                parentPath: "sw.event.category.index",
                privilege: "event-category.creator",
            },
        },
        detail: {
            component: "sw-event-category-detail",
            path: "detail/:id",
            name: "sw.event.category.detail",
            meta: {
                parentPath: "sw.event.category.index",
                privilege: "event-category.viewer",
            },
            props: {
                default(route) {
                    return {
                        eventCategoryId: route.params.id,
                    };
                },
            },
        },
    },
    navigation: [
        {
            path: "sw.event.category.index",
            label: "sw-event-category.general.mainMenuItemList",
            id: "sw-event-category",
            parent: "sw-catalogue",
            color: "#176aaf",
            position: 100,
        },
    ],
});
import deDE from "./snippet/de-DE.json";
import enGB from "./snippet/en-GB.json";
const { Module } = Shopware;
Shopware.Component.register("sw-event-list", () =>
    import("./page/sw-event-list")
);
Shopware.Component.register("sw-event-detail", () =>
    import("./page/sw-event-detail")
);
Module.register("sw-event", {
    title: "Event",
    name: "Event",
    description: "Event",
    color: "#176aaf",
    snippets: {
        "de-DE": deDE,
        "en-GB": enGB,
    },
    routes: {
        index: {
            component: "sw-event-list",
            path: "index",
            name: "sw.event.index",
        },
        create: {
            component: 'sw-event-detail',
            path: 'create',
            name: "sw.event.detail",

            meta: {
                parentPath: 'sw.event.index',
                privilege: 'event.creator',
            },
        },
        detail: {
            component: 'sw-event-detail',
            path: 'detail/:id',
            name: "sw.event.detail",
            meta: {
                parentPath: 'sw.event.index',
                privilege: 'event.viewer',
            },
            props: {
                default(route) {
                    return {
                        eventId: route.params.id,
                    };
                },
            },
        },
    },
    navigation: [
        {
            path: "sw.event.index",
            label: "Event",
            id: "sw-event",
            parent: "sw-catalogue",
            color: "#176aaf",
            position: 100,
        },
    ],
});
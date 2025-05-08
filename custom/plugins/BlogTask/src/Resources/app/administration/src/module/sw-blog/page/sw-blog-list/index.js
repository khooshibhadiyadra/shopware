import template from "./sw-blog-list.html.twig";

const { Mixin } = Shopware;
const { Criteria } = Shopware.Data;

export default {
    template,
    inject: ["repositoryFactory"],
    data() {
        return {
            blog: null,
            repository: null,
            isLoading: false,
            total: 0,
        };
    },
    metaInfo() {
        return {
            title: this.$createTitle(),
        };
    },
    computed: {
        columns() {
            return this.getColumns();
        },
    },
    created() {
        this.createComponent();
    },
    methods: {
        createComponent() {
            this.isLoading = true;
            this.repository = this.repositoryFactory.create("blog");
            this.getList();
        },
        getList() {
            this.isLoading = true;
            return this.repository
                .search(new Criteria(), Shopware.Context.api)
                .then((result) => {
                    this.blog = result;
                    this.total = result.total;
                    this.isLoading = false;
                })
                .catch(() => {
                    this.isLoading = false;
                });
        },
        onChangeLanguage(languageId) {
            this.isLoading = true;
            Shopware.State.commit('context/setApiLanguageId', languageId);
            this.getList();
        },
        getColumns() {
            return [
                {
                    property: "name",
                    label: this.$tc("Name"),
                    routerLink: "sw.blog.detail",
                    inlineEdit: "string",
                    allowResize: true,
                    primary: true,
                },
                {
                    property: "description",
                    label: this.$tc("Description"),
                    inlineEdit: "string",
                    allowResize: true,
                    primary: false,
                },
                {
                    property: "author",
                    label: this.$tc("Author"),
                    inlineEdit: "string",
                    allowResize: true,
                    primary: false,
                },
                {
                    property: "active",
                    label: this.$tc("Active"),
                    inlineEdit: "boolean",
                    allowResize: true,
                    primary: false,
                    align: "center",
                    dataIndex: "active",
                    format: (value) => {
                        return value ? "Active" : "Inactive";
                    },
                    cellComponent: "sw-boolean-badge",
                },
                { property: 'releaseDate', label: 'blog.fields.releaseDate', allowResize: true },
            ];
        },
    },
};
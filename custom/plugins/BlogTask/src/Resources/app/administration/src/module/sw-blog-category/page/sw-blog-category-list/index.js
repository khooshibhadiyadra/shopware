import template from "./sw-blog-category-list.html.twig";

const { Mixin } = Shopware;
const { Criteria } = Shopware.Data;

export default {
    template,
    inject: ["repositoryFactory"],
    data() {
        return {
            blogCategory: null,
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
            this.repository = this.repositoryFactory.create("blog_category");
            this.getList();
        },
        getList() {
            this.isLoading = true;
            return this.repository
                .search(new Criteria(), Shopware.Context.api)
                .then((result) => {
                    this.blogCategory = result;
                    this.total = result.total;
                    this.isLoading = false;
                })
                .catch(() => {
                    this.isLoading = false;
                });
        },

        onChangeLanguage(languageId) {
            Shopware.State.commit("context/setApiLanguageId", languageId);
            this.getList();
        },
        getColumns() {
            return [
                {
                    property: "name",
                    label: "sw-blog-category.list.columnName",
                    routerLink: "sw.blog.category.detail",
                    inlineEdit: "string",
                    allowResize: true,
                    primary: true,
                },
                {
                    property: 'createdAt',
                    label: 'createdAt',
                    allowResize: true
                },
                {
                    property: 'updatedAt',
                    label: 'updatedAt',
                    allowResize: true
                }
            ];
        },
    },
};
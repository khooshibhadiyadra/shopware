import template from "./sw-blog-detail.html.twig";

const { Mixin } = Shopware;
const { Criteria } = Shopware.Data;

export default {
    template,
    inject: ["repositoryFactory"],
    mixins: [
        Mixin.getByName("placeholder"),
        Mixin.getByName("notification"),
        Mixin.getByName("discard-detail-page-changes")("blogCategory"),
    ],

    data() {
        return {
            blog: null,
            blogCategory: null,
            product: null,
            blogRepository: null,
            blogCategoryRepository: null,
            productRepository: null,
            isLoading: false,
            processSuccess: false,
            blogCategoryOptions: null,
        };
    },
    props: {
        blogId: {
            type: String,
            required: false,
            default: null,
        },
    },
    watch: {
        blogId() {
            this.createdComponent();
        },
    },
    metaInfo() {
        return {
            title: this.$createTitle(),
        };
    },
    created() {
        this.createdComponent();
    },
    methods: {
        createdComponent() {
            this.blogRepository = this.repositoryFactory.create("blog");
            this.blogCategoryRepository =
                this.repositoryFactory.create("blog_category");
            this.productRepository = this.repositoryFactory.create("product");
            this.getBlogCategory();
            this.getProduct();

            if (this.blogId) {
                this.getBlog();
                return;
            }
            Shopware.State.commit("context/resetLanguageToDefault");
            this.blog = this.blogRepository.create();
        },

        getBlog() {
            const criteria = new Criteria();
            criteria.addAssociation("blogCategories");
            criteria.addAssociation("products");

            this.blogRepository
                .get(this.$route.params.id, Shopware.Context.api, criteria)
                .then((entity) => {
                    this.blog = entity;
                });
        },
        getBlogCategory() {
            this.blogCategoryRepository
                .search(new Criteria(), Shopware.Context.api)
                .then((result) => {
                    this.blogCategoryOptions = result;
                });
        },
        getProduct() {
            this.productRepository
                .search(new Criteria(), Shopware.Context.api)
                .then((result) => {
                    this.product = result;
                });
        },
        abortOnLanguageChange() {
            return this.blogRepository.hasChanges(this.blog);
        },

        saveOnLanguageChange() {
            return this.onClickSave();
        },

        onChangeLanguage(languageId) {
            this.isLoading = true;
            Shopware.State.commit("context/setApiLanguageId", languageId);
            this.getBlog();
            this.getBlogCategory();
            this.getProduct();
        },
        onClickSave() {
            this.isLoading = true;
            this.blogRepository
                .save(this.blog)
                .then(() => {
                    this.isLoading = false;
                    this.processSuccess = true;
                    this.createNotificationSuccess({
                        title: this.$tc("success"),
                        message: this.$tc("success", 0, {
                            name: this.blog.name,
                        }),
                    });
                    if (this.blogId === null) {
                        this.$router.push({
                            name: "sw.blog.detail",
                            params: { id: this.blog.id },
                        });
                        return;
                    }
                    this.getBlog();
                })
                .catch((exception) => {
                    this.isLoading = false;
                    this.createNotificationError({
                        title: "exception",
                        message: exception,
                    });
                });
        },
        saveFinish() {
            this.processSuccess = false;
        },
    },
};
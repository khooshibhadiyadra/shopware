import template from "./sw-blog-category-detail.html.twig";

const { Mixin } = Shopware;
const { Criteria } = Shopware.Data;

export default {
    template,
    compatConfig: Shopware.compatConfig,

    inject: ["repositoryFactory"],
    mixins: [
        Mixin.getByName("placeholder"),
        Mixin.getByName("notification"),
        Mixin.getByName("discard-detail-page-changes")("blogCategory"),
    ],

    data() {
        return {
            blogCategory: null,
            repository: null,
            isLoading: false,
            processSuccess: false,
        };
    },
    props: {
        blogCategoryId: {
            type: String,
            required: false,
            default: null,
        },
    },
    metaInfo() {
        return {
            title: this.$createTitle(),
        };
    },
    watch: {
        blogCategoryId() {
            this.createdComponent();
        },
    },
    created() {
        this.createdComponent();
    },
    methods: {
        createdComponent() {
            this.repository = this.repositoryFactory.create("blog_category");
            if (this.blogCategoryId) {
                this.getCategory();
                return;
            }
            Shopware.State.commit("context/resetLanguageToDefault");
            this.blogCategory = this.repository.create();
        },
        abortOnLanguageChange() {
            return this.repository.hasChanges(this.blogCategory);
        },

        saveOnLanguageChange() {
            return this.onClickSave();
        },

        onChangeLanguage(languageId) {
            this.isLoading = true;
            Shopware.State.commit("context/setApiLanguageId", languageId);
            this.getCategory();
        },
        getCategory() {
            Shopware.ExtensionAPI.publishData({
                id: "sw-blog-category-detail__blog_category",
                path: "blog-category",
                scope: this,
            });
            const criteria = new Criteria();
            criteria.addAssociation("translations");

            this.repository
                .get(this.blogCategoryId, Shopware.Context.api, criteria)
                .then((entity) => {
                    console.log("entity", entity);
                    this.blogCategory = entity;
                });
        },
        onClickSave() {

            this.isLoading = true;
            this.repository
                .save(this.blogCategory)
                .then(() => {
                    this.isLoading = false;
                    this.processSuccess = true;
                    this.createNotificationSuccess({
                        title: this.$tc("success"),
                        message: this.$tc("success", 0, {
                            name: this.blogCategory.name,
                        }),
                    });
                    if (this.blogCategoryId === null) {
                        this.$router.push({
                            name: "detail",
                            params: { id: this.blogCategory.id },
                        });
                        return;
                    }
                    this.getCategory();
                })
        },
        saveFinish() {
            this.processSuccess = false;
        },
    },
};
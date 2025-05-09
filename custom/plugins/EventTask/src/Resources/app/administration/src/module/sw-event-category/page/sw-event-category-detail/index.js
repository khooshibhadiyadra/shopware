import template from "./sw-event-category-detail.html.twig";

const { Mixin } = Shopware;
const { Criteria } = Shopware.Data;

export default {
    template,
    compatConfig: Shopware.compatConfig,

    inject: ["repositoryFactory"],
    mixins: [
        Mixin.getByName("placeholder"),
        Mixin.getByName("notification"),
        Mixin.getByName("discard-detail-page-changes")("eventCategory"),
    ],

    data() {
        return {
            eventCategory: null,
            repository: null,
            isLoading: false,
            processSuccess: false,
        };
    },
    props: {
        eventCategoryId: {
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
        eventCategoryId() {
            this.createdComponent();
        },
    },
    created() {
        this.createdComponent();
    },
    methods: {
        createdComponent() {
            this.repository = this.repositoryFactory.create("event_category");
            if (this.eventCategoryId) {
                this.getCategory();
                return;
            }
            Shopware.State.commit("context/resetLanguageToDefault");
            this.eventCategory = this.repository.create();
        },
        abortOnLanguageChange() {
            return this.repository.hasChanges(this.eventCategory);
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
                id: "sw-event-category-detail__event_category",
                path: "event-category",
                scope: this,
            });
            const criteria = new Criteria();
            criteria.addAssociation("translations");

            this.repository
                .get(this.eventCategoryId, Shopware.Context.api, criteria)
                .then((entity) => {
                    console.log("entity", entity);
                    this.eventCategory = entity;
                });
        },
        onClickSave() {

            this.isLoading = true;
            this.repository
                .save(this.eventCategory)
                .then(() => {
                    this.isLoading = false;
                    this.processSuccess = true;
                    this.createNotificationSuccess({
                        title: this.$tc("success"),
                        message: this.$tc("success", 0, {
                            name: this.eventCategory.name,
                        }),
                    });
                    if (this.eventCategoryId === null) {
                        this.$router.push({
                            name: "detail",
                            params: { id: this.eventCategory.id },
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
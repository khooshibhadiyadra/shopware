import template from "./sw-event-detail.html.twig";

const { Mixin } = Shopware;
const { Criteria } = Shopware.Data;

export default {
    template,
    inject: ["repositoryFactory"],
    mixins: [
        Mixin.getByName("placeholder"),
        Mixin.getByName("notification"),
        Mixin.getByName("discard-detail-page-changes")("eventCategory"),
    ],

    data() {
        return {
            event: null,
            eventCategory: null,
            product: null,
            eventRepository: null,
            eventCategoryRepository: null,
            productRepository: null,
            isLoading: false,
            processSuccess: false,
            eventCategoryOptions: null,
        };
    },
    props: {
        eventId: {
            type: String,
            required: false,
            default: null,
        },
    },
    watch: {
        eventId() {
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
            this.eventRepository = this.repositoryFactory.create("event");
            this.eventCategoryRepository =
                this.repositoryFactory.create("event_category");
            this.productRepository = this.repositoryFactory.create("product");
            this.getEventCategory();
            this.getProduct();

            if (this.eventId) {
                this.getEvent();
                return;
            }
            Shopware.State.commit("context/resetLanguageToDefault");
            this.event = this.eventRepository.create();
        },

        getEvent() {
            const criteria = new Criteria();
            criteria.addAssociation("eventCategories");
            criteria.addAssociation("products");

            this.eventRepository
                .get(this.$route.params.id, Shopware.Context.api, criteria)
                .then((entity) => {
                    this.event = entity;
                });
        },
        getEventCategory() {
            this.eventCategoryRepository
                .search(new Criteria(), Shopware.Context.api)
                .then((result) => {
                    this.eventCategoryOptions = result;
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
            return this.eventRepository.hasChanges(this.event);
        },

        saveOnLanguageChange() {
            return this.onClickSave();
        },

        onChangeLanguage(languageId) {
            this.isLoading = true;
            Shopware.State.commit("context/setApiLanguageId", languageId);
            this.getEvent();
            this.getEventCategory();
            this.getProduct();
        },
        onClickSave() {
            this.isLoading = true;
            this.eventRepository
                .save(this.event)
                .then(() => {
                    this.isLoading = false;
                    this.processSuccess = true;
                    this.createNotificationSuccess({
                        title: this.$tc("success"),
                        message: this.$tc("success", 0, {
                            name: this.event.name,
                        }),
                    });
                    if (this.eventId === null) {
                        this.$router.push({
                            name: "sw.event.detail",
                            params: { id: this.event.id },
                        });
                        return;
                    }
                    this.getEvent();
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
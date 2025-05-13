import template from "./sw-event-detail.html.twig";

const {Mixin} = Shopware;
const {Criteria} = Shopware.Data;

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
            customer: null,
            eventRepository: null,
            eventCategoryRepository: null,
            customerRepository: null,
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
    computed: {
        customerCriteria() {
            const criteria = new Criteria();
            return criteria;
        }
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
            this.customerRepository = this.repositoryFactory.create("customer");
            this.getEventCategory();
            this.getCustomer();

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
            criteria.addAssociation("organizedBy");

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
                    // console.log(result);
                });
        },
        getCustomer() {
            this.customerRepository
                .search(new Criteria(), Shopware.Context.api)
                .then((result) => {
                    // this.customer = result;
                    // console.log(result);
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
            this.getCustomer();
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
                            params: {id: this.event.id},
                        });
                        return;
                    }
                    this.getEvent();
                    // this.getCustomer();
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
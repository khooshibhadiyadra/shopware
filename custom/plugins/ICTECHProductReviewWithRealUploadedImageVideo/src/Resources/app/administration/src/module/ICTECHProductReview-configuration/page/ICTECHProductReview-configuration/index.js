import template from './ICTECHProductReview-configuration.html.twig';

const {Component, Mixin, Defaults} = Shopware;
const {Criteria} = Shopware.Data;
Component.register('ICTECHProductReview-configuration', {

    template,

    inject: [
        'repositoryFactory',
        'configService',
        'acl',
    ],

    computed: {
        salesChannelRepository() {
            return this.repositoryFactory.create('sales_channel');
        },
    },

    mixins: [
        Mixin.getByName('notification'),
    ],

    data() {
        return {
            isLoading: false,
            isSaveSuccessful: false,
            config: null,
            salesChannels: [],
            salesChannelMain: {salesChannelId: null, id: null},
            domainId: null,
            imageFileExtentionParam: [{id: 'jpg', name: 'JPG'}, {id: 'png', name: 'PNG'}],
            videoFileExtentionParam: [{id: 'mp4', name: 'MP4'}, {id: 'webm', name: 'WEBM'}, {id: 'mov', name: 'MOV'}],
            maxUploadFileSize: null,
            maxUploadFileSizeValue: null,
        }
    },

    watch: {
        config: {
            handler() {
                this.domainId = this.$refs.configComponent.selectedSalesChannelId;
            },
            deep: true
        }
    },

    created() {
        this.createdComponent();
        this.getFileSize();
    },

    methods: {
        createdComponent() {

            this.isLoading = true;
            const criteria = new Criteria();
            criteria.addFilter(
                Criteria.equalsAny('typeId', [
                    Defaults.storefrontSalesChannelTypeId,
                    Defaults.apiSalesChannelTypeId
                ])
            );

            this.salesChannelRepository.search(criteria, Shopware.Context.api).then(res => {
                res.add({
                    id: null,
                    translated: {
                        name: this.$tc('sw-sales-channel-switch.labelDefaultOption')
                    }
                });
                this.salesChannels = res;
            }).finally(() => {
                this.isLoading = false;
            });
        },
        getFileSize() {
            const headers = this.configService.getBasicHeaders();

            return this.configService.httpClient.get('productReview/maxFileUpload', { headers })
                .then((response) => {
                    console.log('API response:', response);

                    const rawSize = response.data.maxUploadFileSize;

                    let sizeValue;

                    if (typeof rawSize === 'string') {
                        sizeValue = parseInt(rawSize.replace(/[^\d]/g, ''), 10);
                    } else if (typeof rawSize === 'number') {
                        sizeValue = rawSize;
                    } else {
                        console.warn('Unexpected type for maxUploadFileSize:', typeof rawSize);
                        sizeValue = 100; // fallback default
                    }

                    this.maxUploadFileSizeValue = sizeValue || 100;
                })
                .catch((err) => {
                    console.error('Error fetching file size:', err);
                    this.maxUploadFileSizeValue = 100; // fallback default on failure
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },


        checkInheritance(value) {
            return value === null || value === undefined;
        },
        checkBoolFieldInheritance(value) {
            return typeof value !== 'boolean';
        },
        imageFileExtentionOptions(imageFileExtentionParam) {
            return imageFileExtentionParam;
        },
        videoFileExtentionOptions(videoFileExtentionParam) {
            return videoFileExtentionParam;
        },
        onSave() {
            this.isLoading = true;

            // ✅ STEP 1: Force default values into actualConfigData if missing
            if (!this.$refs.configComponent.actualConfigData['ICTECHProductReviewWithRealUploadedImageVideo.config.maximumFileSizeforImage']) {
                this.$refs.configComponent.actualConfigData['ICTECHProductReviewWithRealUploadedImageVideo.config.maximumFileSizeforImage'] = this.defaultImageSize;
            }

            if (!this.$refs.configComponent.actualConfigData['ICTECHProductReviewWithRealUploadedImageVideo.config.maximumFileSizeForVideo']) {
                this.$refs.configComponent.actualConfigData['ICTECHProductReviewWithRealUploadedImageVideo.config.maximumFileSizeForVideo'] = this.defaultVideoSize;
            }

            // ✅ STEP 2: Now retrieve config values (they’ll have defaults if needed)
            const pluginActive = this.$refs.configComponent.actualConfigData['ICTECHProductReviewWithRealUploadedImageVideo.config.pluginActive'];
            const displayImageField = this.$refs.configComponent.actualConfigData['ICTECHProductReviewWithRealUploadedImageVideo.config.displayImageField'];
            const allowImageFileExtension = this.$refs.configComponent.actualConfigData['ICTECHProductReviewWithRealUploadedImageVideo.config.allowImageFileExtension'];
            const allowVideoFileExtension = this.$refs.configComponent.actualConfigData['ICTECHProductReviewWithRealUploadedImageVideo.config.allowVideoFileExtension'];
            const maximumFileSizeForVideo = this.$refs.configComponent.actualConfigData['ICTECHProductReviewWithRealUploadedImageVideo.config.maximumFileSizeForVideo'];
            const maximumFileSizeforImage = this.$refs.configComponent.actualConfigData['ICTECHProductReviewWithRealUploadedImageVideo.config.maximumFileSizeforImage'];

            // ✅ STEP 3: Your validation logic continues as-is
            if (pluginActive !== undefined && pluginActive !== false) {
                if (displayImageField !== undefined && displayImageField !== false && allowImageFileExtension == undefined) {
                    this.createNotificationError({
                        title: this.$tc('product-review-configuration.action.titleSaveError'),
                        message: this.$tc('product-review-configuration.action.messageSaveImageFileExtentionError')
                    });
                    this.isLoading = false;
                } else if (displayImageField !== undefined && displayImageField !== false && allowImageFileExtension.length === 0) {
                    this.createNotificationError({
                        title: this.$tc('product-review-configuration.action.titleSaveError'),
                        message: this.$tc('product-review-configuration.action.messageSaveImageFileExtentionError')
                    });
                    this.isLoading = false;
                } else if (displayImageField !== undefined && displayImageField !== false && (maximumFileSizeforImage == null || maximumFileSizeforImage == undefined)) {
                    this.createNotificationError({
                        title: this.$tc('product-review-configuration.action.titleSaveError'),
                        message: this.$tc('product-review-configuration.action.messageSaveImageFileSizeError')
                    });
                    this.isLoading = false;
                } else if (allowVideoFileExtension == undefined) {
                    this.createNotificationError({
                        title: this.$tc('product-review-configuration.action.titleSaveError'),
                        message: this.$tc('product-review-configuration.action.messageSaveVideoFileExtentionError')
                    });
                    this.isLoading = false;
                } else if (allowVideoFileExtension.length === 0) {
                    this.createNotificationError({
                        title: this.$tc('product-review-configuration.action.titleSaveError'),
                        message: this.$tc('product-review-configuration.action.messageSaveVideoFileExtentionError')
                    });
                    this.isLoading = false;
                } else if (maximumFileSizeForVideo == null) {
                    this.createNotificationError({
                        title: this.$tc('product-review-configuration.action.titleSaveError'),
                        message: this.$tc('product-review-configuration.action.messageSaveVideoFileSizeError')
                    });
                    this.isLoading = false;
                } else {
                    this.$refs.configComponent.save().then(() => {
                        this.isSaveSuccessful = true;
                        this.createNotificationSuccess({
                            title: this.$tc('product-review-configuration.action.titleSaveSuccess'),
                            message: this.$tc('product-review-configuration.action.messageSaveSuccess')
                        });
                    }).finally(() => {
                        this.isLoading = false;
                    });
                }
            } else {
                this.$refs.configComponent.save().then(() => {
                    this.isSaveSuccessful = true;
                    this.createNotificationSuccess({
                        title: this.$tc('product-review-configuration.action.titleSaveSuccess'),
                        message: this.$tc('product-review-configuration.action.messageSaveSuccess')
                    });
                }).finally(() => {
                    this.isLoading = false;
                });
            }
        }

    }
});
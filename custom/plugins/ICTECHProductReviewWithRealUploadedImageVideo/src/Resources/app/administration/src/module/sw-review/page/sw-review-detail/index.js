import template from './sw-review-detail.html.twig';

const {Criteria} = Shopware.Data;

/**
 * @content
 */

Shopware.Component.override('sw-review-detail', {
    template,
    data() {
        return {
            videoData: null,
            imageData: null,
        };
    },
    computed: {
        /* Add media repository */
        mediaRepository() {
            return this.repositoryFactory.create('media');
        },
        stars() {
            if (this.review.points >= 0) {
                return this.review.points;
            }

            return 0;
        },
        showCustomFields() {
            return this.review && this.customFieldSets && this.customFieldSets.length > 0;
        },
    },

    methods: {
        loadEntityData() {
            this.isLoading = true;
            const criteria = new Criteria(1, 25);
            criteria.addAssociation('customer');
            criteria.addAssociation('salesChannel');
            criteria.addAssociation('product');

            const context = {...Shopware.Context.api, inheritance: true};

            this.repository.get(this.reviewId, context, criteria).then((review) => {
                /* Add image and video data to display in product review detail page at admin side */
                if (review.customFields.ICTECHImageVideoData) {
                    if (review.customFields.ICTECHImageVideoData.image) {
                        this.mediaRepository.get(review.customFields.ICTECHImageVideoData.image).then(imageResponse => {
                            this.imageData = imageResponse;
                        });
                    }
                    if (review.customFields.ICTECHImageVideoData.video) {
                        this.mediaRepository.get(review.customFields.ICTECHImageVideoData.video).then(videoResponse => {
                            this.videoData = videoResponse;
                        });
                    }
                }
                this.review = review;
                this.isLoading = false;
            });
        },
        loadCustomFieldSets() {
            this.customFieldDataProviderService.getCustomFieldSets('product_review').then((sets) => {
                this.customFieldSets = sets;
            });
        },
    },
});

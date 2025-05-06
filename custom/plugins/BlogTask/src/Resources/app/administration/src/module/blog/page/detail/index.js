import template from './blog-detail.html.twig';

const { Criteria } = Shopware.Data;

Shopware.Component.register('blog-detail', {
    template,

    inject: ['repositoryFactory'],

    props: {
        blogId: {
            type: String,
            required: false,
            default: null
        }
    },

    data() {
        return {
            blog: null,
            isLoading: false,
            isSaveSuccessful: false
        };
    },

    computed: {
        isNew() {
            return !this.blogId;
        },

        blogRepository() {
            return this.repositoryFactory.create('blog');
        },
    },

    created() {
        this.loadBlog();
    },

    methods: {
        async loadBlog() {
            this.isLoading = true;
            try {
                if (this.isNew) {
                    this.blog = this.blogRepository.create(Shopware.Context.api);
                } else {
                    const criteria = new Criteria();
                    // criteria.addAssociation('categories');
                    criteria.addAssociation('blogCategories');
                    criteria.addAssociation('products');

                    this.blog = await this.blogRepository.get(this.blogId, Shopware.Context.api, criteria);
                }
            } catch (e) {
                console.error('Failed to load blog', e);
            } finally {
                this.isLoading = false;
            }
        },

        async onSave() {
            this.isLoading = true;
            try {
                await this.blogRepository.save(this.blog, Shopware.Context.api);
                this.isSaveSuccessful = true;

                if (this.isNew) {
                    this.$router.push({ name: 'blog.module.detail', params: { id: this.blog.id } });
                } else {
                    await this.loadBlog();
                    this.$router.push({ name: 'blog.module.detail' });
                }
            } catch (e) {
                this.createNotificationError({
                    message: this.$tc('global.notification.notificationSaveErrorMessageRequiredFieldsInvalid'),
                });
                console.error('Save failed', e);

            } finally {
                this.isLoading = false;
            }
        },

        async saveOnLanguageChange() {
            await this.onSave();
        },

        async onChangeLanguage() {
            await this.loadBlog();
        },

        onCancel() {
            this.$router.push({ name: 'blog.module.index' });
        }
    }
});
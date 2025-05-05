import template from './blog-category-detail.html.twig';

const { Component } = Shopware;

Component.register('blog-category-detail', {
    template,

    inject: ['repositoryFactory'],

    props: {
        blogCategoryId: {
            type: String,
            required: false,
            default: null
        }
    },
    data() {
        return {
            blogCategory: null,
            isLoading: false,
            isSaveSuccessful: false
        };
    },
    computed: {
        isNew() {
            return !this.blogCategoryId;
        },

        blogCategoryRepository() {
            return this.repositoryFactory.create('blog_category');
        }
    },

    created() {
        this.loadBlogCategory();
    },

    methods: {
        async loadBlogCategory() {
            this.isLoading = true;

            try {
                if (this.isNew) {
                    this.blogCategory = this.blogCategoryRepository.create(Shopware.Context.api);
                } else {
                    this.blogCategory = await this.blogCategoryRepository.get(this.blogCategoryId, Shopware.Context.api);

                }
            } catch (e) {
                console.error('Failed to load blog category', e);
            } finally {
                this.isLoading = false;
            }
        },

        async onSave() {
            this.isLoading = true;
            console.log(this.blogCategory);

            try {
                await this.blogCategoryRepository.save(this.blogCategory, Shopware.Context.api);
                this.isSaveSuccessful = true;
                if (this.isNew) {
                    this.$router.push({ name: 'blog.category.index', params: { id: this.blogCategory.id } });
                } else {
                    await this.loadBlogCategory();
                    this.$router.push({ name: 'blog.category.index'});
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
            await this.loadBlogCategory();
        },

        onCancel() {
            this.$router.push({ name: 'blog.category.index' });
        }
    }
});


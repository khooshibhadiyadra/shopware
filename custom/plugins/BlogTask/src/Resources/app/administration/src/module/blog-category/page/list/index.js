import template from './blog-category-list.html.twig';

const { Component } = Shopware;
const { Criteria } = Shopware.Data;
const { Mixin } = Shopware;

Component.register('blog-category-list', {
    template,

    inject: ['repositoryFactory'],

    mixins: [
        Mixin.getByName('listing'),
    ],

    data() {
        return {
            blogCategories: null,
            isLoading: false,
            sortBy: 'name',
            sortDirection: 'ASC',
            total: 0,
            term: ''
        };
    },

    computed: {
        blogCategoryRepository() {
            return this.repositoryFactory.create('blog_category');
        },

        blogCategoryColumns() {
            return [
                {
                    property: 'name',
                    label: 'blog-module.list.name',
                    routerLink: 'blog.category.detail',
                    inlineEdit: 'string',
                    primary: true,
                    allowResize: true
                },
                {
                    property: 'createdAt',
                    label: 'blog-module.list.createdAt',
                    allowResize: true
                },
                {
                    property: 'updatedAt',
                    label: 'blog-module.list.updatedAt',
                    allowResize: true
                }
            ];
        },

        blogCategoryCriteria() {
            const criteria = new Criteria(this.page, this.limit);
            criteria.setTerm(this.term);
            criteria.addSorting(
                Criteria.sort(this.sortBy, this.sortDirection, this.naturalSorting)
            );
            return criteria;
        }
    },

    watch: {
        page() {
            this.getList();
        },
        limit() {
            this.getList();
        },
        term() {
            this.page = 1;
            this.getList();
        }
    },

    mounted() {
        this.getList();
    },

    methods: {
        async getList() {
            // console.log(this.blogCategories);
            this.isLoading = true;
            // alert(this.total);
            try {

                const criteria = await this.addQueryScores(this.term, this.blogCategoryCriteria);

                if (!this.entitySearchable) {
                    this.isLoading = false;
                    return;
                }

                const result = await this.blogCategoryRepository.search(criteria);

                this.blogCategories = result;
                this.total = result.total;
            } catch (error) {
                console.error('Failed to fetch categories:', error);
            } finally {
                this.isLoading = false;
            }
        },

        onSearch(term) {
            this.term = term;
        },
        onCreateNewCategory() {
            this.$router.push({ name: 'blog.category.create' });
        },
        onChangeLanguage() {
            this.getList();
        }
    }
});
import template from './blog-list.html.twig';

const { Criteria } = Shopware.Data;
const { Mixin } = Shopware;

Shopware.Component.register('blog-list', {
    template,

    inject: ['repositoryFactory'],

    mixins: [
        Mixin.getByName('listing'),
    ],

    data() {
        return {
            blogs: null,
            isLoading: false,
            sortBy: 'name',
            sortDirection: 'ASC',
            total: 0,
            term: ''
        };
    },

    computed: {
        blogRepository() {
            return this.repositoryFactory.create('blog');
        },

        blogColumns() {
            return [
                { property: 'name', label: 'blog.fields.name', routerLink: 'blog.module.detail', inlineEdit: 'string', primary: true, allowResize: true },
                { property: 'description', label: 'blog.fields.description', allowResize: true },
                { property: 'author', label: 'blog.fields.author', allowResize: true },
                { property: 'releaseDate', label: 'blog.fields.releaseDate', allowResize: true },
                { property: 'active', label: 'blog.fields.active', allowResize: true },
                {property: 'blogCategories',label:'blog.fields.categories',allowResize: true},
                { property: 'products', label: 'blog.fields.products', allowResize: true }
            ];
        },

        blogCriteria() {
            const criteria = new Criteria(this.page, this.limit);
            criteria.setTerm(this.term);
            criteria.addSorting(Criteria.sort(this.sortBy, this.sortDirection, this.naturalSorting));
            criteria.addAssociation('blogCategories');
            criteria.addAssociation('products');
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
            this.isLoading = true;

            try {
                // if (!Shopware.State.getters['context/isSystemDefaultLanguage']) {
                //     Shopware.State.commit('context/resetLanguageToDefault');
                // }
                const criteria = await this.addQueryScores(this.term, this.blogCriteria);

                if (!this.entitySearchable) {
                    this.isLoading = false;
                    return;
                }
                const result = await this.blogRepository.search(criteria, Shopware.Context.api);
                this.blogs = result;
                this.total = result.total;
            console.log(this.blogs);
            }
            catch (error) {
                console.error('Failed to fetch blog', error);
            } finally {
                this.isLoading = false;
            }
        },

        onSearch(term) {
            this.term = term;
        },

        onCreateNewBlog() {
            this.$router.push({ name: 'blog.module.create' });
        },

        onChangeLanguage() {
            this.getList();
        }
    }
});
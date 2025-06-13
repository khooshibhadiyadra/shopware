import ICTECHProductReview from './ICTECHProductReview/ICTECHProductReview.plugin';

const PluginManager = window.PluginManager;

PluginManager.register('ICTECHProductReview', ICTECHProductReview, '[data-ictech-product-review]');

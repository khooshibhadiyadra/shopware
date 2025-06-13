import './page/ICTECHProductReview-configuration';

import deDE from './snippet/de-DE.json';
import enGB from './snippet/en-GB.json';

Shopware.Module.register(
    'ICTECHProductReview-configuration',
    {
        type: 'plugin',
        name: 'Product Review Configuration',
        title: 'product-review-configuration.general.mainMenuItemGeneral',
        description: 'product-review-configuration.general.descriptionTextModule',
        color: '#ff3d58',

        snippets: {
            'de-DE': deDE,
            'en-GB': enGB
        },
        routes: {
            index: {
                component: 'ICTECHProductReview-configuration',
                path: 'index',
                meta: {
                    parentPath: 'sw.settings.index',
                }
            }
        },
        settingsItem: {
            group: 'plugins',
            to: 'ICTECHProductReview.configuration.index',
            backgroundEnabled: true,
            icon: 'regular-star'
        }
    }
);

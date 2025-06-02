<?php declare(strict_types=1);

namespace CustomPlugin\Service;

use Shopware\Core\Defaults;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\CustomField\CustomFieldTypes;

class CustomFieldsInstaller
{
    private const CUSTOM_FIELDSET_NAME = 'swag_example_set';
    private const CUSTOM_FIELDSET_DESCRIPTION='swag_example_set_description';
    private const CUSTOM_FIELDSET_MEDIA='swag_example_set_media';


    private const CUSTOM_FIELDSET = [
        'name' => self::CUSTOM_FIELDSET_NAME,
        'description'=>self::CUSTOM_FIELDSET_DESCRIPTION,
        'media'=>self::CUSTOM_FIELDSET_MEDIA,

        'config' => [
            'label' => [
                'en-GB' => 'Custom Configuration',
                'de-DE' => 'Custom configuration',
                Defaults::LANGUAGE_SYSTEM => 'Mention the fallback label here'
            ]
        ],
        'customFields' => [
            [
                'name' => 'get_names',
                'type' => CustomFieldTypes::INT,
                'config' => [
                    'label' => [
                        'en-GB' => 'English custom field label',
                        'de-DE' => 'German custom field label',
                        Defaults::LANGUAGE_SYSTEM => 'Mention the fallback label here'
                    ],
                    'customFieldPosition' => 1
                ]
            ],
            [
            'name'=> 'get_description',
            'type'=>CustomFieldTypes::TEXT,
            'config'=> [
                'label' => [
                    'componentName' => 'sw-single-select',
                    'customFieldType' => CustomFieldTypes::SELECT,
                    'en-GB' => 'add description',
                    'de-DE'=>'ge description',
                    Defaults::LANGUAGE_SYSTEM => 'Mention the fallback label here'
                ],
                'customFieldPosition' => 2
            ]
        ],
            [
                'name' => 'get_toggle',
                'type' => CustomFieldTypes::SELECT,
                'config' => [
                    'componentName' => 'sw-single-select',
                    'customFieldType' => CustomFieldTypes::SELECT,
                    'label' => [
                        'en-GB' => 'Activate Offer note',
                        'de-DE' => 'Angebot Hinweis aktivieren',
                        Defaults::LANGUAGE_SYSTEM => "Note Text"
                    ],
                    'options' => [
                        [
                            'label' => [
                                'en-GB' => 'ON',
                                'de-DE' => 'An',
                            ],
                            'value' => 'true',
                        ],
                        [
                            'label' => [
                                'en-GB' => 'OFF',
                                'de-DE' => 'Aus',
                            ],
                            'value' => 'false',
                        ]
                    ],
                    'customFieldPosition' => 5
                ]
            ],
            [
                'name'=> self::CUSTOM_FIELDSET_MEDIA .'get_image',
                'type'=>'media',
                'config'=> [
                    'type'=>'media',
                    'componentName'=>'sw-media-field',
                    'label' => [
                        'en-GB' => 'add media',
                        'de-DE'=>'ge media',
                        Defaults::LANGUAGE_SYSTEM => 'media'
                    ],
                    'customFieldPosition' => 6
                ]
            ],
            ],
    ];

    public function __construct(
        private readonly EntityRepository $customFieldSetRepository,
        private readonly EntityRepository $customFieldSetRelationRepository
    ) {
    }

    public function install(Context $context): void
    {
        $this->customFieldSetRepository->upsert([
            self::CUSTOM_FIELDSET
        ], $context);
    }

    public function addRelations(Context $context): void
    {
        $this->customFieldSetRelationRepository->upsert(array_map(function (string $customFieldSetId) {
            return [
                'customFieldSetId' => $customFieldSetId,
                'entityName' => 'product',
            ];
        }, $this->getCustomFieldSetIds($context)), $context);
    }

    /**
     * @return string[]
     */
    private function getCustomFieldSetIds(Context $context): array
    {
        $criteria = new Criteria();

        $criteria->addFilter(new EqualsFilter('name', self::CUSTOM_FIELDSET_NAME));
        $criteria->addFilter(new EqualsFilter('name', self::CUSTOM_FIELDSET_DESCRIPTION));
        $criteria->addFilter(new EqualsFilter('name', self::CUSTOM_FIELDSET_MEDIA));
        return $this->customFieldSetRepository->searchIds($criteria, $context)->getIds();
    }
}

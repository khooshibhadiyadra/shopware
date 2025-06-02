<?php declare(strict_types=1);

namespace CustomPlugin;

use OpenApi\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsAnyFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\IdSearchResult;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\ActivateContext;
use Shopware\Core\Framework\Plugin\Context\DeactivateContext;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Shopware\Core\Framework\Plugin\Context\UpdateContext;
use CustomPlugin\Service\CustomFieldsInstaller;

class CustomPlugin extends Plugin
{
    public function install(InstallContext $installContext): void
    {
        // Do stuff such as creating a new payment method
//$context=$installContext->getContext();
//$customFieldRepository=$this->container->get('custom_field_repository');

        $this->getCustomFieldsInstaller()->install($installContext->getContext());
    }

    public function uninstall(UninstallContext $uninstallContext): void
    {
        parent::uninstall($uninstallContext);

        if ($uninstallContext->keepUserData()) {
            return;
        }
        $this->removeCustomFields($uninstallContext);

    }

    public function activate(ActivateContext $activateContext): void
    {
        // Activate entities, such as a new payment method
        // Or create new entities here, because now your plugin is installed and active for sure

        $this->getCustomFieldsInstaller()->addRelations($activateContext->getContext());
    }

    public function deactivate(DeactivateContext $deactivateContext): void
    {
        // Deactivate entities, such as a new payment method
        // Or remove previously created entities
    }

    public function update(UpdateContext $updateContext): void
    {
        // Update necessary stuff, mostly non-database related
    }

    public function postInstall(InstallContext $installContext): void
    {
    }

    public function postUpdate(UpdateContext $updateContext): void
    {
    }

    private function getCustomFieldsInstaller(): CustomFieldsInstaller
    {
        if ($this->container->has(CustomFieldsInstaller::class)) {
            return $this->container->get(CustomFieldsInstaller::class);
        }

        return new CustomFieldsInstaller(
            $this->container->get('custom_field_set.repository'),
            $this->container->get('custom_field_set_relation.repository')
        );
    }

    private function removeCustomFields(): void
    {
        $customFieldRepository = $this->container->get('custom_field_set.repository');
//        $mediaSet = $this->customFieldExist($uninstallContext->getContext(), self::CUSTOM_FIELD_SET);

//if ($mediaSet)
//{
//$customFieldRepository->delete(array_values($mediaSet->getData()), $uninstallContext->getContext());
//}
}
private function customFieldExist(Context $context,string $setName): ?IdSearchResult{
        $criteria = new Criteria();
        $customFieldRepository=$this->container->get('custom_field_set.repository');
    $criteria->addFilter(new EqualsAnyFilter('name',[$setName]));
    $ids=$customFieldRepository->searchIds($criteria, $context);
return $ids->getTotal() > 0 ? $ids->getFirst() : null;
}

}

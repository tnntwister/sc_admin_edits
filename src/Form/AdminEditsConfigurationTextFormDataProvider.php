<?php
declare(strict_types=1);

namespace PrestaShop\Module\Sc_Admin_Edits\Form;

use PrestaShop\PrestaShop\Core\Configuration\DataConfigurationInterface;
use PrestaShop\PrestaShop\Core\Form\FormDataProviderInterface;

/**
 * Provider ir responsible for providing form data, in this case it's as simple as using configuration to do that
 *
 * Class AdminEditsConfigurationTextFormDataProvider
 */
class AdminEditsConfigurationTextFormDataProvider implements FormDataProviderInterface
{
    /**
     * @var DataConfigurationInterface
     */
    private $adminEditsConfigurationTextDataConfiguration;

    /**
     * @param DataConfigurationInterface $adminEditsConfigurationTextDataConfiguration
     */
    public function __construct(DataConfigurationInterface $adminEditsConfigurationTextDataConfiguration)
    {
        $this->adminEditsConfigurationTextDataConfiguration = $adminEditsConfigurationTextDataConfiguration;
    }

    /**
     * {@inheritdoc}
     */
    public function getData(): array
    {
        return $this->adminEditsConfigurationTextDataConfiguration->getConfiguration();
    }

    /**
     * {@inheritdoc}
     */
    public function setData(array $data): array
    {
        return $this->adminEditsConfigurationTextDataConfiguration->updateConfiguration($data);
    }
}
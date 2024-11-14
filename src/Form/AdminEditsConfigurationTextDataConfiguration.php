<?php 
declare(strict_types=1);

namespace PrestaShop\Module\Sc_Admin_Edits\Form;

use PrestaShop\PrestaShop\Core\Configuration\DataConfigurationInterface;
use PrestaShop\PrestaShop\Core\ConfigurationInterface;
use PrestaShop\PrestaShop\Adapter\Language\LanguageDataProvider;
use Validate;
use Tools;

/**
 * Configuration is used to save data to configuration table and retrieve from it
 */
final class AdminEditsConfigurationTextDataConfiguration implements DataConfigurationInterface
{
    public const ADMIN_EDITS_ADD_SUPPLIER_COLUMN_IN_PRODUCTS_VIEW = 'ADMIN_EDITS_ADD_SUPPLIER_COLUMN_IN_PRODUCTS_VIEW';

    /**
     * @var ConfigurationInterface
     */
    private $configuration;

    public function __construct(ConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
    }

    public function getConfiguration(): array
    {
        $return = [];
        $return['add_supplier_column_in_products_view'] = $this->configuration->get(static::ADMIN_EDITS_ADD_SUPPLIER_COLUMN_IN_PRODUCTS_VIEW);

        return $return;
    }

    public function updateConfiguration(array $configuration): array
    {
        $errors = [];

        if ($this->validateConfiguration($configuration)) {
            if (is_bool($configuration['add_supplier_column_in_products_view'])) {
                $this->configuration->set(static::ADMIN_EDITS_ADD_SUPPLIER_COLUMN_IN_PRODUCTS_VIEW, $configuration['add_supplier_column_in_products_view']);
            } else {
                $errors[] = 'ADMIN_EDITS_ADD_SUPPLIER_COLUMN_IN_PRODUCTS_VIEW value is not a boolean';
            }
        }
       return $errors;
    }

    /**
     * Ensure the parameters passed are valid.
     *
     * @return bool Returns true if no exception are thrown
     */
    public function validateConfiguration(array $configuration): bool
    {
        return isset($configuration['add_supplier_column_in_products_view']);
    }
}
<?php 
declare(strict_types=1);

use PrestaShop\PrestaShop\Adapter\SymfonyContainer;
use PrestaShop\PrestaShop\Adapter\Configuration;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn;
use Doctrine\DBAL\Query\QueryBuilder;

class Sc_Admin_Edits extends Module
{
    public function __construct()
    {
        $this->name = 'sc_admin_edits';
        $this->author = 'François-Xavier Guillois';
        $this->tab = 'administration'; 
        $this->version = '1.0.0';
        $this->need_instance = 0;
        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->trans('Modifications dans le back-office', [], 'Modules.Sc_Admin_Edits.Admin');
        $this->description = $this->trans(
            'Module créé pour modifier le back office de prestashop',
            [],
            'Modules.Sc_Admin_Edits.Admin'
        );

        $this->ps_versions_compliancy = ['min' => '1.7.6.7', 'max' => '8.99.99'];
    }

    public function install()
    {
        return parent::install() 
        && $this->addPermanentlyOutofStockField()
        // && $this->registerHook('displayHome') 
        // && $this->registerHook('actionAdminProductsListingFieldsModifier')
        && $this->registerHook('displayAdminProductsCombinationBottom')
        && $this->registerHook('actionAdminProductsCombinationFormModifier')
        && $this->registerHook('actionProductGridDefinitionModifier') 
        && $this->registerHook('actionProductGridQueryBuilderModifier');
        
    }

    public function uninstall()
    {
        return parent::uninstall()
        && $this->removePermanentlyOutofStockField()    ;
    }

    private function addPermanentlyOutofStockField()
    {
        return Db::getInstance()->execute('
            ALTER TABLE `'._DB_PREFIX_.'product_attribute`
            ADD `permanently_out_of_stock` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
        ');
    }

    private function removePermanentlyOutofStockField()
    {
        return Db::getInstance()->execute('
            ALTER TABLE `'._DB_PREFIX_.'product_attribute`
            DROP COLUMN `permanently_out_of_stock`
        ');
    }

    public function getTabs()
    {
        return [
            [
                'class_name' => 'ScriptamiConfigurationController',
                'visible' => true,
                'name' => 'Modifications produit',
                'parent_class_name' => 'CONFIGURE',
            ]
        ];
    }

    public function getContent()
    {
        $route = SymfonyContainer::getInstance()->get('router')->generate('admin_edits_configuration_form');
        Tools::redirectAdmin($route);
    }

    public function isUsingNewTranslationSystem()
    {
        return true;
    }  
 


    public function hookDisplayHome($params)
    {
        return $this->display(__FILE__, 'views/templates/hook/displayHome.tpl');
    }

    public function hookActionProductGridDefinitionModifier(array $params)
    {
        $configuration = new PrestaShop\PrestaShop\Adapter\Configuration();
        if ($configuration->get('ADMIN_EDITS_ADD_SUPPLIER_COLUMN_IN_PRODUCTS_VIEW')) {
            /** @var PrestaShop\PrestaShop\Core\Grid\Definition\GridDefinition $definition */
            $definition = $params['definition'];

            $columns = $definition->getColumns();
            $columns->addAfter(
                'name',
                (new DataColumn('supplier_name'))
                    ->setName($this->trans('Supplier', [], 'Modules.Sc_Admin_Edits.Admin'))
                    ->setOptions([
                        'field' => 'supplier_name',
                    ])
            );
        }
    }

    public function hookActionProductGridQueryBuilderModifier(array $params)
    {
        $configuration = new PrestaShop\PrestaShop\Adapter\Configuration();
        if ($configuration->get('ADMIN_EDITS_ADD_SUPPLIER_COLUMN_IN_PRODUCTS_VIEW')) {
            /** @var Doctrine\DBAL\Query\QueryBuilder $searchQueryBuilder */
            $searchQueryBuilder = $params['search_query_builder'];
            $searchQueryBuilder->addSelect('sup.name AS supplier_name')
            ->leftJoin(
                'p',
                _DB_PREFIX_ . 'product_supplier',
                'psup',
                'psup.id_product = p.id_product AND p.id_supplier = psup.id_supplier'
            )
            ->leftJoin(
                'psup',
                _DB_PREFIX_ . 'supplier',
                'sup',
                'sup.id_supplier = psup.id_supplier'
            );
        }
    }

    public function hookDisplayAdminProductsCombinationBottom($params)
    {
        $this->context->smarty->assign(array(
            'permanently_out_of_stock' => $params['fields_value']['permanently_out_of_stock']
        ));
        var_dump($params['fields_value']['permanently_out_of_stock']);
        return $this->display(__FILE__, 'views/templates/admin/products/combinations/permanently_out_of_stock.tpl');
    }

    public function hookActionAdminProductsCombinationFormModifier($params)
    {
        $params['fields_value']['permanently_out_of_stock'] = (int)Db::getInstance()->getValue('
            SELECT `permanently_out_of_stock`
            FROM `'._DB_PREFIX_.'product_attribute`
            WHERE `id_product_attribute` = '.(int)$params['id']
        );
    }

    public function hookActionProductAttributeUpdate($params)
    {
        $id_product_attribute = (int)$params['id_product_attribute'];
        $permanently_out_of_stock = (int)Tools::getValue('permanently_out_of_stock');

        Db::getInstance()->execute('
            UPDATE `'._DB_PREFIX_.'product_attribute`
            SET `permanently_out_of_stock` = '.$permanently_out_of_stock.'
            WHERE `id_product_attribute` = '.$id_product_attribute
        );
    }
}

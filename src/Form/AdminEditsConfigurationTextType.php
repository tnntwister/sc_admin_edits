<?php 
declare(strict_types=1);

namespace PrestaShop\Module\Sc_Admin_Edits\Form;

use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use PrestaShopBundle\Form\Admin\Type\SwitchType;
use Symfony\Component\Form\FormBuilderInterface;
use PrestaShop\PrestaShop\Adapter\Configuration;

class AdminEditsConfigurationTextType extends TranslatorAwareType
{
    private $legacyConfiguration;

    public function setLegacyConfiguration(Configuration $legacyConfiguration)
    {
        $this->legacyConfiguration = $legacyConfiguration;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $value = $this->legacyConfiguration->get('ADMIN_EDITS_ADD_SUPPLIER_COLUMN_IN_PRODUCTS_VIEW');
    

        $builder
            /*
            ->add('config_text', TextType::class, [
                'label' => $this->trans('Configuration text', 'Modules.Sc_Admin_Edits.Admin'),
                'help' => $this->trans('Maximum 32 characters', 'Modules.Sc_Admin_Edits.Admin'),
            ]) */
            ->add('add_supplier_column_in_products_view', SwitchType::class, [
                // Utilisez des choix personnalisés avec ON/OFF
                'label' => $this->trans('Add supplier column in products view', 'Modules.Sc_Admin_Edits.Admin'),
                'data' => (bool)$value, 
                'choices' => [
                    $this->trans('No', 'Modules.Sc_Admin_Edits.Admin') => false,
                    $this->trans('Yes', 'Modules.Sc_Admin_Edits.Admin') => true,
                ]
                ]);

    }
}

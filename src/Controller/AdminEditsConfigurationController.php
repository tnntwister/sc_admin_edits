<?php 
declare(strict_types=1);

namespace PrestaShop\Module\Sc_Admin_Edits\Controller;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PrestaShopBundle\Entity\Tab;
// use PrestaShop\PrestaShop\Core\Configuration\Configuration;

class AdminEditsConfigurationController extends FrameworkBundleAdminController
{
    public function index(Request $request): Response
    {

        /*$this->setTitle($this->trans('My Custom Title', [], 'Modules.MyModule.Admin'));
        
        $this->setBreadcrumbs([
            'default' => [
                1 => [
                    'url' => $this->context->link->getAdminLink('AdminDashboard'),
                    'title' => $this->trans('Dashboard', [], 'Admin.Global'),
                ],
                2 => [
                    'url' => $this->context->link->getAdminLink('AdminModules'),
                    'title' => $this->trans('Modules', [], 'Admin.Global'),
                ],
                3 => [
                    'url' => $this->context->link->getAdminLink('AdminModules', true, ['configure' => $this->module->name]),
                    'title' => $this->module->displayName,
                ],
                4 => [
                    'url' => '',
                    'title' => $this->trans('My Custom Page', [], 'Modules.MyModule.Admin'),
                ],
            ],
        ]);*/

        $textFormDataHandler = $this->get('prestashop.module.sc_admin_edits.form.admin_edits_configuration_text_form_data_handler');

        $textForm = $textFormDataHandler->getForm();
        $textForm->handleRequest($request);

        if ($textForm->isSubmitted() && $textForm->isValid()) {
            /** You can return array of errors in form handler and they can be displayed to user with flashErrors */
            $errors = $textFormDataHandler->save($textForm->getData());
            if (empty($errors)) {
                $this->addFlash('success', $this->trans('Successful update.', 'Admin.Notifications.Success'));

                return $this->redirectToRoute('admin_edits_configuration_form');
            }

            $this->flashErrors($errors);
        }

        return $this->render('@Modules/sc_admin_edits/views/templates/admin/sc_admin_edits.html.twig', [
            'adminEditsConfigurationTextForm' => $textForm->createView(),
        ]);
    }

}
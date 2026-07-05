<?php

namespace Joomla\Component\Advportfolio\Administrator\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;

class ProjectController extends FormController
{
    public function save($key = null, $urlVar = null)
    {
        Factory::getApplication()->checkToken();

        $model = $this->getModel();
        $data = $this->input->post->get('jform', [], 'array');

        if (!$model->save($data)) {
            $this->setRedirect(
                Route::_('index.php?option=' . $this->option . '&view=' . $this->view_item, false)
            );
            return false;
        }

        $this->setRedirect(
            Route::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false)
        );

        return true;
    }

    public function cancel($key = null)
    {
        $app = Factory::getApplication();

        $app->redirect(
            Route::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false)
        );
    }
}
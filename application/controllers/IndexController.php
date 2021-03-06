<?php

/**
 * RAMP: Records and Activity Management Program
 *
 * LICENSE
 *
 * This source file is subject to the BSD-2-Clause license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.cs.kzoo.edu/ramp/LICENSE.txt
 *
 * @category   Ramp
 * @package    Ramp_Controller
 * @copyright  Copyright (c) 2012 Alyce Brady (http://www.cs.kzoo.edu/~abrady)
 * @license    http://www.cs.kzoo.edu/ramp/LICENSE.txt   Simplified BSD License
 * @version    $Id: IndexController.php 1 2012-07-12 alyce $
 *
 */

class IndexController extends Zend_Controller_Action
{
    const SETTING_NAME = 'setting';     // TODO: should use one in TableController!
    const AL_NAME = 'activity';     // TODO: should use one in ActivityController!

    public function init()
    {
        /* Initialize action controller here */

        /* Doesn't work!!!
        // Make sure that TableController has been loaded, so that this 
        // controller can use its constants for inter-controller 
        // communication.
        if ( ! class_exists('TableController') )
        {
            require_once 'Zend/Loader.php';
            Zend_Loader::loadClass('TableController');
        }
         */
    }

    public function indexAction()
    {
        /*
        if ( null === Zend_Auth::getInstance()->getIdentity() )
        {
            $this->_helper->redirector('login', 'auth');
        }
         */

        // $this->_forward('choose-table');
        $this->_forward('choose-activity-list');
    }

    public function chooseTableAction()
    {
        // Instantiate the form that asks the user which table setting to 
        // retrieve.
        $form = new Application_Form_TableChoice();
        $form->submit->setLabel('Retrieve');

        // Specify the view to render.
        $this->view->form = $form;

        if ($this->getRequest()->isPost())
        {
            // Process the filled-out form that has been posted:
            // if the changes are valid, view the appropriate table.
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData))
            {
                $settingName = $form->getValue('settingName');
                $settingName = urlencode($settingName);
                $params = array(self::SETTING_NAME => $settingName);

                $this->_helper->redirector('index', 'table', null, $params);
            }
            else
            {
                $form->populate($formData);
            }
        }
    }

    public function chooseActivityListAction()
    {
        // Instantiate the form that asks the user which activity list to 
        // retrieve.
        $form = new Application_Form_ActivityChoice();
        $form->submit->setLabel('Retrieve');

        // Specify the view to render.
        $this->view->form = $form;

        if ($this->getRequest()->isPost())
        {
            // Process the filled-out form that has been posted:
            // if the changes are valid, view the appropriate activity list.
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData))
            {
                $activityListName = $form->getValue('activityName');
                $activityListName = urlencode($activityListName);
                $params = array(self::AL_NAME => $activityListName);

                $this->_helper->redirector('index', 'activity', null, $params);
            }
            else
            {
                $form->populate($formData);
            }
        }
    }

    /**
     * Provides the main menu.
     * From Zend Framework in Action by Allen, Lo, and Brown,
     *      2009, p. 74.
     */ 
    public function menuAction()
    {
        // TODO: Convert this to use Zend_Navigation

        // Assigns the menu to the view & changes the response placeholder.
        $this->view->menu = $this->_readMenu();
        $this->_helper->viewRenderer->setResponseSegment('menu');
    }

    /**
     * Reads in the menu.
     *
     * @return string   filename
     *
     */
    protected function _readMenu()
    {
        // Get the menu filename from Zend_Registry.
        $menuFilename = 
            Zend_Registry::isRegistered('rampMenuFilename') ?
                Zend_Registry::get('rampMenuFilename') :
                null;

        $menu =  new Zend_Config_Ini($menuFilename);
        return $menu;
    }

// MIGHT WANT SOMETHING LIKE THIS TO GET FIRST ACTIVITY; default to index.act?

    /**
     * Gets a table viewing sequence for the given sequence or table 
     * setting name.
     *
     * @param string $name    name of the sequence
     * @return Application_Model_TableViewSequence
     *
     */
    protected function _getViewingSequence($name)
    {
        // Get the sequence.
        $allSequences = Zend_Registry::get('rampTableViewingSequences');
        if ( isset($allSequences[$name]) )
            { $sequence = $allSequences[$name]; }
        else
        {
            // Sequence not in registry; construct and register it.
            $sequence = new Application_Model_TableViewSequence($name);
            $allSequences[$name] = $sequence;
            Zend_Registry::set('rampSetTables', $allSequences);
        }

        // Return the sequence.
        return $sequence;
    }


}












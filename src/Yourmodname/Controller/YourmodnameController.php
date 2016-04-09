<?php
/**
 * BB's Zend Framework 2 Components
 *
 * CRUD Module Template
 *
 * @package     [MyApplication]
 * @subpackage  BB's Zend Framework 2 Components
 * @subpackage  CRUD Module Template
 * @author      Björn Bartels <coding@bjoernbartels.earth>
 * @link        https://gitlab.bjoernbartels.earth/groups/zf2
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright   copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */

namespace Yourmodname\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Application\Controller\BaseActionController;
use Zend\View\Model\ViewModel;
use Yourmodname\Model\Yourmodname;
use Yourmodname\Form\YourmodnameForm;
use Zend\Form\FormInterface;

/**
 * YourmodnameController
 *
 * @author
 *
 * @version
 *
 */
class YourmodnameController extends BaseActionController {
    
    protected $yourmodnameTable;
    
    /**
     * The default action - show the home page
     */
    public function indexAction() 
    {
        $tmplVars = array_merge( 
            $this->params()->fromRoute(), 
            $this->params()->fromPost(),
            array()
        );
        $oUser = $this->getUser();
        $mData = array();
        if ( $oUser ) {
            $sAclRole = $oUser->getAclrole();
            $oDataService = $this->getYourmodnameTable();
            if ( $sAclRole != 'admin' ) {
                $oDataService->setUserid($oUser->getId());
                $mData = $oDataService->fetchAll();
            }
            $mData = $oDataService->fetchAll();
        }
    
        $tmplVars = array_merge( 
            $tmplVars,
            array(
                'user'        => $oUser,
                'yourmodnamedata'    => $this->getYourmodnameTable()->fetchAll(),
            )
        );
        return new ViewModel($tmplVars);
    }
    
    public function addAction()
    {
        $tmplVars = array_merge( 
            $this->params()->fromRoute(), 
            $this->params()->fromPost(),
            array(
                'user' => $this->getUser(),
            )
        );
        
        $form = new YourmodnameForm();
        $form->get('submit')->setValue('Daten anlegen');

        $request = $this->getRequest();
        $yourmodname = new Yourmodname();
        if ($request->isPost()) {
            $form->setInputFilter($yourmodname->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $yourmodname->exchangeArray($form->getData());
                $this->getYourmodnameTable()->saveItem($yourmodname);
                // Redirect to list of yourmodname
                $this->flashMessenger()->addSuccessMessage("Daten wurden angelegt.");
                return $this->redirect()->toRoute('yourmodname/yourmodnameedit', array('action' => 'index'));
            }
            $tmplVars["yourmodname"] = $yourmodname;
        }
        $tmplVars["form"] = $form;
        
        return new ViewModel($tmplVars);
    }

    public function editAction()
    {
        $tmplVars = array_merge( 
            $this->params()->fromRoute(), 
            $this->params()->fromPost(),
            array(
                'user' => $this->getUser(),
            )
        );
        
        $id = (int) $this->params()->fromRoute('yourmodname_id', 0);
        if (!$id) {
            $this->flashMessenger()->addWarningMessage("Fehlende Parameter");
            return $this->redirect()->toRoute('yourmodname/yourmodnameedit', array(
                'action' => 'add'
            ));
        }
        $yourmodname = $this->getYourmodnameTable()->getItem($id);

        $form  = new YourmodnameForm();
        $form->bind($yourmodname);
        $form->get('submit')->setValue('Daten speichern');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($yourmodname->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $yourmodname->exchangeArray($request->getPost()->toArray());
                $this->getYourmodnameTable()->saveItem($yourmodname);

                // Redirect to list of yourmodname
                $this->flashMessenger()->addSuccessMessage("Daten wurden gespeichert.");
                return $this->redirect()->toRoute('yourmodname/yourmodnameedit', array('action' => 'index'));
            }
        } else {
               $form->bind($yourmodname); //->getArrayCopy());
        }
        $tmplVars["yourmodname_id"] = $id;
        $tmplVars["form"] = $form;
        return new ViewModel($tmplVars);
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('yourmodname_id', 0);
        if (!$id) {
            $this->flashMessenger()->addWarningMessage("Fehlende Parameter");
            return $this->redirect()->toRoute('yourmodname/yourmodnameedit', array('action' => 'index'));
        }

        $tmplVars = array_merge( 
            $this->params()->fromRoute(), 
            $this->params()->fromPost(),
            array(
                'user' => $this->getUser(),
            )
        );
        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', '');

            if (!empty($del)) {
                $id = (int) $request->getPost('id');
                $this->getYourmodnameTable()->deleteItem($id);
                $this->flashMessenger()->addSuccessMessage("Daten wurden entfernt.");
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('yourmodname/yourmodnameedit', array('action' => 'index'));
        }

        $tmplVars["yourmodname_id"] = $id;
        $tmplVars["yourmodname"] = $this->getYourmodnameTable()->getItem($id);
        return new ViewModel($tmplVars);
    }

    public function getYourmodnameTable()
    {
        if (!$this->yourmodnameTable) {
            $sm = $this->getServiceLocator();
            $this->yourmodnameTable = $sm->get('YourmodnameModelYourmodnameTable');
        }
        return $this->yourmodnameTable;
    }

}
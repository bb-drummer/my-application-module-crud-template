<?php
/**
 * BB's Zend Framework 2 Components
 * 
 * CRUD Module Template
 *
 * @package		[MyApplication]
 * @package		BB's Zend Framework 2 Components
 * @package		CRUD Module Template
 * @author		Björn Bartels <development@bjoernbartels.earth>
 * @link		https://gitlab.bjoernbartels.earth/groups/zf2
 * @license		http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright	copyright (c) 2016 Björn Bartels <development@bjoernbartels.earth>
 */

namespace Yourmodname\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
    	return $this->redirect()->toRoute('yourmodname/yourmodnameedit', array('action' => 'index'));
    }
}

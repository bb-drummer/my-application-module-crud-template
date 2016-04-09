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

namespace Yourmodname\Form;

use Zend\Form\Form;
use Yourmodname\Module;

class YourmodnameForm extends Form
{
    public function __construct($name = null)
    {
        
        // we want to ignore the name passed
        parent::__construct('yourmodname');
        $oModule = new Module();
        $cfg = $oModule->getConfig();
        
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'yourmodname_id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        
        $this->add(array(
            'name' => 'col1',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Spalte 1',
            ),
        ));
        $this->add(array(
            'name' => 'extracolumn',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Zusatz',
            ),
        ));
        
        $this->add(array(
            'name' => 'col2',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Spalte 2',
            ),
        ));
        $this->add(array(
            'name' => 'col3',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Spalte 3',
            ),
        ));
        
        // ...define more form fields...
        
        $this->add(array(
            'name' => 'userid',
            'attributes' => array(
                'type'  => 'hidden',
            ),
            'options' => array(
                'label' => 'Team',
            ),
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'absenden',
                'id' => 'submitbutton',
            ),
        ));
        
        $this->add(array(
            'name' => 'reset',
            'attributes' => array(
                'type'  => 'reset',
                'value' => 'zurücksetzen',
                'id' => 'resetbutton',
            ),
        ));
    }
}
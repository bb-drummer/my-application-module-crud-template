<?php
/**
 * BB's Zend Framework 2 Components
 * 
 * CRUD Module Template
 *
 * @package		[MyApplication]
 * @package		BB's Zend Framework 2 Components
 * @package		CRUD Module Template
 * @author		Björn Bartels [dragon-projects.net] <info@dragon-projects.net>
 * @link		http://gitlab.dragon-projects.de:81/groups/zf2
 * @license		http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright	copyright (c) 2016 Björn Bartels [dragon-projects.net] <info@dragon-projects.net>
 */

return array(
    'controllers' => array(
        'invokables' => array(
            'Yourmodname\Controller\Index' => 'Yourmodname\Controller\IndexController',
            'Yourmodname\Controller\Yourmodname' => 'Yourmodname\Controller\YourmodnameController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'yourmodname' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/yourmodname',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Yourmodname\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                    'yourmodnameedit' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '[/:action[/:yourmodname_id]]',
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'yourmodname_id'     => '[0-9]*',
                            ),
                            'defaults' => array(
                            	'controller' => 'Yourmodname\Controller\Yourmodname',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Yourmodname' => __DIR__ . '/../view',
        ),
    ),
);

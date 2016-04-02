<?php
/**
 * BB's Zend Framework 2 Components
 * 
 * CRUD Module Template
 *
 * @package        [MyApplication]
 * @package        BB's Zend Framework 2 Components
 * @package        CRUD Module Template
 * @author        Björn Bartels <development@bjoernbartels.earth>
 * @link        https://gitlab.bjoernbartels.earth/groups/zf2
 * @license        http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright    copyright (c) 2016 Björn Bartels <development@bjoernbartels.earth>
 */

namespace Yourmodname;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceManager;

use Yourmodname\Model\Yourmodname;
use Yourmodname\Model\YourmodnameTable;

class Module implements AutoloaderProviderInterface, ServiceLocatorAwareInterface
{
    
    protected $yourmodnameTable;
    
    /** @var $serviceLocator \Zend\Di\ServiceLocator */
    protected static $serviceLocator;

    /** @var $serviceManager \Zend\ServiceManager\ServiceManager */
    protected static $serviceManager;
    
    public function init(ModuleManager $mm)
    {
        $mm->getEventManager()->getSharedManager()->attach(__NAMESPACE__, 'dispatch', function($e) {
            $oController = $e->getTarget();
            $sAccept = $oController->getRequest()->getHeaders()->get('Accept')->toString();
            if ( $oController->getRequest()->isXmlHttpRequest() ) {
                if ( strpos($sAccept, 'text/html') !== false ) {
                    $oController->layout('layout/ajax');
                } else {
                    $oController->layout('layout/json');
                }
            } else {
                $oController->layout('layout/layout');
            }
        });

    }    
    
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
            // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $this->setServiceManager($e->getApplication()->getServiceManager());

        $application = $e->getApplication();
        /** @var $serviceManager \Zend\ServiceManager\ServiceManager */
        $serviceManager = $application->getServiceManager();

        // set (form) validator locale
        $translator = $serviceManager->get('translator');
        \Zend\Validator\AbstractValidator::setDefaultTranslator($translator, 'default');
        
        // override or add a view helper
        // /** @var $pm \Zend\View\Helper\Navigation\PluginManager */
        // $pm = $serviceManager->get('ViewHelperManager')->get('Navigation')->getPluginManager();
        // $pm->setInvokableClass('menu', '\Application\View\Helper\Navigation\Menu');
        
    }

    public function getServiceConfig()
    {
        return array(
                'factories' => array(
                        'YourmodnameModelYourmodnameTable' =>  function($sm) {
                            $tableGateway = $sm->get('YourmodnameTableGateway');
                            $table = new YourmodnameTable($tableGateway);
                            return $table;
                        },
                        'YourmodnameTableGateway' => function ($sm) {
                            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                            $resultSetPrototype = new ResultSet();
                            $resultSetPrototype->setArrayObjectPrototype(new Yourmodname());
                            return new TableGateway('yourmodname', $dbAdapter, null, $resultSetPrototype);
                        },
                ),
        );
    }

    /**
     * Set serviceManager instance
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return \Admin\Module
     */
    public function setServiceManager($serviceManager)
    {
        self::$serviceManager = $serviceManager;
        return $this;
    }

    /**
     * Retrieve serviceManager instance
     *
     * @return \Zend\ServiceManager\ServiceManager
     */
    public function getServiceManager()
    {
        if (!self::$serviceManager) {
            
            self::$serviceManager = new \Zend\ServiceManager\ServiceManager();
        }
        return self::$serviceManager;
    }
    
    /**
     * Set serviceManager instance
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return void
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        self::$serviceLocator = $serviceLocator;
        return $this;
    }

    /**
     * Retrieve serviceManager instance
     *
     * @return \Zend\Di\ServiceLocator
     */
    public function getServiceLocator()
    {
        if (!self::$serviceLocator) {
            self::$serviceLocator = new \Zend\Di\ServiceLocator();
            //$this->serviceLocator = new \Zend\Di\ServiceLocator();
        }
        return self::$serviceLocator;
    }
    
    
}

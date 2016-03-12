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
 * @link		https://gitlab.bjoernbartels.earth/groups/zf2
 * @license		http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright	copyright (c) 2016 Björn Bartels [dragon-projects.net] <info@dragon-projects.net>
 */

namespace Yourmodname\Model;

use Zend\Db\TableGateway\TableGateway;
use Yourmodname\Model\Yourmodname;
use Zend\Db\Sql\Select;
use Admin\Entity\User;

// service locator
use Zend\ServiceManager\ServiceLocatorInterface;


class YourmodnameTable
{
	protected $userid = 0;
	
    protected $tableGateway;
    protected $serviceLocator;
    
    protected $defaultOrder = 'col1, extracolumn, col2 ASC';

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
    	$userid = $this->getUserid();
		if ($userid == 0) {
	    	$resultSet = $this->tableGateway->select(function (Select $select) {
				$select->order($this->getDefaultOrder());
			});
		} else {
		    $resultSet = $this->tableGateway->select(function (Select $select) {
				$select->where(array(
					'userid' => $this->getUserid(),
				));
				$select->order($this->getDefaultOrder());
			});
		}
        return $resultSet;
    }

    public function getItem($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('yourmodname_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveItem(Yourmodname $yourmodname)
    {
        $data = array(
            'col1'			=> $yourmodname->col1,
            'extracolumn'   => $yourmodname->extracolumn,

        	'col2'		    => $yourmodname->col2,
            'col3'			=> $yourmodname->col3,

            'userid'		=> $yourmodname->userid,
        );

        $id = (int)$yourmodname->yourmodname_id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getItem($id)) {
                $this->tableGateway->update($data, array('yourmodname_id' => $id));
            } else {
                throw new \Exception('Team id does not exist');
            }
        }
    }

    public function deleteItem($id)
    {
        $this->tableGateway->delete(array('yourmodname_id' => $id));
    }
    
	/**
	 * set a user-id
	 * @param INT $userid
	 */
	public function setUserid($userid) {
		$this->userid = $userid;
	}
    
    /**
	 * @return the $userid
	 */
	public function getUserid() {
		return $this->userid;
	}

	/**
	 * @return ARRAY suitable for Zend/Form/Select options
	 */
	public function getSelectOptions() {
		$yourmodname = $this->fetchAll();
		$aYourmodname = array();
		foreach ($yourmodname as $item) {
			$aYourmodname[$item->yourmodname_id] = $item->col1 . (!empty($item->extracolumn) ? ", " . $item->extracolumn : "");
		}
		return $aYourmodname;
	}

	/**
	 * Set serviceManager instance
	 *
	 * @param  ServiceLocatorInterface $serviceLocator
	 * @return void
	 */
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
	{
		$this->serviceLocator = $serviceLocator;
		return $this;
	}
	
	/**
	 * Retrieve serviceManager instance
	 *
	 * @return ServiceLocatorInterface
	 */
	public function getServiceLocator()
	{
		if (!$this->serviceLocator) {
			$this->serviceLocator = new \Zend\Di\ServiceLocator();
		}
		return $this->serviceLocator;
	}
	
	/**
	 * @return the $defaultOrder
	 */
	public function getDefaultOrder() {
		return $this->defaultOrder;
	}
	
	/**
	 * @param string $defaultOrder
	 */
	public function setDefaultOrder($defaultOrder) {
		$this->defaultOrder = $defaultOrder;
	}
	
}
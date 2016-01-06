<?php
namespace Controller;

/*
 * This file is part of the HMS project.
 *
 * (c) CoderDojo Polska Foundation
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Arbor\Core\Controller;
use Arbor\Provider\Response;
use Common\ActionColumnFormatter;
use Common\BasicDataManager;
use Common\BasicFormFormatter;
use Common\BasicGridFormatter;
use Exception\OrderWrongLocationException;
use Arbor\Component\Form\SelectField;
use Arbor\Exception\OrderNotFetchedException;
use Common\LogSuccessColumnFormatter;
use Arbor\Component\Grid\Column;

/**
 * Class Log
 *
 * @package Controller
 * @author Slawomir Nowak (s.nowak@coderdojo.org.pl)
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class Log extends Controller
{
	/**
	 * Preparing data for index view
	 *
	 * @return array
	 */
	public function index()
	{
		$grid = $this->createGrid();
		$grid->render();
		return compact('grid');
	}

	/**
	 * Creates grid view
	 *
	 * @return mixed
	 * @throws \Arbor\Exception\ServiceNotFoundException
	 */
	private function createGrid()
	{
		$builder = $this->getService('grid')->create($this->getRequest());
		$builder->setFormatter(new BasicGridFormatter('log',false));//prefix
		$builder->setDataManager(new BasicDataManager(
			$this->getDoctrine()->getEntityManager()
			, 'Entity\Log'
		));

		$builder->setLimit(10);
		$query = $this->getRequest()->getQuery();
		if (!isset($query['page'])) {
			$query['page'] = 1;
		}
		$builder->setPage($query['page']);

		$builder->addColumn(new Column('id','#'));
		$builder->addColumn(new Column('action','Action'));
		$builder->addColumn(new Column('user','User'));
		$builder->addColumn(new Column('createdAt','Date'));
		$builder->addColumn(new Column(array('isSuccess','failMessage'),'Success',new LogSuccessColumnFormatter(),'failMessage'));
		$builder->addColumn(new Column('countModifiedEntities','Modified entities'));

		$builder->addColumn(new Column('id','Action', new ActionColumnFormatter('log', array('show')),array()));
		return $builder;
	}

	/**
	 * Shows log details
	 *
	 * @param \Entity\Order $entity
	 * @return array
	 */
	public function show($entity)
	{
		$em=$this->getDoctrine()->getEntityManager();
		$devices=$this->devices($entity);
		$locations=$this->locations($entity);
		$users=$this->users($entity);
		$orders=$this->orders($entity);
		$log=compact('entity');
		return array_merge($log,$devices,$locations,$users,$orders);

	}

	private function devices($entity){
		$editDevices=$this->executeQuery('select 
		dlo.id
		,dto.name as type
		,dtc.name as _type
		,dso.name state
		,dsc.name _state
		,llo.name as location
		,llc.name as _location
		,dlo.name
		,dlc.name as _name
		,dlo.photo
		,dlc.photo as _photo
		,dlo.serial_number
		,dlc.serial_number as _serial_number
		,dlo.warranty_expiration_date
		,dlc.warranty_expiration_date as _warranty_expiration_date
		,dlo.price
		,dlc.price as _price
		,dlo.note
		,dlc.note as _note 
		from device_logs as dlo
		inner join device_logs as dlc on(dlo.id=dlc.id and dlo.log_right_id=dlc.log_left_id)
		left join device_types as dto on(dlo.type_id=dto.id)
		left join device_types as dtc on(dlc.type_id=dtc.id)
		left join device_states as dso on(dlo.state_id=dso.id)
		left join device_states as dsc on(dlc.state_id=dsc.id)
		left join location_logs as llo on(dlo.location_id=llo.id and llo.log_left_id<=dlo.log_left_id and (llo.log_right_id is null or llo.log_right_id>dlo.log_left_id))
		left join location_logs as llc on(dlc.location_id=llc.id and llc.log_left_id<=dlc.log_left_id and (llc.log_right_id is null or llc.log_right_id>dlc.log_left_id))
		where dlo.log_right_id=:id and dlo.removed is false',array('id'=>$entity->getId()));
		$edited=array();
		foreach($editDevices as $record){
			$edited[]=$record['id'];
		}

		$addDevices=$this->executeQuery('select 
		dl.id
		,dt.name as type
		,ds.name state
		,ll.name as location
		,dl.name
		,dl.photo
		,dl.serial_number
		,dl.warranty_expiration_date
		,dl.price
		,dl.note
		,dl.created_at
		from device_logs dl
		left join device_types dt on(dl.type_id=dt.id)
		left join device_states ds on(dl.state_id=ds.id)
		left join location_logs ll on(dl.location_id=ll.id and ll.log_left_id<=dl.log_left_id and (ll.log_right_id is null or ll.log_right_id>dl.log_left_id))
		where dl.log_left_id=:id '.(count($edited)>0?'and dl.id not in('.implode(',',$edited).')':''),array('id'=>$entity->getId()));

		$removeDevices=$this->executeQuery('select 
		dl.id
		,dt.name as type
		,ds.name state
		,ll.name as location
		,dl.name
		,dl.photo
		,dl.serial_number
		,dl.warranty_expiration_date
		,dl.price
		,dl.note
		,dl.created_at
		from device_logs dl
		left join device_types dt on(dl.type_id=dt.id)
		left join device_states ds on(dl.state_id=ds.id)
		left join location_logs ll on(dl.location_id=ll.id and ll.log_left_id<=dl.log_left_id and (ll.log_right_id is null or ll.log_right_id>dl.log_left_id))
		where dl.log_right_id=:id and dl.removed is true',array('id'=>$entity->getId()));

		return compact('addDevices','editDevices','removeDevices');

	}

	private function locations($entity){
		$editLocations=$this->executeQuery('select 
		llo.id
		,llo.name as name
		,llc.name as _name
		,llo.city as city
		,llc.city as _city
		,llo.street as street
		,llc.street as _street
		,llo.number as number
		,llc.number as _number
		,llo.apartment as apartment
		,llc.apartment as _apartment
		,llo.postal as postal
		,llc.postal as _postal
		,llo.phone as phone
		,llc.phone as _phone
		,llo.email as email
		,llc.email as _email
		from location_logs as llo
		inner join location_logs as llc on(llo.id=llc.id and llo.log_right_id=llc.log_left_id)
		where llo.log_right_id=:id and llo.removed is false',array('id'=>$entity->getId()));
		$edited=array();
		foreach($editLocations as $record){
			$edited[]=$record['id'];
		}

		$addLocations=$this->executeQuery('select 
		ll.id
		,ll.name as name
		,ll.city as city
		,ll.street as street
		,ll.number as number
		,ll.apartment as apartment
		,ll.postal as postal
		,ll.phone as phone
		,ll.email as email
		from location_logs as ll
		where ll.log_left_id=:id '.(count($edited)>0?'and ll.id not in('.implode(',',$edited).')':''),array('id'=>$entity->getId()));

		$removeLocations=$this->executeQuery('select 
		ll.id
		,ll.name as name
		,ll.city as city
		,ll.street as street
		,ll.number as number
		,ll.apartment as apartment
		,ll.postal as postal
		,ll.phone as phone
		,ll.email as email
		from location_logs as ll
		where ll.log_right_id=:id and ll.removed is true',array('id'=>$entity->getId()));

		return compact('addLocations','editLocations','removeLocations');

	}

	private function users($entity){
		$editUsers=$this->executeQuery('select 
		ulo.id
		,ulo.email as email
		,ulc.email as _email
		,ulo.first_name as first_name
		,ulc.first_name as _first_name
		,ulo.last_name as last_name
		,ulc.last_name as _last_name
		,llo.name as location
		,llc.name as _location
		from user_logs as ulo
		inner join user_logs as ulc on(ulo.id=ulc.id and ulo.log_right_id=ulc.log_left_id)
		left join location_logs as llo on(ulo.location_id=llo.id and llo.log_left_id<=ulo.log_left_id and (llo.log_right_id is null or llo.log_right_id>ulo.log_left_id))
		left join location_logs as llc on(ulc.location_id=llc.id and llc.log_left_id<=ulc.log_left_id and (llc.log_right_id is null or llc.log_right_id>ulc.log_left_id))
		where ulo.log_right_id=:id and ulo.removed is false',array('id'=>$entity->getId()));

		$edited=array();
		foreach($editUsers as $record){
			$edited[]=$record['id'];
		}

		$addUsers=$this->executeQuery('select 
		ul.id
		,ul.email as email
		,ul.first_name as first_name
		,ul.last_name as last_name
		,ll.name as location
		from user_logs as ul
		left join location_logs ll on(ul.location_id=ll.id and ll.log_left_id<=ul.log_left_id and (ll.log_right_id is null or ll.log_right_id>ul.log_left_id))
		where ul.log_left_id=:id '.(count($edited)>0?'and ul.id not in('.implode(',',$edited).')':''),array('id'=>$entity->getId()));

		$removeUsers=$this->executeQuery('select 
		ul.id
		,ul.email as email
		,ul.first_name as first_name
		,ul.last_name as last_name
		,ll.name as location
		from user_logs as ul
		left join location_logs ll on(ul.location_id=ll.id and ll.log_left_id<=ul.log_left_id and (ll.log_right_id is null or ll.log_right_id>ul.log_left_id))
		where ul.log_right_id=:id and ul.removed is true',array('id'=>$entity->getId()));

		return compact('addUsers','editUsers','removeUsers');

	}

	private function orders($entity){
		$editOrders=$this->executeQuery('select 
		olo.id
		,oolo.email as owner
		,oolo.email as _owner
		,dlo.name as device
		,dlc.name as _device
		,oso.name as state
		,osc.name as _state
		,plo.email as performer
		,plc.email as _performer
		,olo.fetched_at as fetched_at
		,olc.fetched_at as _fetched_at
		,olo.closed_at as closed_at
		,olc.closed_at as _closed_at
		from order_logs as olo
		inner join order_logs as olc on(olo.id=olc.id and olo.log_right_id=olc.log_left_id)
		left join user_logs oolo on(olo.owner_id=oolo.id and oolo.log_left_id<=olo.log_left_id and (oolo.log_right_id is null or oolo.log_right_id>olo.log_left_id))
		left join user_logs oolc on(olc.owner_id=oolc.id and oolc.log_left_id<=olc.log_left_id and (oolc.log_right_id is null or oolc.log_right_id>olc.log_left_id))
		left join device_logs dlo on(olo.device_id=dlo.id and dlo.log_left_id<=olo.log_left_id and (dlo.log_right_id is null or dlo.log_right_id>olo.log_left_id))
		left join device_logs dlc on(olc.device_id=dlc.id and dlc.log_left_id<=olc.log_left_id and (dlc.log_right_id is null or dlc.log_right_id>olc.log_left_id))
		left join user_logs plo on(olo.performer_id=plo.id and plo.log_left_id<=olo.log_left_id and (plo.log_right_id is null or plo.log_right_id>olo.log_left_id))
		left join user_logs plc on(olc.performer_id=plc.id and plc.log_left_id<=olc.log_left_id and (plc.log_right_id is null or plc.log_right_id>olc.log_left_id))
		left join order_states oso on(olo.state_id=oso.id)
		left join order_states osc on(olc.state_id=osc.id)
		where olo.log_right_id=:id and olo.removed is false',array('id'=>$entity->getId()));

		$edited=array();
		foreach($editOrders as $record){
			$edited[]=$record['id'];
		}

		$addOrders=$this->executeQuery('select 
		ol.id
		,ool.email as owner
		,dl.name as device
		,os.name as state
		,pl.email as performer
		,ol.fetched_at as fetched_at
		,ol.closed_at as closed_at
		from order_logs as ol
		left join user_logs ool on(ol.owner_id=ool.id and ool.log_left_id<=ol.log_left_id and (ool.log_right_id is null or ool.log_right_id>ol.log_left_id))
		left join device_logs dl on(ol.device_id=dl.id and dl.log_left_id<=ol.log_left_id and (dl.log_right_id is null or dl.log_right_id>ol.log_left_id))
		left join user_logs pl on(ol.performer_id=pl.id and pl.log_left_id<=ol.log_left_id and (pl.log_right_id is null or pl.log_right_id>ol.log_left_id))
		left join order_states os on(ol.state_id=os.id)
		where ol.log_left_id=:id '.(count($edited)>0?'and ol.id not in('.implode(',',$edited).')':''),array('id'=>$entity->getId()));

		$removeOrders=$this->executeQuery('select 
		ol.id
		,ool.email as owner
		,dl.name as device
		,os.name as state
		,pl.email as performer
		,ol.fetched_at as fetched_at
		,ol.closed_at as closed_at
		from order_logs as ol
		left join user_logs ool on(ol.owner_id=ool.id and ool.log_left_id<=ol.log_left_id and (ool.log_right_id is null or ool.log_right_id>ol.log_left_id))
		left join device_logs dl on(ol.device_id=dl.id and dl.log_left_id<=ol.log_left_id and (dl.log_right_id is null or dl.log_right_id>ol.log_left_id))
		left join user_logs pl on(ol.performer_id=pl.id and pl.log_left_id<=ol.log_left_id and (pl.log_right_id is null or pl.log_right_id>ol.log_left_id))
		left join order_states os on(ol.state_id=os.id)
		where ol.log_right_id=:id and ol.removed is true',array('id'=>$entity->getId()));

		return compact('addOrders','editOrders','removeOrders');

	}

}
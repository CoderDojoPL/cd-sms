<?php
/**
 * Created by PhpStorm.
 * User: DrafFter
 * Date: 2015-07-22
 * Time: 18:40
 */

namespace Exception;


use Arbor\Core\Exception;

/**
 * @package Exception
 * @author Slawomir Nowak (s.nowak@coderdojo.org.pl)
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class OrderWrongLocationException extends Exception
{

	/**
	 * OrderWrongLocationException constructor.
	 */
	public function __construct(){
		parent::__construct(1,'Order for device in yours location.');
	}
}
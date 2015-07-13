<?php

namespace Library\Google\Service;

use Library\Doctrine\EventHandler;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Arbor\Contener\ServiceConfig;
use Doctrine\DBAL\Event\Listeners\MysqlSessionInit;
use Arbor\Core\EventManager;

require_once '../library/google/engine/src/Google/autoload.php';

class Google
{

	private $addInfo;

	public function __construct(ServiceConfig $serviceConfig, EventManager $eventManager)
	{

		$client_id = $serviceConfig->get('clientId');
		$client_secret = $serviceConfig->get('clientSecret');
		$redirect_uri = $serviceConfig->get('redirectUri');
		$client = new \Google_Client();
		$client->setClientId($client_id);
		$client->setClientSecret($client_secret);
		$client->setRedirectUri($redirect_uri);
		$client->setScopes(array('https://www.googleapis.com/auth/userinfo.email','https://www.googleapis.com/auth/userinfo.profile'));
		$this->addInfo = new \Google_Service_Oauth2($client);
		$this->client = $client;
	}

	public function isAuthenticated()
	{
		return isset($_SESSION['access_token']);
	}

	public function getClient()
	{
		return $this->client;
	}

	/**
	 * @return \Google_Service_Oauth2
	 */
	public function getAddInfo()
	{
		return $this->addInfo;
	}

}
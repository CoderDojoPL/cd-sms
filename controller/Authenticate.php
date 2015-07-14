<?php

namespace Controller;

use Arbor\Core\Controller;
use Arbor\Exception\ValueNotFoundException;
use Arbor\Provider\Response;

/**
 * Authenticate user with Google OAuth2 API
 * Class Authenticate
 * @package Controller
 */
class Authenticate extends Controller
{

	public function index()
	{
		$name = $this->getUser();
		return compact('name');
	}

	public function login()
	{

		try {
			$error = $this->getRequest()->getSession()->get('api.error');
			$this->getRequest()->getSession()->remove('api.error');
			return compact('error');
		} catch (ValueNotFoundException $e) {

		}
	}

	public function loginRedirect()
	{
		$googleService = $this->getService('google');

		$response = new Response();
		$response->redirect($googleService->getClient()->createAuthUrl());
		return $response;
	}

	/**
	 * Callback method for OAuth login
	 * @return Response
	 * @throws \Arbor\Exception\ServiceNotFoundException
	 */
	public function loginOAuth2Callback()
	{
		$googleService = $this->getService('google');
		$client = $googleService->getClient();
		/* @var $client \Google_Client */
		$session = $this->getRequest()->getSession();
		$query = $this->getRequest()->getQuery();

		if (isset($query['code'])) {
			$client->authenticate($query['code']);
			$session->set('access.token', $client->getAccessToken());

			$tokenData = $client->verifyIdToken()->getAttributes();
			$userInfoData = $googleService->getAddInfo()->userinfo->get();
			/* @var $userInfoData \Google_Service_Oauth2_Userinfoplus */

			$email = $tokenData['payload']['email'];
			if (!preg_match('/^.*?@coderdojo.org.pl$/', $email)) {//FIXME domain to config
				$session->clear();
				$session->set('api.error', 'You have to login from "coderdojo.org.pl" domain.');
			} else {

				$user = $this->findOne('User', array('email' => $email));
				if (!$user) {
					$user = $this->createUser($tokenData['payload'], $userInfoData);
				}

				$session->set('user.id', $user->getId());

			}

		} else if (isset($query['error'])) {
			$this->getRequest()->getSession()->set('api.error', $query['error']);
		}

		$response = new Response();
		$response->redirect('/');
		return $response;

	}

	/**
	 * Logout method
	 * @return Response
	 * @throws \Arbor\Exception\ServiceNotFoundException
	 */
	public function logout()
	{
		$googleService = $this->getService('google');
		$client = $googleService->getClient();

		$client->revokeToken();
		$this->getRequest()->getSession()->clear();

		$response = new Response();
		$response->redirect('/');
		return $response;

	}

	/**
	 * Creates new user in database from Google response data
	 * @param $data
	 * @param \Google_Service_Oauth2_Userinfoplus $userData
	 * @return \Entity\User
	 */
	private function createUser($data, $userData)
	{
		$userEntity = new \Entity\User();
		$userEntity->setEmail($data['email']);
		$userEntity->setFirstName($userData['givenName']);
		$userEntity->setLastName($userData['familyName']);

		$this->persist($userEntity);
		$this->flush();

		return $userEntity;
	}


}
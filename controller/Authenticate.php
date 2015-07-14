<?php

namespace Controller;

use Arbor\Core\Controller;
use Arbor\Provider\Response;

class Authenticate extends Controller
{

	public function index()
	{
		$name = $this->getUser();
		return  compact('name');
	}

	public function login()
	{
	}

	public function loginRedirect()
	{
		$googleService = $this->getService('google');

		$response = new Response();
		$response->redirect($googleService->getClient()->createAuthUrl());
		return $response;
	}

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
			if (!preg_match('/^.*?@coderdojo.org.pl$/', $email)) {
				$session->clear();
			} else {

				$user = $this->findOne('User', array('email' => $email));
				if (!$user) {
					$user = $this->createUser($tokenData['payload'], $userInfoData);
				}

				$session->set('user.id', $user->getId());

			}

		}

		$response = new Response();
		$response->redirect('/');
		return $response;

	}

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
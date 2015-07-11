<?php

namespace Controller;
use Arbor\Core\Controller;
use Arbor\Provider\Response;

class Authenticate extends Controller{

	public function index(){
	}
	
	public function login(){
	}

	public function loginRedirect(){
		$googleService=$this->getService('google');

		$response=new Response();
		$response->redirect($googleService->getClient()->createAuthUrl());
		return $response;
	}

	public function loginOAuth2Callback(){
		$googleService=$this->getService('google');
		$client=$googleService->getClient();

		$session=$this->getRequest()->getSession();

		$query=$this->getRequest()->getQuery();
		if (isset($query['code'])) {
		  $client->authenticate($query['code']);
		  $session->set('access.token',$client->getAccessToken());

		  $tokenData = $client->verifyIdToken()->getAttributes();
		  $email=$tokenData['payload']['email'];
		  if(!preg_match('/^.*?@coderdojo.org.pl$/',$email)){
		  	$session->clear();
		  }
		  else{

		  	$user=$this->findOne('User',array('email'=>$email));
		  	if(!$user){		  		
		  		$user=$this->createUser($tokenData['payload']);
		  	}

		  	$session->set('user.id',$user->getId());

		  }

		}

		$response=new Response();
		$response->redirect('/');
		return $response;
	
	}

	public function logout(){
		$this->getRequest()->getSession()->clear();

		$response=new Response();
		$response->redirect('/');
		return $response;
		
	}

	private function createUser($data){
		$userEntity=new \Entity\User();
		$userEntity->setEmail($data['email']);
		$userEntity->setFirstName('TODO');
		$userEntity->setLastName('TODO');
	
		$this->persist($userEntity);
		$this->flush();

		return $userEntity;
	}



}
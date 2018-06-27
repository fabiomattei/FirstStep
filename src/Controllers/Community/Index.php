<?php

namespace Firststep\Controllers\Community;

use Firststep\Common\Controllers\Controller;

use Firststep\Templates\Blocks\Menus\PublicMenu;
use Firststep\Templates\Blocks\Login\LoginForm;
use Firststep\BusinessLogic\User\Daos\UserDao;
use Firststep\BusinessLogic\User\UseCases\UserCanLogIn;
use Firststep\Common\Router\Router;

/**
 * This class cares about the login process.
 * The get method cares about visualizing the interface, the post method cares about eventually allow 
 * the user to log in or not
 */
class Index extends Controller {
	
    function __construct() {
		$this->userDao = new UserDao();
		$this->userCanLogIn = new UserCanLogIn();
    }
	
	public function getRequest() {
		$error = '';
		
		if ( isset( $this->parameters[0] )  AND $this->parameters[0] != '' ) {
			$error = 'error';
		}
		
		$this->title            = $this->setup->getAppNameForPageTitle() . ' :: Access page';
		$this->menucontainer    = array( new PublicMenu( $this->setup->getAppNameForPageTitle(), 'login' ) );
		$this->centralcontainer = array( new LoginForm( $this->setup->getAppNameForPageTitle(), $error ) );
		$this->templateFile     = 'login';
	}
	
    public $post_validation_rules = array(
		'email'	   => 'max_len,255',
		'password' => 'max_len,255',
    );
    public $post_filter_rules = array(
		'email'    => 'trim',
		'password' => 'trim',
    );
	
	public function postRequest() {
		$this->userCanLogIn->setUserDao( $this->userDao );
		$this->userCanLogIn->setParameters( $this->parameters );
		$this->userCanLogIn->performAction();

		if ($this->userCanLogIn->getUserCanLogIn()) {
			$user = $this->userDao->getOneByFields( array( 'usr_email' => $this->parameters['email'] ) );
			$this->sessionWrapper->setSessionUserId( $user->usr_id );
			$this->sessionWrapper->setSessionUsername( $user->usr_name );
			$this->sessionWrapper->setSessionLoggedIn( true );
			$this->sessionWrapper->setSessionIp( $_SERVER['REMOTE_ADDR'] );
			$this->sessionWrapper->setSessionUserAgent( $_SERVER['HTTP_USER_AGENT'] );
			$this->sessionWrapper->setSessionLastLogin( time() );
			
	        // redirecting to assets list
			$this->redirectToPage( $this->router->make_url( Router::ROUTE_OFFICE_INBOX ) );
		} else {
	        // redirecting to assets list
			$this->redirectToPage( $this->router->make_url( Router::ROUTE_COMMUNITY_LOGIN ) );
		}
	}
	
}

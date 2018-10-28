<?php

namespace Firststep\Controllers\Admin\Group;

use Firststep\BusinessLogic\Group\Daos\UserGroupDao;
use Firststep\BusinessLogic\User\Daos\UserDao;
use Firststep\Common\Controllers\Controller;
use Firststep\Templates\Blocks\Menus\AdminMenu;
use Firststep\Templates\Blocks\Sidebars\AdminSidebar;
use Firststep\Common\Blocks\BaseForm;
use Firststep\Common\Router\Router;

class AdminGroupAddUser extends Controller {

    private $userDao;

    public function __construct() {
        $this->userDao = new UserDao;
        $this->userGroupDao = New UserGroupDao();
    }

    public $get_validation_rules = array( 'groupslug' => 'required|max_len,100' );
    public $get_filter_rules     = array( 'groupslug' => 'trim' );

    /**
     * @throws GeneralException
     *
     * $this->getParameters['id'] resource key index
     */
    public function getRequest() {
        $this->userDao->setDBH( $this->dbconnection->getDBH() );

        $this->title = $this->setup->getAppNameForPageTitle() . ' :: Add user to a group';

        $form = new BaseForm;
        $form->setTitle( 'Add user to a group');
        $form->addDropdownField('usr_id', 'Users:', $this->userDao->makeListForDropdown(), '', '6' );
        $form->addHiddenField('groupslug', $this->getParameters['groupslug'] );
        $form->addSubmitButton('save', 'Add');

        $this->menucontainer    = array( new AdminMenu( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_USER_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_USER_LIST, $this->router ) );
        $this->centralcontainer = array( $form );
    }

    public $post_validation_rules = array(
        'usr_id' => 'required|numeric',
        'groupslug' => 'required|alpha_numeric|max_len,100'
    );
    public $post_filter_rules     = array(
        'usr_id' => 'trim',
        'groupslug' => 'trim|sanitize_string'
    );

    /**
     * @throws GeneralException
     *
     * $this->postParameters['id'] resource key index
     */
    public function postRequest() {
        $this->userGroupDao->setDBH( $this->dbconnection->getDBH() );
        $this->userGroupDao->insert(
            array(
                'ug_groupslug' => $this->postParameters['groupslug'],
                'ug_userid' => $this->postParameters['usr_id']
            )
        );

        $this->redirectToSecondPreviousPage();
    }

    public function show_post_error_page() {
        $this->userDao->setDBH( $this->dbconnection->getDBH() );

        $this->messages->setError($this->readableErrors);

        $this->title = $this->setup->getAppNameForPageTitle() . ' :: Add user to a group';

        $form = new BaseForm;
        $form->setTitle( 'Add user to a group');
        $form->addDropdownField('usr_id', 'Users:', $this->userDao->makeListForDropdown(), '', '6' );
        $form->addHiddenField('groupslug', $this->getParameters['groupslug'] );
        $form->addSubmitButton('save', 'Add');

        $this->menucontainer    = array( new AdminMenu( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_USER_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_USER_LIST, $this->router ) );
        $this->centralcontainer = array( $form );
    }
}
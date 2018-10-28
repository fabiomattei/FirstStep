<?php

namespace Firststep\Controllers\Admin\Group;

use Firststep\BusinessLogic\Group\Daos\UserGroupDao;
use Firststep\Common\Controllers\Controller;
use Firststep\Templates\Blocks\Menus\AdminMenu;
use Firststep\Templates\Blocks\Sidebars\AdminSidebar;
use Firststep\Common\Blocks\BaseInfo;
use Firststep\Common\Blocks\StaticTable;
use Firststep\Common\Blocks\Button;
use Firststep\Common\Router\Router;

/**
 *
 */
class AdminGroupView extends Controller {

    function __construct() {
        $this->userGroupDao = new UserGroupDao;
    }

    public $get_validation_rules = array( 'res' => 'required|max_len,50' );
    public $get_filter_rules     = array( 'res' => 'trim' );

    /**
     * Overwrite parent showPage method in order to add the functionality of loading a json resource.
     */
    public function showPage() {
        $this->jsonloader->loadIndex();
        parent::showPage();
    }

    /**
     * @throws GeneralException
     *
     * $this->getParameters['res'] resource key index
     */
    public function getRequest() {
        $this->userGroupDao->setDBH( $this->dbconnection->getDBH() );
        $this->resource = $this->jsonloader->loadResource( $this->getParameters['res'] );

        $this->title = $this->setup->getAppNameForPageTitle() . ' :: Admin group view';

        $info = new BaseInfo;
        $info->setTitle( 'Group name: '.$this->resource->name );

        $users = $this->userGroupDao->getUsersByGroupSlug( $this->resource->name );

        $userTable = new StaticTable;
        $userTable->setTitle("Users");
        $userTable->addButton('Add', $this->router->make_url( Router::ROUTE_ADMIN_GROUP_ADD_USER, 'groupslug='.$this->resource->name ));
        $userTable->addTHead();
        $userTable->addRow();
        $userTable->addHeadLineColumn('Name');
        $userTable->addHeadLineColumn(''); // adding one more for actions
        $userTable->closeRow();
        $userTable->closeTHead();
        $userTable->addTBody();
        foreach ( $users as $res ) {
            $userTable->addRow();
            $userTable->addColumn($res->usr_name.' '.$res->usr_surname);
            $userTable->addUnfilteredColumn( Button::get($this->router->make_url( Router::ROUTE_ADMIN_GROUP_REMOVE_USER, 'res='.$this->resource->name.'&usrid='.$res->usr_id ), 'Remove', Button::COLOR_GRAY.' '.Button::SMALL ) );
            $userTable->closeRow();
        }
        $userTable->closeTBody();

        $resourcesTable = new StaticTable;
        $resourcesTable->setTitle("Resources this group has access to");
        $resourcesTable->addTHead();
        $resourcesTable->addRow();
        $resourcesTable->addHeadLineColumn('Name');
        $resourcesTable->addHeadLineColumn('Path');
        $resourcesTable->addHeadLineColumn('Type'); // adding one more for actions
        $resourcesTable->closeRow();
        $resourcesTable->closeTHead();
        $resourcesTable->addTBody();
        foreach ( $this->jsonloader->getResourcesIndex() as $reskey => $resvalue ) {
            $tmpres = $this->jsonloader->loadResource( $reskey );
            if ( isset($tmpres->allowedgroups) AND in_array( $this->resource->name, $tmpres->allowedgroups) ) {
                $resourcesTable->addRow();
                $resourcesTable->addColumn($reskey);
                $resourcesTable->addColumn($resvalue->path);
                $resourcesTable->addColumn($resvalue->type);
                $resourcesTable->closeRow();
            }
        }
        $resourcesTable->closeTBody();

        $this->menucontainer    = array( new AdminMenu( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_GROUP_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_GROUP_LIST, $this->router ) );
        $this->centralcontainer = array( $info );
        $this->secondcentralcontainer = array( $userTable );
        $this->thirdcentralcontainer = array( $resourcesTable );
    }

}
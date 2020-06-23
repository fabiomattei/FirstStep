<?php

namespace Fabiom\UglyDuckling\Controllers\Admin\Entity;

use Fabiom\UglyDuckling\Common\Controllers\Controller;
use Fabiom\UglyDuckling\Templates\Blocks\Menus\AdminMenu;
use Fabiom\UglyDuckling\Templates\Blocks\Sidebars\AdminSidebar;
use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLInfo;
use Fabiom\UglyDuckling\Common\Router\Router;
use Fabiom\UglyDuckling\Common\Database\QueryExecuter;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\QueryBuilder;

/**
 * 
 */
class EntityCreateTable extends Controller {
	
	function __construct() {
		$this->queryExecuter = new QueryExecuter;
		$this->queryBuilder = new QueryBuilder;
    }
	
    public $get_validation_rules = array( 'res' => 'required|max_len,50' );
    public $get_filter_rules     = array( 'res' => 'trim' );
	
    /**
     * Overwrite parent showPage method in order to add the functionality of loading a json resource.
     */
    public function showPage() {
		$this->applicationBuilder->getJsonloader()->loadIndex();
		parent::showPage(); 
    }
	
    /**
     * @throws GeneralException
     *
     * $this->getParameters['res'] resource key index
     */
	public function getRequest() {
		$this->queryExecuter->setDBH( $this->dbconnection->getDBH() );
		$this->resource = $this->applicationBuilder->getJsonloader()->loadResource( $this->getParameters['res'] );
		
		$this->title = $this->applicationBuilder->getSetup()->getAppNameForPageTitle() . ' :: Admin entity create';
		
		$info = new BaseHTMLInfo;
        $info->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
		$info->setTitle( 'Entity name: '.$this->resource->name );
		$info->addParagraph( 'Table name: '.$this->resource->entity->tablename, '' );

		$this->queryBuilder->setQueryStructure( $this->resource->entity );
		$this->queryExecuter->executeTableCreate( $this->queryBuilder->create() );
		$this->queryExecuter->executeTableCreate( $this->queryBuilder->primarykey() );
		$this->queryExecuter->executeTableCreate( $this->queryBuilder->autoincrement() );
			
		$info->addParagraph( 'Table created! ', '' );
		
		$this->menucontainer    = array( new AdminMenu( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), Router::ROUTE_ADMIN_ENTITY_LIST ) );
		$this->leftcontainer    = array( new AdminSidebar( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), Router::ROUTE_ADMIN_ENTITY_LIST, $this->routerContainer ) );
		$this->centralcontainer = array( $info );

        $this->templateFile = $this->applicationBuilder->getSetup()->getPrivateTemplateWithSidebarFileName();
	}

}

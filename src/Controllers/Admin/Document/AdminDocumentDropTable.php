<?php

namespace Fabiom\UglyDuckling\Controllers\Admin\Document;

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
class AdminDocumentDropTable extends Controller {
	
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
		$this->jsonloader->loadIndex();
		parent::showPage(); 
    }
	
    /**
     * @throws GeneralException
     *
     * $this->getParameters['res'] resource key index
     */
	public function getRequest() {
		$this->queryExecuter->setDBH( $this->dbconnection->getDBH() );
		$this->resource = $this->jsonloader->loadResource( $this->getParameters['res'] );
		
		$this->title = $this->setup->getAppNameForPageTitle() . ' :: Admin document drop table';
		
		$info = new BaseHTMLInfo;
        $info->setHtmlTemplateLoader( $this->htmlTemplateLoader );
		$info->setTitle( 'Document name: '.$this->resource->name );
		$info->addParagraph( 'Table name: '.$this->resource->name, '' );

		$this->queryExecuter->executeTableDrop( $this->queryBuilder->tableDrop( $this->resource->name ) );
			
		$info->addParagraph( 'Table Dropped ', '' );
		
		$this->menucontainer    = array( new AdminMenu( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_DOCUMENT_LIST ) );
		$this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_DOCUMENT_LIST, $this->router ) );
		$this->centralcontainer = array( $info );

        $this->templateFile = $this->setup->getPrivateTemplateWithSidebarFileName();
	}

}

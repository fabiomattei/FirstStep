<?php

namespace Firststep\Controllers\Office\Manager;

use Firststep\Common\Controllers\ManagerEntityController;
use Firststep\Common\Json\Builders\PanelBuilder;
use Firststep\Common\Router\Router;
use Firststep\Common\Json\Builders\MenuBuilder;

/**
 * User: Fabio Mattei
 * Date: 16/08/2018
 * Time: 12:02
 */
class EntityTable extends ManagerEntityController {

    private $panelBuilder;
    private $menubuilder;

    function __construct() {
        $this->panelBuilder = new PanelBuilder;
		$this->menubuilder = new MenuBuilder;
    }
	
    /**
     * @throws GeneralException
     */
	public function getRequest() {
		$this->resource = $this->jsonloader->loadResource( $this->getParameters['res'] );
		
		$menuresource = $this->jsonloader->loadResource( $this->sessionWrapper->getSessionGroup() );
		$this->menubuilder->setMenuStructure( $menuresource );
		$this->menubuilder->setRouter( $this->router );

        $this->panelBuilder->setHtmlTemplateLoader( $this->htmlTemplateLoader );
        $this->panelBuilder->setDbconnection($this->dbconnection);
        $this->panelBuilder->setRouter($this->router);
        $this->panelBuilder->setJsonloader($this->jsonloader);
        $this->panelBuilder->setParameters($this->getParameters);
        $this->panelBuilder->setAction($this->router->make_url( Router::ROUTE_OFFICE_ENTITY_DASHBOARD, 'res='.$this->getParameters['res'] ));

		$this->title = $this->setup->getAppNameForPageTitle() . ' :: Office table';
		
		$this->menucontainer    = array( $this->menubuilder->createMenu() );
		$this->leftcontainer    = array();
		$this->centralcontainer = array( $this->panelBuilder->getWidePanel($this->resource) );
	}

}

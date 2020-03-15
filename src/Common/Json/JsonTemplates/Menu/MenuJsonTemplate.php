<?php

/**
 * User: Fabio Mattei
 * Date: 29/09/18
 * Time: 11.54
 */

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates\Menu;

use Fabiom\UglyDuckling\Common\Json\JsonTemplates\JsonTemplate;
use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLMenu;
use stdClass;

class MenuJsonTemplate extends JsonTemplate {

    private $menuStructure;
    private $routerContainer;

    /**
     * @param mixed $infoStructure
     */
    public function setMenuStructure( $menuStructure ) {
        $this->menuStructure = $menuStructure;
    }

    public function createMenu() {
		$menu = new BaseHTMLMenu;
        $menu->addBrand( $this->menuStructure->home->label, $this->menuStructure->home->action );
        $menu->addButtonToggler();
        foreach ($this->menuStructure->menu as $menuitem) {
            if (isset($menuitem->submenu)) {
                $submenuItems = array();
                foreach ($menuitem->submenu as $item) {
                    $mi = new stdClass;
                    $mi->label = $item->label;
                    $mi->url = LinkBuilder::getURL( $this->routerContainer, $item->action, $item->resource );
                    $submenuItems[] = $mi;
                }
                $menu->addNavItemWithDropdown( $menuitem->label, 
                    LinkBuilder::getURL( $this->routerContainer, $menuitem->action, $menuitem->resource ), 
                    false, false, 
                    $submenuItems 
                );
            } else {
                $menu->addNavItem( $menuitem->label, 
                    LinkBuilder::getURL( $this->routerContainer, $menuitem->action, $menuitem->resource ), 
                    false, false 
                );
            }
        }

        return $menu;
    }

}

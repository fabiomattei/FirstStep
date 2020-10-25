<?php

namespace Fabiom\UglyDuckling\BusinessLogic\Ip\UseCases;

class ipIsNotBlocked {
	
	public function performAction( $remote_address, $ipDao ) {
		return $ipDao->checkIfIpIsBlocker( $remote_address );
	}
	
}
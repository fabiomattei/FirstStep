<?php

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates\Form;

use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLForm;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\JsonTemplate;

/**
 * User: Fabio Mattei
 * Date: 13/07/2018
 * Time: 12:00
 */
class FormJsonTemplate extends JsonTemplate {

    const blocktype = 'form';

    public function createForm() {
        $queryExecuter = $this->jsonTemplateFactoriesContainer->getQueryExecuter();
        $queryBuilder = $this->jsonTemplateFactoriesContainer->getQueryBuilder();
        $parameters = $this->jsonTemplateFactoriesContainer->getParameters();
        $dbconnection = $this->jsonTemplateFactoriesContainer->getDbconnection();
        $logger = $this->jsonTemplateFactoriesContainer->getLogger();
        $htmlTemplateLoader = $this->jsonTemplateFactoriesContainer->getHtmlTemplateLoader();
        $sessionWrapper = $this->jsonTemplateFactoriesContainer->getSessionWrapper();

        // If there are dummy data they take precedence in order to fill the form
        if ( isset($this->resource->get->dummydata) ) {
            $entity = $this->resource->get->dummydata;
        } else {
            // If there is a query I look for data to fill the form,
            // if there is not query I do not
            if ( isset($this->resource->get->query) AND isset($dbconnection) ) {
                $queryExecuter->setDBH( $dbconnection->getDBH() );
				$queryExecuter->setResourceName( $this->resource->name ?? 'undefined ');
                $queryExecuter->setQueryBuilder( $queryBuilder );
                $queryExecuter->setQueryStructure( $this->resource->get->query );
                $queryExecuter->setLogger( $logger );
                $queryExecuter->setSessionWrapper( $sessionWrapper );
                if (isset( $this->parameters ) ) $queryExecuter->setGetParameters( $this->parameters );

                $result = $queryExecuter->executeSql();
                $entity = $result->fetch();
            } else {
                $entity = new \stdClass();
            }
        }

		$formBlock = new BaseHTMLForm;
        $formBlock->setHtmlTemplateLoader( $htmlTemplateLoader );
		$formBlock->setTitle($this->resource->get->form->title ?? '');
        $formBlock->setAction( $this->action ?? '');
        $formBlock->setMethod( $this->resource->get->form->method ?? 'POST');
		$fieldRows = array();
		
		foreach ($this->resource->get->form->fields as $field) {
			if( !array_key_exists($field->row, $fieldRows) ) $fieldRows[$field->row] = array();
			$fieldRows[$field->row][] = $field;
		}
		
        $rowcounter = 1;
		foreach ($fieldRows as $row) {
			$formBlock->addRow();
			foreach ($row as $field) {
				$value = $this->getValue($field, $parameters, array(), $sessionWrapper, $entity);
                if (in_array( $field->type, array('textfield', 'number') )) {
                    $formBlock->addGenericField( $field, $value ?? '');
                }
                if ($field->type === 'dropdown') {
                    $options = array();
                    foreach ($field->options as $op) {
                        $options[$op->value] = $op->label;
                    }
                    $formBlock->addDropdownField($field->name, $field->label, $options, $value ?? '', $field->width);
                }
                if ($field->type === 'sqldropdown') {
                    if ( isset($field->query) AND isset($dbconnection) ) {
                        $queryExecuter->setDBH( $dbconnection->getDBH() );
                        $queryExecuter->setQueryBuilder( $queryBuilder );
                        $queryExecuter->setQueryStructure( $field->query );
                        $queryExecuter->setLogger( $logger );
                        $queryExecuter->setSessionWrapper( $sessionWrapper );
                        if (isset( $this->parameters ) ) $queryExecuter->setGetParameters( $this->parameters );

                        $result = $queryExecuter->executeSql();
                        $fieldOptions = $result->fetchAll();
                    } else {
                        $logger->write('ERROR <FormJsonTemplate> <sqldropdown> - Missing object query in json object', __FILE__, __LINE__);
                        $fieldOptions = array();
                    }

                    $options = array();
                    foreach ($fieldOptions as $op) {
                        if ( !isset($op->valuesqlfield) ) {
                            $logger->write('ERROR <FormJsonTemplate> <sqldropdown> - Missing parameter valuesqlfield in json object', __FILE__, __LINE__);
                        }
                        if ( !isset($op->labelsqlfield) ) {
                            $logger->write('ERROR <FormJsonTemplate> <sqldropdown> - Missing parameter labelsqlfield in json object', __FILE__, __LINE__);
                        }
                        $options[$op->valuesqlfield] = $op->labelsqlfield;
                    }
                    $formBlock->addDropdownField($field->name, $field->label, $options, $value ?? '', $field->width);
                }
				if ($field->type === 'textarea') {
                    $formBlock->addTextAreaField($field->name, $field->label, $value ?? '', $field->width);
                }
                if ($field->type === 'date') {
                    if (!isset($field->placeholder)) { $field->placeholder = date('Y-m-d'); }
                    $formBlock->addGenericField( $field, $value ?? '');
                }
                if ($field->type === 'hidden') {
                    $formBlock->addHiddenField($field->name, $value);
                }
                if ($field->type === 'file') {
                    $formBlock->addFileUploadField($field->name, $field->label, $field->width);
                }
                if ($field->type === 'submitbutton') {
                    $formBlock->addSubmitButton( $field->name, $field->constantparameter ?? '', $field->label ?? '', $field->width ?? '12' );
                }
			}
			$formBlock->closeRow('row '.$rowcounter);
            $rowcounter++;
		}
        return $formBlock;
    }

}

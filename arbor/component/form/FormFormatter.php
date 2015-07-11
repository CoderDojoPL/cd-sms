<?php

namespace Arbor\Component\Form;

use Arbor\Component\Form\FormField;
interface FormFormatter{

	/**
	 * Method generated html for field
	 * @param Arbor\Common\FormField $field - FormField object 
	 * @since 0.15.0
	 */
	public function renderField(FormField $field);

	/**
	 * Method generated html for form open html element
	 * @param array $tags - tag list
	 * @since 0.13.0
	 */
	public function renderFormBegin($tags);

	/**
	 * Method generated html for form close html element
	 * @since 0.13.0
	 */
	public function renderFormEnd();

	/**
	 * Method generated html for form submit button
	 * @param array $tags - tag list
	 * @since 0.13.0
	 */
	public function renderSubmit($tags);

}
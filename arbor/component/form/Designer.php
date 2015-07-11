<?php

namespace Arbor\Component\Form;
use Arbor\Component\Form\FormBuilder;

interface Designer{
	
	/**
	 * Method with rules create field for FormBuilder
	 * @param Arbor\Component\Form\FormBuilder $formBuilder
	 * @since 0.18.0
	 */
	public function build(FormBuilder $formBuilder);
}
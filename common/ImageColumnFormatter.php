<?php

namespace Common;

use Arbor\Component\Grid\ColumnFormatter;

class ImageColumnFormatter implements ColumnFormatter
{

	public function __construct()
	{
	}

	/**
	 * {@inheritdoc}
	 */
	public function render($data)
	{
//		var_dump($data); die;
//		$html='';
//		$action='';
//		$icon='';
//		foreach($this->buttons as $button){
//			$action=$button;
//			$href='href=""';
//			switch($button){
//				case 'show':
//					$href='href="/'.$this->prefix.'/'.$action.'/'.$data.'"';
//				break;
//				case 'edit':
//					$href='href="/'.$this->prefix.'/'.$action.'/'.$data.'"';
//				break;
//				case 'remove':
//					$href='href="/'.$this->prefix.'/'.$action.'/'.$data.'"';
//				break;
//			}
//
//			$html.='<a type="button" '.$href.' class="btn btn-default btn-xs">'.ucfirst($action).'</a>';
//
//		}
		$html = "";
		if (is_file(ltrim($data, '/'))) {
			$html = '<a href="' . $data . '" target="_blank" data-toggle="lightbox"><img src="' . $data . '" width="50" /></a>';
		}

		return $html;

	}

}
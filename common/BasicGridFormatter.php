<?php

/*
 * This file is part of the HMS project.
 *
 * (c) CoderDojo Polska Foundation
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Common;

use Arbor\Component\Grid\GridFormatter;

/**
 * Formatter for grid.
 * @package Common
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class BasicGridFormatter implements GridFormatter{

	private $prefix;
	private $addAction;
	private $extendButtons;
	public function __construct($prefix,$addAction=true,$extendButtons=array()){
		$this->prefix=$prefix;
		$this->addAction=$addAction;
		$this->extendButtons=$extendButtons;

	}

	/**
	 * {@inheritdoc}
	 */
	public function render($columns,$records,$totalCount,$limit,$page){

		$html=$this->renderHead($columns);
		$html.=$this->renderBody($records,$columns);
		$html.=$this->renderFoot($totalCount,$limit,$page,count($columns));
		return $html;
	}

	/**
	 * Generate head grid in html tags
	 *
	 * @param array $columns to display in grid
	 * @return string
	 */
	private function renderHead($columns){
		$html='<table class="table">
					<thead>
						<tr>';
		foreach($columns as $column){
			$html.='<th data-id="'.$column['key'].'" data-template="'.htmlspecialchars($column['formatter']->render('{data}')).'" data-name="'.$column['key'].'">'.$column['label'].'</th>';
		}

		$html.='</tr>
				</thead>';
		return $html;
	}

	/**
	 * Generate body grid in html tags
	 *
	 * @param array $records to display in grid
	 * @param array $columns to display in grid
	 * @return string
	 */
	private function renderBody($records,$columns){

		$html='<tbody>';
		foreach($records as $record){
			$html.='<tr>';
			foreach($columns as $column){
				$html.='<td>'.$column['formatter']->render($record[$column['key']]).'</td>';
			}

			$html.='</tr>';

		}
		$html.='</tbody>';
		return $html;
	}

	/**
	 * Generate foot grid in html tags
	 *
	 * @param int $count records
	 * @param int $limit records on one page
	 * @param int $page - current page number
	 * @param int $colspan for html tags
	 * @return string
	 */
	private function renderFoot($count,$limit,$page,$colspan){
		$html='<tfoot>
					<tr>
					<td colspan="'.$colspan.'" class="text-center">

						<nav>
						  <ul class="pagination">
						    <li>
						      <a href="/'.$this->prefix.'?page='.($page-1>0?$page-1:1).'" aria-label="Previous">
						        <span aria-hidden="true">&laquo;</span>
						      </a>
						    </li>';
						    $pages=(int)($count/$limit);
						    if($pages<($count/$limit))
						    	$pages+=1;

						    if($pages==0)
						    	$pages=1;
						    for($i=1; $i <=$pages; $i++){
						    	$html.='<li><a href="/'.$this->prefix.'?page='.$i.'">'.$i.'</a></li>';
						    }

						      $html.='<li>
						      <a href="/'.$this->prefix.'?page='.($page+1<$pages?$page+1:$pages).'" aria-label="Next">
						        <span aria-hidden="true">&raquo;</span>
						      </a>
						    </li>
						  </ul>
						</nav>
					</td>
					</tr>';
					if($this->addAction){
						$html.='<tr>
						  		<td colspan="'.$colspan.'">';

						  		$html.='<a href="/'.$this->prefix.'/add" class="btn btn-primary">Add</a>';
						  foreach($this->extendButtons as $button){
						  		$html.='<a href="/'.$button['url'].'" class="btn btn-default">'.$button['label'].'</a></td>';
						  }
						$html.='  	</td></tr>';

					}
			$html.='	</tfoot>
			</table>';


		return $html;
	}

}
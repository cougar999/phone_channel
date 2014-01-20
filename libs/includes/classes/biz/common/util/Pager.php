<?php

define('PAGER_TYPE_MAIN', 1);
define('PAGER_TYPE_PS', 2);
define('PAGER_TYPE_CLUB', 3);
define('PAGER_TYPE_MALL', 4);
define('PAGER_TYPE_ALBUM', 5);

/**
* Pager class
* @package biz.common.util
* @version 1.0
* @author adou<adou@uland.com>
* @ create date		: 2006-06-27
* @ change history	: 
*/
class Pager {

	protected $currentPageIndex;//当前页码
	protected $totalRecords;//总记录数
	protected $pageSize;//每页显示的记录数
	protected $groupSize;//每次显示多少个页码
	protected $url;//链接的url
	protected $args;//除了页码以外的参数
	protected $enableJump = true;//支持页面跳转
	protected $enableFirstLast = false;//支持首页尾页
	protected $alignCenter = false;//当前页码居中显示
	protected $pagerType;
	protected $target = '';

	/**
	* 构造函数
	*@param $page 页码
	*@param $total_cnt 总的记录数
	*@param $url 当前页码
	*@param $view_cnt 每页显示的记录数
	*@param $block_cnt 每组显示的页码数 为了显示美观 最好为奇数
	*@param $args 除页码外的页面参数
	*@example 
		$obj_Pager1 = new Pager(2, 100, '/list.php', 10, 10, 'mh_id=2000010000&menuid=3'); 
		echo $obj_Pager1->getPagingHtml(); echo "<hr>\n";
		 
		$obj_Pager2 = new Pager(11, 1000, '/list.php'); 
		echo $obj_Pager2->getPagingHtml(); echo "<hr>\n";
		 
		$obj_Pager3 = new Pager(1, 200, '/list.php'); 
		echo $obj_Pager3->getPagingHtml(); echo "<hr>\n";
		 
		$obj_Pager4 = new Pager(20, 201, '/list.php', 10, 5);
		$obj_Pager4->setPagerType(PAGER_TYPE_PS);
		echo $obj_Pager4->getPagingHtml();
		echo "<hr>\n";
		
		$obj_Pager5 = new Pager(5, 201, '/list.php', 10, 5);
		$obj_Pager5->setPagerType(PAGER_TYPE_PS);
		echo $obj_Pager5->getPagingHtml();
		echo "<hr>\n";
	*/
	function __construct($page=1, $total_cnt=0, $url='', $view_cnt=10, $block_cnt=5, $args=null) {

		$this->currentPageIndex = max($page, 1);
		$this->totalRecords = max($total_cnt, 0);
		$this->url = $url;
		$this->pageSize = max($view_cnt, 1);
		$this->groupSize = max($block_cnt, 1);
		$this->args = $args;

		//默认使用 main 的样式
		$this->setPagerType(PAGER_TYPE_MAIN);
	}

	function __destruct() {
	}

	/**
	* 设置分页类型
	*@param $pagerType			<BR>
	*		PAGER_TYPE_MAIN //默认	<BR>
	*		PAGER_TYPE_PS		<BR>
	*		PAGER_TYPE_CLUB	<BR>
	*/
	public function setPagerType($pagerType) {
		$this->pagerType = $pagerType;

		switch ($this->pagerType) {
			case PAGER_TYPE_MAIN:
			$this->enableJump = false;
			$this->enableFirstLast = true;
			$this->alignCenter = true;
			break;

			case PAGER_TYPE_PS:
			case PAGER_TYPE_CLUB:
			case PAGER_TYPE_ALBUM:
			$this->enableJump = true;
			$this->enableFirstLast = false;
			$this->alignCenter = true;
			break;

			case PAGER_TYPE_MALL:
			$this->enableJump = true;
			$this->enableFirstLast = true;
			$this->alignCenter = true;
			break;
		}
	}

	/**
	* 是否显示 Go 按钮
	*/
	public function setEnableJump($enableJump) {
		$this->enableJump = $enableJump;
	}

	/**
	* 是否显示首页 尾页
	*/
	public function setEnableFirstLast($enableFirstLast) {
		$this->enableFirstLast = $enableFirstLast;
	}

	/**
	* 当前页码是否居中显示
	*/
	public function setAlignCenter($alignCenter) {
		$this->alignCenter = $alignCenter;
	}

	/**
	* URL Target
	*/
	public function setTarget($target) {
		$this->target = $target;
	}

	public function getLimitQuery() {
		return "LIMIT ".($this->pageSize*($this->currentPageIndex-1)).
		",".$this->pageSize;
	}

	/**
	* 返回页码总数
	*/
	public function getTotalPages() {
		return ceil($this->totalRecords / $this->pageSize);
	}

	public function getPagingHtml () {
		//计算页码
		$sumPage = $this->getTotalPages();

		$this->currentPageIndex = max($this->currentPageIndex, 1);
		$this->currentPageIndex = min($this->currentPageIndex, $sumPage);

		//计算分组 ---------------------
		if ($this->alignCenter) {
			// 页码居中的方式
			//当前组的第一页
			$groupStart = $this->currentPageIndex - floor($this->groupSize / 2);
			$groupStart = max($groupStart, 1);
			//当前组的最后一页
			$groupEnd = $groupStart + $this->groupSize - 1;
			$groupEnd = min($groupEnd, $sumPage);

		} else {
			//页码不居中的方式
			//总共的分组数
			$sumGroup =floor(($this->currentPageIndex - 1)/ $this->groupSize);
			//当前组的第一页
			$groupStart = 1;
			if ($sumGroup > 0) {
				$groupStart = $sumGroup * $this->groupSize + 1;
			}
			//当前组的最后一页
			$groupEnd = $groupStart + $this->groupSize - 1;
			$groupEnd = min($groupEnd, $sumPage);
		}
		//-------------------------

		$html = "";

		if ($this->enableJump) {
			$html .= "<form action=\"{$this->url}";
			$html .= "\" method=\"get\" onsubmit=\"return (this.page.value.length > 0 && this.page.value <= {$sumPage});\">\n";
			if ($this->args) {
				$arr_params = split('&',$this->args);
				foreach ($arr_params as $val) {
					$tmp = explode("=",$val);
					$html .= "<input type=\"hidden\" name=\"{$tmp[0]}\" value=\"{$tmp[1]}\" />\n";
				}
			}
		}
		//$html .= "<div class=\"blockleft\">&nbsp;</div>\n";
		//$html .= '<div class="pagenumber">';

		/*if ($this->enableFirstLast && $this->currentPageIndex > 1) {
			$html .= $this->getLinkInfo(1, '首页');
		}*/

		/*if ($this->currentPageIndex > 1) {
			$html .= $this->getLinkInfo($this->currentPageIndex - 1, '上一页');
		}*/

		$html .= '';
		
		for ($i = $groupStart; $i <= $groupEnd; $i++) {
			$html .= $this->getLinkInfo($i);
		}

		$html .= '';
		
		/*if ($this->currentPageIndex < $sumPage) {
			$html .= $this->getLinkInfo($this->currentPageIndex + 1, '下一页');
		}*/

		/*if ($this->enableFirstLast && $this->currentPageIndex < $sumPage) {
			$html .= $this->getLinkInfo($sumPage, '尾页');
		}*/

		//$html .= "</div>\n";

		if ($this->enableJump) {
			//$html .= "<div class=\"blockright\">\n";
			$maxLength = max(strlen($sumPage),2)*8;
			$html .= "&nbsp;&nbsp;第<input type=\"text\" name=\"page\" class=\"flat\" value=\"{$this->currentPageIndex}\" align=\"absmiddle\"";
			$html .= " onkeypress=\"return (event.keyCode >= 48 && event.keyCode <= 57);\"";
			$html .= " maxlength=\"{$maxLength}\" style=\"width:{$maxLength}px\" />/<span class=\"number\">{$sumPage}</span>页\n";
			//$html .= "<input type=\"submit\" value=\"Go\" />\n";
			$html .= "<input type=\"submit\" align=\"absmiddle\" value=\"GO\" />\n";
			//$html .= "</div>\n";
			$html .= "</form>\n";
		} else {
			//$html .= "<div class=\"blockright\">&nbsp;</div>\n";
		}
		return $html;
	}

	protected function getLinkInfo($pageno,$html=null) {
		$link_html = '';

		if ($this->target) {
			$target_html = "target=\"{$this->target}\"";
		} else {
			$target_html = '';
		}

		if (null == $html) {
			if ($pageno == $this->currentPageIndex) {
				//$link_html = "<a href=\"#\" class=\"current\">$pageno</a>";
				//$link_html = "<a href=\"#\" class=\"hatchinglink\" {$target_html}>$pageno</a>";
				$link_html = "<strong>{$pageno}</strong>";
			}
			else {
				$link_html = "<a href=\"".$this->url."?pageno=".$pageno;
				if ($this->args) {
					$link_html.="&".$this->args;
				}
				//$link_html.= "\" class=\"normal\">".$pageno."</a>";
				//$link_html.= "\" class=\"hatching\" {$target_html}>".$pageno."</a>";
				$link_html.= "\" {$target_html}>".$pageno."</a>";
			}
		}
		else {
			if ($pageno==$this->currentPageIndex) {
				// Nothing
			}
			else {
				$link_html = "<a href=\"".$this->url."?pageno=".$pageno;
				if ($this->args) {
					$link_html.="&".$this->args;
				}
				$link_html.= "\" {$target_html}>".$html."</a>";
			}
		}
		return $link_html."\n";
	}
}
?>
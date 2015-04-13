<?php namespace snp\pagination{

class PagesInfo {

	public $totalRows =0; // Total number of items (database results)
	public $perPage=10; // Max number of items you want shown per page
	public $numLinks=2; // Number of "digit" links to show before/after the currently viewed page
	public $currentPage=0; // The current page being viewed


	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	array	initialization parameters
	 */
	public function __construct($params = array()) {
		if (count($params) > 0) {
			$this->initialize($params);
		}
	}


	/**
	 * Initialize Preferences
	 *
	 * @access	public
	 * @param	array	initialization parameters
	 * @return	void
	 */
	public function initialize($params = array()) {
		if (count($params) > 0) {
			foreach ($params as $key => $val) {
				if (isset($this->$key)) {
					$this->$key = $val;
				}
			}
		}
		$this->numLinks = (int) $this->numLinks;
		if ($this->numLinks < 1) {
            die('$numlinks must be a positive integer');
		}
	}



    //计算总页数
    private function caculateTotalPages(){
		if ($this->totalRows == 0 OR $this->perPage == 0) {
			return 0;
		}
		// Calculate the total number of pages
        $this->totalPages= intval(ceil($this->totalRows / $this->perPage));
        return $this->totalPages;
    }

    //会在setCurrentPage()时自动调用
    private function caculateFirstPage(){
        return $this->firstPage=1;
    }

    //会在setCurrentPage()时自动调用
    private function caculateLastPage(){
        return $this->lastPage=$this->caculateTotalPages();
    }

    //根据输入的$currentPage判断是否在firstPage和lastPage之间
    public function setCurrentPage($currentPage){
        $currentPage=intval($currentPage);

        //判断是否在firstPage和lastPage之间
        $this->caculateFirstPage();
        $this->caculateLastPage();
        $this->currentPage=$currentPage<$this->firstPage?$this->firstPage:$currentPage;
        return $this->currentPage=$this->currentPage>$this->lastPage?$this->lastPage:$this->currentPage;
    }


    private function caculatePrevPage(){
        $this->prevPage=$this->currentPage-1;
        return $this->prevPage=$this->prevPage<$this->firstPage?
            $this->firstPage:$this->prevPage;
    }

    private function caculateNextPage(){
        $this->nextPage=$this->currentPage+1;
        return $this->nextPage=$this->nextPage>$this->lastPage?
            $this->lastPage:$this->nextPage;
    }

    private function caculateFirstDigit(){
        return $this->firstDigit = (($this->currentPage - $this->numLinks) > 0) ?
            ($this->currentPage - $this->numLinks):
            $this->firstPage;
    }

    private function caculateLastDigit(){
        return $this->listDigit = (($this->currentPage + $this->numLinks) < $this->totalPages) ? 
            ($this->currentPage + $this->numLinks): 
            $this->lastPage;
    }


    /**
     *
     *
     * @return  array{
     *     array('firstPage','$page'),
     *     array('prevPage',$page),
     *     array('currentPage',$page),
     *     array('nextPage',$page),
     *     array('lastPage','$page'),
     *     array('firstDigit'=>$firstDigit),
     *     array('firstDigit'=>$firstDigit)
     * }
     */
    public function generatePagesInfo($currentPage=1){

        $this->setCurrentPage($currentPage);

        $info=array();
        $info['currentPage']=$this->currentPage;
        $info['firstPage']=$this->firstPage;
        $info['lastPage']=$this->lastPage;

        $info['prevPage']=$this->caculatePrevPage();
        $info['nextPage']=$this->caculateNextPage();

        $info['firstDigit']=$this->caculateFirstDigit();
        $info['lastDigit']=$this->caculateLastDigit();

        return $info;
    }
}}?>

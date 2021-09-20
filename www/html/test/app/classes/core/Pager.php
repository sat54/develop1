<?php

/**
 * ページャークラス
 */
class Pager
{

    /**
     * コンストラクタ
     */
    function __construct ()
    {
        $this->range = 5;
        $this->perpage = 10;
        $this->prev_label = '&lt;&lt; 前の'.$this->perpage.'件';
        $this->next_label = '次の'.$this->perpage.'件 &gt;&gt;';
        $this->first_label = '最初';
        $this->last_label = '最後';
    }
    
    /**
     * ナビゲーションhtmlを取得
     * 
     * @param array $params
     * @return array
     */
    function get($params)
    {
        if(!isset($params['perpage'])){
            $params['perpage'] = $this->perpage;
        }
        if(!isset($params['range'])){
            $params['range'] = $this->range;
        }
        if(!isset($params['current_page'])){
            $params['current_page'] = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        }
        if(isset($params['prev_label'])){
            $this->prev_label = $params['prev_label'];
        }
        if(isset($params['next_label'])){
            $this->next_label = $params['next_label'];
        }

        extract($params);

        $pager = array();

        $total_count = ceil($total_count);

        //total_pages
        $total_pages = ceil($total_count / $perpage);

        //range
        $range = ($total_pages < $range) ? $total_pages : $range;

        //start_page
        $start_page = 1;
        if ($current_page >= ceil($range / 2)) {
            $start_page = $current_page - floor($range / 2);
        }
        $start_page = ($start_page < 1) ? 1 : $start_page;

        //end_page
        $end_page   = $start_page + $range - 1;
        if ($current_page > $total_pages - ceil($range / 2)) {
            $end_page   = $total_pages;
            $start_page = $end_page - $range + 1;
        }

        //start_cnt
        $start_cnt = ceil($current_page - 1) * $perpage + 1;

        //end_cnt
        $end_cnt = ceil($current_page) * $perpage <  $total_count ? ceil($current_page) * $perpage : $total_count;

        //prev
        if ($current_page > $start_page) {
            $prev = $current_page - 1;
        } else {
            $prev = NULL;
        }

        //next
        if ($current_page < $end_page) {
            $next = $current_page + 1;
        } else {
            $next = NULL;
        }

        //offset
        $offset = ceil($current_page - 1) * $perpage;

        //limit
        $limit = ($total_count < $perpage)? $total_count : $perpage;

        //html
        $url = $_SERVER['REQUEST_URI'];
        if(stristr($_SERVER['REQUEST_URI'],'?')){
            $url = preg_replace('/p=([0-9]+)&?/','',$_SERVER['REQUEST_URI']);
            $url = preg_replace('/action=(.+)&?/','',$url);
        }

        if (empty($_SERVER['QUERY_STRING'])) {
            $url .= '?p=';
        } elseif(substr($url,-1) != '?' and substr($url,-1) != '&') {
            $url .= '&p=';
        } else {
            $url .= 'p=';
        }

        $links = array();

        //prev
        if(empty($prev)){
            $links['prev'] = '';
            $links['prev'] = "<li class=\"prev nolink\">{$this->prev_label}</li>\n";
        } else {
            $links['prev'] = "<li class=\"prev\"><a href=\"{$url}{$prev}\">{$this->prev_label}</a></li>\n";
        }

        //next
        if(empty($next)){
            $links['next'] = '';
            $links['next'] = "<li class=\"next nolink\">{$this->next_label}</li>\n";
        } else {
            $links['next'] = "<li class=\"next\"><a href=\"{$url}{$next}\">{$this->next_label}</a></li>\n";
        }

        //first
        $class = 'class="first"';
        if($current_page == 1){
            $class = "class='first active'";
        }
        if(isset($current_page) and $current_page != 1) {
            $links['first'] = "<li $class><a href=\"{$url}1\">最初</a></li>\n";
        } else {
            $links['first'] = "<li class='first'>最初</li>\n";
        }
        //last
        $class = 'class="last"';
        if($current_page == $total_pages){
            $class = "class='first active'";
        }
        
        if($total_pages > 1 and $total_pages != $current_page) {
            $links['last'] = "<li $class><a href=\"{$url}{$total_pages}\">最後</a></li>\n";
        } else {
            $links['last'] = "<li class='last'>最後</li>\n";
        }
        
        //link
        $links['pages'] = array();
        for ($i=$start_page;$i<=$end_page;$i++) {
            $class = '';
            if($i == $current_page){
                $class = "class='page active'";
            }
            if(!empty($class)){
                $links['pages'][] = "<li $class>{$i}</li>\n";
            } else {
                $links['pages'][] = "<li class='page'><a href=\"{$url}{$i}\">{$i}</a></li>\n";
            }
        }

        //return
        $this->links = array(
                    'total_count' => $total_count,
                    'total_pages' => $total_pages == 0 ? 1 : $total_pages,
                    'current_page'     => $current_page,
                    //'range'       => $range,
                    //'start_page'  => $start_page,
                    //'end_page'    => $end_page,
                    'start_cnt'   => $end_cnt > 0 ? $start_cnt : 0,
                    'end_cnt'     => $end_cnt,
                    //'prev'        => $prev,
                    //'next'        => $next,
                    'offset'      => $offset,
                    'limit'       => $limit,
                    'links'       => $links,
                    );
        
        return $this->links;
    }

    /**
     * ナビゲーションhtmlを取得
     * 
     * @param array $params
     * @return array
     */
    function correction()
    {
        if( $this->links['total_pages'] == 0) {
            return;
        }
        
        if( $this->links['current_page'] == 1) {
            return;
        }
        
        if ($this->links['current_page'] > $this->links['total_pages']) {
           $location = preg_replace("/p=[0-9]+/", "p={$this->links['total_pages']}", $_SERVER['REQUEST_URI']);
           header('Location: '. $location);
           exit;
        }
    }
    
}

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
        $this->prev_label = '<span uk-pagination-previous></span>';
        $this->next_label = '<span uk-pagination-next></span>';
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
            $params['current_page'] = isset($_GET['page']) ? (int)$_GET['page'] : 1;
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
            $url = preg_replace('/page=([0-9]+)&?/','',$_SERVER['REQUEST_URI']);
            $url = preg_replace('/action=(.+)&?/','',$url);
        }

        if (empty($_SERVER['QUERY_STRING'])) {
            $url .= '?page=';
        } elseif(substr($url,-1) != '?' and substr($url,-1) != '&') {
            $url .= '&page=';
        } else {
            $url .= 'page=';
        }

        $links = array();

        //prev
        if(empty($prev)){
            $links['prev'] = '';
            $links['prev'] = "<li class=\"prev\"><a href='javascript:void(0);'>{$this->prev_label}</a></li>\n";
        } else {
            $links['prev'] = "<li class=\"prev\"><a href=\"{$url}{$prev}\">{$this->prev_label}</a></li>\n";
        }

        //next
        if(empty($next)){
            $links['next'] = '';
            $links['next'] = "<li class=\"next\"><a href='javascript:void(0);'>{$this->next_label}</a></li>\n";
        } else {
            $links['next'] = "<li class=\"next\"><a href=\"{$url}{$next}\">{$this->next_label}</a></li>\n";
        }

        //first
        $class = 'class="first"';
        if($current_page == 1){
            $class = "class='first active'";
        }
        if(isset($current_page) and $current_page != 1) {
            $links['first'] = "<li $class><a href=\"{$url}1\">1</a></li>\n";
        } else {
            $links['first'] = "<li>1</li>\n";
        }
        //last
        $class = 'class="last"';
        if($current_page == $total_pages){
            $class = "class='first active'";
        }
        
        if($total_pages > 1 and $total_pages != $current_page) {
            $links['last'] = "<li><a href=\"{$url}{$total_pages}\">{$total_pages}</a></li>\n";
        } else {
            $links['last'] = "<li>{$total_pages}</li>\n";
        }
        
        //link
        $links['pages'] = array();
        for ($i=$start_page;$i<=$end_page;$i++) {
            $class = '';
            if($i == $current_page){
                $class = "class='page active'";
            }
            if(!empty($class)){
                $links['pages'][] = "<li><em>{$i}</em></li>\n";
            } else {
                $links['pages'][] = "<li><a href=\"{$url}{$i}\">{$i}</a></li>\n";
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
                    'start_cnt'   => $end_cnt == 0 ? 0 : $start_cnt,
                    'end_cnt'     => $end_cnt,
                    'start_page'  => $start_page,
                    'end_page'    => $end_page,
                    'page_list'   => range($start_page, $end_page),
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
           $location = preg_replace("/page=[0-9]+/", "page={$this->links['total_pages']}", $_SERVER['REQUEST_URI']);
           header('Location: '. $location);
           exit;
        }
    }

    /**
     * 整形
     */
    function format($paginate)
    {
        $pager = "";
        $pager .= "<ul class='pager'>";
        $pager .= "<p>".h($paginate['start_cnt'])."～". h($paginate['end_cnt'])."件/".h($paginate['total_count'])."件</p>";
        $pager .= strip_tags($paginate['links']['prev'], '<a><li>');
        if (!in_array(1, $paginate['page_list'])) {
            $pager .= $paginate['links']['first'];
            $pager .= "<li><span>…</span></li>";
        }
        foreach ($paginate['links']['pages'] as $page){
            $pager .= strip_tags($page, '<a><li><span>');
        }
        if (!in_array($paginate['total_pages'], $paginate['page_list'])) {
            $pager .= "<li><span>…</span></li>";
            $pager .= $paginate['links']['last'];
        }
        $pager .= strip_tags($paginate['links']['next'], '<a><li>');
        $pager .= "</ul>";

        return $pager;
    }
    
}

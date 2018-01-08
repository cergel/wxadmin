<?php

/**
 * 简单分页(展示)
 *
 * @author CHAIYUE
 * @version 2016-12-22
 */
class SimplePaginateWidget extends CWidget
{
    public $total;
    public $perPage;
    public $lastPage;
    public $currentPage;
    private $pageName = 'page';
    private $query = [];
    private $path = '/';

    public function run()
    {
        $perPage = $this->perPage;
        $total = $this->total;
        $this->path = Yii::app()->request->getPathInfo();
        $request = Yii::app()->getRequest()->queryString;
        parse_str($request, $this->query);
        $this->lastPage = (int)ceil($total / $perPage);
        $this->currentPage = $this->currentPage > $this->lastPage ? $this->lastPage : $this->currentPage;
        $this->render('SimplePaginate', ['paginator' => $this]);
    }

    /**
     * 获取当前页
     */
    public function currentPage()
    {
        return $this->currentPage;
    }

    /**
     * 是否第一页
     */
    public function onFirstPage()
    {
        return $this->currentPage > 1;
    }

    /**
     * 获取最后一页页码
     */
    public function lastPage()
    {
        return $this->lastPage;
    }

    /**
     * 是否有下一页
     */
    public function hasMorePages()
    {
        return $this->currentPage() < $this->lastPage();
    }

    /**
     * 下一页
     */
    public function nextPageUrl()
    {
        if ($this->lastPage() > $this->currentPage()) {
            return $this->url($this->currentPage() + 1);
        }
    }

    /**
     * 上一页
     */
    public function previousPageUrl()
    {
        if ($this->currentPage() > 1) {
            return $this->url($this->currentPage() - 1);
        }
    }

    /**
     * 第一页
     */
    public function firstUrl()
    {
        return $this->url(1);
    }

    /**
     * 最后一页
     */
    public function lastUrl()
    {
        return $this->url($this->lastPage());
    }

    /**
     * 总页数
     */
    public function total()
    {
        return $this->total;
    }

    /**
     * 创建url
     * @param $page
     * @return string
     */
    public function url($page)
    {
        if ($page <= 0) {
            $page = 1;
        }

        $parameters = [$this->pageName => $page];
        if (count($this->query) > 0) {
            $parameters = array_merge($this->query, $parameters);
        }
        return '/' . $this->path . (self::contains($this->path, '?') ? '&' : '?') . http_build_query($parameters, '', '&');
    }


    /**
     * 添加请求参数
     * @param $key
     * @param $value
     * @return $this
     */
    public function addQuery($key, $value)
    {
        if ($key !== $this->pageName) {
            $this->query[$key] = $value;
        }
        return $this;
    }

    public static function contains($haystack, $needles)
    {
        foreach ((array)$needles as $needle) {
            if ($needle != '' && mb_strpos($haystack, $needle) !== false) {
                return true;
            }
        }
        return false;
    }

}
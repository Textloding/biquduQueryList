<?php
declare (strict_types = 1);

namespace app\index\controller;

use QL\QueryList;
class Index
{
    public function index()
    {
        if (!empty($_POST)) {
            $html = $_POST['html'];
            $list = [];
            $start=$_POST['start']-1;
            $last=$_POST['last'];
            //获取文章所有的链接
            $data=QueryList::get($html)->find('dl>dd:eq(11)')->nextAll()->find('a')->attrs('href')->all();
            $end=count($data);
            if ($last>$end){
                return "<script>alert('章节不存在')</script>";
            }
            //获取文章链接
            for($i=0;$i<$end;$i++){
                $data[$i]=explode('/',$data[$i]);
                $data[$i]=$html.$data[$i][2];
            }
            for($start; $start<$last; $start++) {
                $url = $data[$start];
                //获取爬取的书名和章节以及链接
                $bname = QueryList::get($url)->find('.bookname>h1')->text();
               //获取爬取的网址
                $ql = QueryList::get($url)->find('#content');
                //选择要移除的元素，标签
                $ql->find('div:a,div,script')->remove();
                //指向爬取内容的标签 id class 和属性
                $content = $ql->html();
                $ye=$start+1;
                $list[$ye]= array('第'.$ye.'章'=>$bname,'链接' => $url, '内容' => $content);

            }
                echo "<pre>";
                print_r($list);
                echo "<pre>";

        } else {
            return \think\facade\View::fetch();
        }

    }



}



<?php
/**
 * Created by PhpStorm.
 * User: hansongda@hotmail.com
 * Date: 2018/01/01
 * Time: 01:52
 */

namespace App\Http\Controllers\Backend;


use App\Http\Controllers\Controller;
use Models\Posts;
use Persimmon\Interfaces\CreatorInterface;
use Naux\AutoCorrect;


class ImportblogController extends Controller implements CreatorInterface
{

    /*******************************************
     * Delegate Action
     ******************************************/

    /**
     * Create Fail Use
     *
     * @param $error
     * @return \Illuminate\Http\JsonResponse
     */
    public function creatorFail($error)
    {
        $this->_response = ['status' => 'error', 'info' => $error];
    }

    /**
     * Create Success Use
     *
     * @param $model
     * @return \Illuminate\Http\JsonResponse
     */
    public function creatorSuccess($model)
    {
        $this->_response = ['status' => 'success', 'info' => '操作成功'];
    }

    /**
     * 博客园博客导入
     */
    public function importBlog(){

        $filename = '/data/www/blogbackup.xml';// 博客园备份成xml文件

        $xml = simplexml_load_file($filename, null, LIBXML_NOCDATA);
        $xml = json_decode(json_encode($xml), true);
        $content = array_reverse($xml['channel']['item']);

        foreach($content as $con){
            $posts = new Posts;
            $posts->title = (new AutoCorrect())->convert($con['title']);
            $posts->flag = strtolower($this->randstr());
            $posts->thumb = '';
            $posts->category_id = 1;
            $posts->user_id = 1;
            $posts->content = (new \Parsedown())->text($con['description']);
            $posts->markdown = $con['description'];
            $posts->ipaddress = '';
            $posts->created_at = date('Y-m-d H:i:s', strtotime($con['pubDate'])+8*3600);

            $re = $posts->save();
        }

    }

    /**
     * @param int $len
     * @return string
     */
    function randstr($len = 12){
        $re = '';
        $str = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        for($i = 0; $i < $len; $i++){
            $re .= $str[rand(0,61)];
        }
        return $re;
    }


}
<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Photo;
use DB;

class PhotoController extends Controller
{
   public function add(Request $res)
   {
//            $id = $res->input('id');
          $data = $res->all();
           $photo = $res->input('p1');
           //设置七牛
           $drive = \Storage::drive('qiniu');
           //将图文件url传入七牛空间
                $arr =[];
            foreach($photo as $v){
                $handle = fopen($v['thumbUrl'], 'r');
                $tname = str_random(10);
                $dir = 'aqiduotu/' . date('Ymd', time());
                $filename = $dir . '/' . $tname;
                //==============================
                $drive->writeStream("$filename", $handle);
                $name = 'http://peol8ff6o.bkt.clouddn.com/'.$filename;
                $arr[] = $name;
            }

            $arr = implode('#',$arr);
            $data['p1'] = $arr;
           //===设置数据库引用链接

             $res = Photo::insert($data);
       if($res){
          return response()->json('66');
       }else{
          return response()->json('88');
      }

   }



}

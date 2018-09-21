<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Users;
use DB;
use function PHPSTORM_META\elementType;
use function Qiniu\thumbnail;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $data = Users::all();

//        echo  json_encode();
        return response()->json($data);
    }

    public function add(Request $request){
           //获取图文件
        $dd = $request->has('photo');
        $name = '';
        if($dd) {
            $photo = $request->input('photo');
            //设置七牛
            $drive = \Storage::drive('qiniu');
            //将图文件url传入七牛空间
            $handle = fopen($photo[0]['thumbUrl'], 'r');
            //===========================
            //设置存储文件名
            $tname = str_random(10);
            $dir = 'aqi/' . date('Ymd', time());
            $filename = $dir . '/' . $tname;
            //==============================
            $drive->writeStream("$filename", $handle);
            //===设置数据库引用链接
            $name = 'http://peol8ff6o.bkt.clouddn.com/'.$filename;
        }
        //==================
        $data = $request->except('photo');
        $data['photo'] = $name;
        $res = DB::table('users')->insert($data);
        if($res){
            $data = Users::all();
            return response()->json($data);
        }

    }

    public function del(Request $request)
    {
        $id = $request->all();
        $res = DB::table('users')->where('id',$id)->delete();
        if($res){
            $data = Users::all();
            return response()->json($data);
        }
    }

    public function edd(Request $request)
    {
        $res = DB::table('users')->insert($request->all());

//        $data = $request->all();
//        $user = new Users;
//        $user->name = $data['name'];
//        $user->tel = $data['tel'];
//        $user->photo = $data['photo'];
//        $res = $user->save();

        if($res){
            $data = Users::all();
            return response()->json($data);
        }
    }

    public function upp(Request $request)
    {
       $id = $request->all();
       $data = DB::table('users')->where('id',$id)->first();
       return response()->json($data);

    }
//　＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝修改的方法
    public function edit(Request $request)
    {           //判断是否有图片
        $dd = $request->has('photo');
        $id = $request->input('id');
        $data =$request->except('id');
        if($dd) {
            $photo = $request->input('photo');
            //设置七牛
            $drive = \Storage::drive('qiniu');
            //将图文件url传入七牛空间
            $handle = fopen($photo[0]['thumbUrl'], 'r');
            //===========================
            //设置存储文件名
            $tname = str_random(10);
            $dir = 'aqi/' . date('Ymd', time());
            $filename = $dir . '/' . $tname;
            //==============================
            $drive->writeStream("$filename", $handle);
            //===设置数据库引用链接
            $name = 'http://peol8ff6o.bkt.clouddn.com/'.$filename;
            $data['photo'] = $name;
        }

        $res = DB::table('users')->where('id',$id)->update($data);
        if($res){
            $data = Users::all();
            return response()->json($data);
        }
    }

    public function login(Request $res)
    {

//        return response()->json( $res->all() );
        $nam = $res->input('name');
        $tel =  $res->input('tel');
        $re = Users::where(['name'=>$nam,'tel'=>$tel])->first();

        if($re){
            $data = [ 'status' =>'ok','type'=>'account','currentAuthority'=>'user' ];
            return response()->json( $data);
        }else{
            return response()->json( 88);
        }
    }
                //查询方法－－
   public function search(Request $res)
   {             //获取查询条件　是下标为０的数组
     $re =  $res->all();
    $data = Users::where('tel', 'like', '%'.$re[0].'%')->orWhere('name', 'like', '%'.$re[0].'%')
        ->orWhere('sex', $re[0])
        ->get();
     return response()->json( $data);

   }

    //差多图
   public function ott(Request $res){

       return response()->json($res->all());
   }
    //传多图
    public function oto(Request $res){
        $id = $res->input('id');
        $photo = $res->input('p1');
        //设置七牛
        $drive = \Storage::drive('qiniu');
        //将图文件url传入七牛空间
            $arr = [];
            foreach ($photo as $v) {

                if(strlen($v['thumbUrl'])<100){
                    $arr[] = $v['thumbUrl'];
                }else{
                    $handle = fopen($v['thumbUrl'], 'r');
                    $tname = str_random(10);
                    $dir = 'aqiduotu/' . date('Ymd', time());
                    $filename = $dir . '/' . $tname;
                    //==============================
                    $drive->writeStream("$filename", $handle);
                    $name = 'http://peol8ff6o.bkt.clouddn.com/' . $filename;
                    $arr[] = $name;
                }
            }
            //生成字符串
            $arr = implode('#', $arr);


        $order = Users::find($id);
        $order -> p1 = $arr;
        $res = $order -> save();
        if($res){
            $data = Users::all();

            return response()->json($data);
        }else{
            return response()->json('88');
        }

//        return response()->json($res->all());
    }


}

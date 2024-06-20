<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Validation\Rules;
use Hash;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index()
    {
        $result = DB::table('activity')->get();
        return view('activity.index',['result'=>$result]);
    }

    public function create()
    {
        return view('activity.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $baseurl = 'localhost:8000';
        // dd($request->all());
        $request->validate([
            'a_date' => 'required',
            'a_name' => 'required',
            'a_place' => 'required',
            'a_register_date' => 'required',
        ],
        [
            'a_date.required'=>'กรุณาระบุวันที่กิจกรรม',
            'a_name.required'=>'กรุณาระบุชื่อกิจกรรม',
            'a_place.required'=>'กรุณาระบุสถานที่จัดกิจกรรม',
            'a_register_date.required'=>'กรุณาระบุวันที่ลงทะเบียน',
        ]
    );

    $lastinsertid = DB::table('activity')->insertGetId(
        [
            'a_date' => $request->a_date,
            'a_name' => $request->a_name,
            'a_place' => $request->a_place,
            'a_regis_date' => $request->a_register_date,

        ]

        );
        // $hash = Hash::make($lastinsertid);
        $hash = md5($lastinsertid);
        $qrname = time().'.png';
        DB::table('activity')->where('a_id',$lastinsertid)->update([
            'a_qrcode' => $qrname,
            'a_hashurl' => $hash
        ]);
        
        return redirect()->route('activity.index')->with('success','บันทึกสำเร็จ'.$request->a_name);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $data = DB::table('activity')->where('a_id',$id)->first();
        $img = QrCode::size(300)->generate('http://localhost:8000/frontend/activity/'.$data->a_hashurl);
        return view('activity.show',['data'=>$data,'img'=>$img]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       $dtnow = date('Y-m-d H:i:s');

        $request->validate([
            'a_date' => 'required',
            'a_name' => 'required',
            'a_place' => 'required',
            'a_register_date' => 'required',
        ],
        [
            'a_date.required'=>'กรุณาระบุวันที่กิจกรรม',
            'a_name.required'=>'กรุณาระบุชื่อกิจกรรม',
            'a_place.required'=>'กรุณาระบุสถานที่จัดกิจกรรม',
            'a_register_date.required'=>'กรุณาระบุวันที่ลงทะเบียน',
        ]
    );

    DB::table('activity')->where('a_id',$id)->update(
        [
            'a_date' => $request->a_date,
            'a_name' => $request->a_name,
            'a_place' => $request->a_place,
            'a_regis_date' => $request->a_register_date,
            'updated_at'=> $dtnow
        ]
        );

        return redirect()->route('activity.index')->with('success','แก้ไขสำเร็จ'.$request->a_name);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::table('activity')->where('a_id',$id)->delete();
        return back()->with('success','ลบข้อมูลสำเร็จ');
    }

    public function register(string $id)
    {
        $result = DB::table('activity')
        ->join('register','activity_a_id','=','a_id')
        ->where('a_id',$id)
        ->get();
        return view('activity.register',['result'=>$result]);
    }
    
    public function rprint(string $id)
    {
        $dnow = date('Y-m-d');
        $data = DB::table('register')
        ->where ('reg_id',$id)
        ->first();

        DB::table('register')->where('reg_id',$id)->update(
            [
                'reg_status' => 'พิมพ์แล้ว',
                'exam_date' => $dnow
            ]
            );


            return view('activity.rprint', [
                'data' => $data
            ]);
            // return back()->with('success','พิมพ์ลาเบลแล้ว',['data' => $data]);
    }
}

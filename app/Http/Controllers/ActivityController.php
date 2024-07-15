<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Validation\Rules;
use Hash;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Picqer\Barcode\BarcodeGeneratorPNG;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index()
    {
        $result = DB::table('activity')->get();
        return view('activity.index', ['result' => $result]);
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
        $request->validate(
            [
                'a_date' => 'required',
                'a_name' => 'required',
                'a_place' => 'required',
                'a_register_date' => 'required',
            ],
            [
                'a_date.required' => 'กรุณาระบุวันที่กิจกรรม',
                'a_name.required' => 'กรุณาระบุชื่อกิจกรรม',
                'a_place.required' => 'กรุณาระบุสถานที่จัดกิจกรรม',
                'a_register_date.required' => 'กรุณาระบุวันที่ลงทะเบียน',
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
        $qrname = time() . '.png';
        DB::table('activity')->where('a_id', $lastinsertid)->update([
            'a_qrcode' => $qrname,
            'a_hashurl' => $hash
        ]);

        return redirect()->route('activity.index')->with('success', 'บันทึกสำเร็จ' . $request->a_name);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $data = DB::table('activity')->where('a_id', $id)->first();
        $img = QrCode::size(300)->generate('https://8785-61-19-145-132.ngrok-free.app/frontend/create/' . $data->a_hashurl);
        return view('activity.show', ['data' => $data, 'img' => $img]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $dtnow = date('Y-m-d H:i:s');

        $request->validate(
            [
                'a_date' => 'required',
                'a_name' => 'required',
                'a_place' => 'required',
                'a_register_date' => 'required',
            ],
            [
                'a_date.required' => 'กรุณาระบุวันที่กิจกรรม',
                'a_name.required' => 'กรุณาระบุชื่อกิจกรรม',
                'a_place.required' => 'กรุณาระบุสถานที่จัดกิจกรรม',
                'a_register_date.required' => 'กรุณาระบุวันที่ลงทะเบียน',
            ]
        );

        DB::table('activity')->where('a_id', $id)->update(
            [
                'a_date' => $request->a_date,
                'a_name' => $request->a_name,
                'a_place' => $request->a_place,
                'a_regis_date' => $request->a_register_date,
                'updated_at' => $dtnow
            ]
        );

        return redirect()->route('activity.index')->with('success', 'แก้ไขสำเร็จ' . $request->a_name);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::table('activity')->where('a_id', $id)->delete();
        return back()->with('success', 'ลบข้อมูลสำเร็จ');
    }

    public function register(string $id)
    {
        $result = DB::table('activity')
            ->join('register', 'activity_a_id', '=', 'a_id')
            ->where('a_id', $id)
            ->get();
        return view('activity.register', ['result' => $result]);
    }

    public function rprint(string $id)
    {
        $dnow = date('Y-m-d');
        $data = DB::table('register')
            ->where('reg_id', $id)
            ->first();
        
        $count = DB::table('register')
            ->select(DB::raw('count(reg_qr) as que_count'))
            ->where('reg_id', $id)
            ->where('activity_a_id', $data->activity_a_id)
            ->count();

            dd($count);
        // DB::table('register')->where('reg_id', $id)->update(
        //     [
        //         'reg_status' => 'พิมพ์แล้ว',
        //         'exam_date' => $dnow,
        //     ]
        // );


        // $img = QrCode::size(300)->generate('https://8785-61-19-145-132.ngrok-free.app/frontend/create/' . $data->a_hashurl);

        $generatorPNG = new \Picqer\Barcode\BarcodeGeneratorPNG();
        $img = $generatorPNG->getBarcode($id, $generatorPNG::TYPE_CODE_128);

        return view('activity.rprint', [
            'data' => $data,
            'img'=> $img
        ]);
        // return back()->with('success','พิมพ์ลาเบลแล้ว',['data' => $data]);
    }
}

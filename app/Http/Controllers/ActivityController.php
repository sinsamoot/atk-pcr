<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Validation\Rules;

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

    DB::table('activity')->insert(
        [
            'a_date' => $request->a_date,
            'a_name' => $request->a_name,
            'a_place' => $request->a_place,
            'a_regis_date' => $request->a_register_date,
        ]
        );

        return redirect()->route('activity.index')->with('success','บันทึกสำเร็จ'.$request->a_name);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = DB::table('activity')->where('a_id',$id)->first();
        return view('activity.show',['data'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
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
}

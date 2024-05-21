@extends('layouts.app')
@section('content')
<div class="card mt-4">
    <div class="card-header">
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      {{-- <li class="breadcrumb-item"><a href="#">Home</a></li> --}}
                      <li class="breadcrumb-item"><a href="{{route('activity.index')}}">รายการกิจกรรม</a></li>
                      <li class="breadcrumb-item active" aria-current="page">แก้ไข</li>
                    </ol>
                </nav>                                
            </div>
        </div>
    </div>
    <div class="card-body">
        <form action="{{route('activity.update',$data->a_id)}}" method="post">
            @csrf
            <div class="mb-3">
                <label class="form-label">วันที่ทำกิจกรรม</label>
                <input type="text" class="form-control myDate" name="a_date" value="{{$data->a_date}}">
            </div>
            <div class="mb-3">
                <label class="form-label">ชื่อกิจกรรม</label>
                <input type="text" class="form-control" name="a_name" value="{{$data->a_name}}">
            </div>
            <div class="mb-3">
                <label class="form-label">สถานที่ทำกิจกรรม</label>
                <input type="text" class="form-control" name="a_place" value="{{$data->a_place}}">
            </div>
            <div class="mb-3">
                <label class="form-label">วันที่สิ้นสุดลงทะเบียน</label>
                <input type="text" class="form-control myDate" name="a_register_date" value="{{$data->a_regis_date}}">
            </div>
            <div class="md-3">
                <button class="btn btn-success" type="button"
                onclick="Swal.fire({
                    title: 'ยืนยันการบันทึกข้อมูล ?',
                    showCancelButton: true,
                    confirmButtonText: `<i class='fa-solid fa-check-circle'></i> ตกลง`,
                    cancelButtonText: `<i class='fa-solid fa-times-circle'></i> ยกเลิก`,
                    icon: 'success',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    } else if (result.isDenied) {
                        form.reset();
                    }
                })"
                >                    
                    บันทึก</button>
            </div>
        </form>
    </div>
</div>
    
@endsection
@section('script')
<script>
    flatpickr(".myDate", {
        "locale":"th"
    });
</script>
@endsection
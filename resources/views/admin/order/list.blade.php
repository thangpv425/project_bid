@extends('admin.index')
@section('admin')
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">List Orders</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                    <tr>
                        <th class="text-center">User</th>
                        <th class="text-center">Bid</th>
                        <th class="text-center">Tax amount</th>
                        <th class="text-center">Ship amount</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Pay by</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="odd gradeX text-center">
                        <td>Trident</td>
                        <td>Internet Explorer 4.0</td>
                        <td>100</td>
                        <td>4</td>
                        <td>100</td>
                        <td>x</td>
                        <td>đang chuyển hàng</td>
                        <td>
                            <button class="btn btn-primary">Edit</button>
                            <button class="btn btn-danger">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- /.table-responsive -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->

</div>
@endsection

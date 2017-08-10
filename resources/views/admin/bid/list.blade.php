@extends('admin.index')
@section('admin')
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">List Bid</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                    <tr>
                        <th class="text-center">Product</th>
                        <th class="text-center">Cost begin</th>
                        <th class="text-center">Cost sell</th>
                        <th class="text-center">Cost max current</th>
                        <th class="text-center">Time end</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="odd gradeX text-center">
                        <td>Trident</td>
                        <td>4$</td>
                        <td>4$</td>
                        <td>4$</td>
                        <td>10:10 10/10/2010</td>
                        <td>đang đấu giá</td>
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

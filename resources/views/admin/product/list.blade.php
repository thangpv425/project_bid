@extends('admin.index')
@section('admin')
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">List Product</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                    <tr>
                        <th class="text-center">Name</th>
                        <th class="text-center">Description</th>
                        <th class="text-center">Image</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="odd gradeX text-center">
                        <td>Trident</td>
                        <td>Internet Explorer 4.0</td>
                        <td>
                            <img src="http://dummyimage.com/800x600/4d494d/686a82.gif&text=placeholder+image" alt="placeholder+image" width="50px" height="50px">
                        </td>
                        <td>
                            <button class="btn btn-primary">Edit</button>
                            <button class="btn btn-danger">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
                  <!-- /.table-responsive -->
        </div>
        <div class="col-lg-2">
            <a href="{{route('admin-product-create')}}" class="btn btn-primary">Add product</a>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->

</div>
@endsection

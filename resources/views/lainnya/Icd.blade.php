@extends('layouts.main')

@section('menu')
	{!! $menus !!}
@endsection

@section('content')
<div class="card">
	<div class="card-header header-elements-inline">
		<h5 class="card-title">Data Tabel ICD</h5>
	</div>

	<div class="card-header header-elements-inline">
        @if(Auth::user()->id_role == "1")
            <button type="button" class="btn bg-success btn-labeled btn-labeled-left" data-toggle="modal" data-target="#add-modal"><b><i class="icon-reading"></i></b> Tambah ICD</button>
        @endif
    </div>

	<table id="icd-tables" class="table datatable-basic">
		<thead>
			<tr>
				<th>No</th>
                <th>Nama ICD </th>
                @if(Auth::user()->id_role == "1")
                    <th class="text-center">Actions</th>
                @endif
			</tr>
		</thead>
	</table>
</div>  
	<!--Modal show penyakit -->
<div id="add-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h6 class="modal-title">Form Tambah ICD</h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="col-xl-12">
                    <!-- Form -->
                    <div class="card-body">
                        <form id="addForm" name="addForm">
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Nama ICD : </label>
                                <div class="col-lg-9">
                                    <input name="nama_icd" type="text" class="form-control" placeholder="Nama ICD ">
                                </div>
                            </div>
                        </form>
                        <!-- /Form -->
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                <button type="button" class="btn bg-success add_icd">Save changes</button>
            </div>
        </div>
    </div>
</div>
<!--End Modal show penyakit-->

<!--Modal edit penyakit -->

<div id="edit-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h6 class="modal-title">Form Edit ICD</h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="col-xl-12">
                    <!-- Form -->
                    <div class="card-body">
                        <form id="editForm" name="editForm">
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Nama ICD</label>
                                <div class="col-lg-9">
                                    <input name="nama_icd" id="nama_icd" type="text" class="form-control" >
                                </div>
                            </div>
                        </form>
                        <!-- /Form -->
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                <button type="button" class="btn bg-success edit_icd">Save changes</button>
            </div>
        </div>
    </div>
</div>

<!--End Modal edit penyakit-->

<!--Modal delete -->
<div id="delete-modal" class="modal fade" tabindex="-2">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h6 class="modal-title">Delete Data</h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
    
            <div class="modal-body">
                <div class="col-xl-12">
                    <div class="card-body">
                        <p>Anda yakin ingin menghapus data ini?</p>
                    </div>
                </div>
            </div>
    
             <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">Batal</button>
                <button type="button" class="btn bg-danger delete_icd">Delete</button>
            </div>
        </div>
    </div>
</div>
 <!--End Modal delete-->

@endsection

@push('scripts')
<script>

    //add penyakit
    $(document).on('click','.add_icd', function(e){
        e.preventDefault();

        $.ajax({
            headers: {
               'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
            },
            url : "{{ route('icd.addIcd') }}",
            method : "post",
            data: {formData: JSON.parse(JSON.stringify($('#addForm').serializeArray())) },
            success : function(data){
                Swal.fire({
                    type: 'success',
                    title : 'ICD berhasil ditambah!',
                    text : 'ICD telah berhasil ditambahkan!',
                });
                $("#addForm")[0].reset();
                $("#add-modal").modal('hide');
                $('#icd-tables').DataTable().ajax.reload();
            }
        });
    });
    
    //edit penyakit baru
    $(document).on('click', '#editIcdBtn', function(){
            console.log("text")
            id = $(this).attr('data-id');
            $('.edit_icd').attr("id", id);
            $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
                },
                url: "{{ route('icd.editDataIcd') }}",
                method: "get",
                data: {id: id},
                success: function(data){
                    console.log(data);
                    $('#editForm #nama_icd').val(data.nama_icd);
                    $("#edit-modal").modal("show")
                }
            });
    
        });

    $(document).on('click', '.edit_icd', function(e){
        e.preventDefault();
         var id = $(this).attr("id");
        
         $.ajax({
            headers: {
               'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
            },
            url: "{{ route('icd.editIcd') }}",
            method: "post",
            data: {id: id, formData: JSON.parse(JSON.stringify($('#editForm').serializeArray())) },
            success: function(data){
                console.log(data)
               Swal.fire({
                  type: 'success',
                  title: 'Icd berhasil di ubah!',
                  text: 'Icd yang anda pilih telah diubah!',
               });
               $('#edit-modal').modal('hide');
               $('#icd-tables').DataTable().ajax.reload();
            }
         });
        
      });

    //delete penyakit
    $(document).on('click', '.delete-icd-data', function(){
       var id = $(this).attr("id");
       $('.delete_icd').attr("id", id);
    });

    $(document).on('click', '.delete_icd', function(){
        var id = $(this).attr("id"); 
        $.ajax({
           headers: {
              'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
           },
           url: "{{ route('icd.delete') }}",
           method: "GET",
           data: {id: id},
           success: function(){          
                Swal.fire({
                    type: 'success',
                    title: 'Berhasil dihapus!',
                    text: 'ICD yang anda pilih telah dihapus!',
                });
                $('#delete-modal').modal('hide');
                $('#icd-tables').DataTable().ajax.reload();
           }
        });
       
    });


    //GET ALL DATA
    $(function(){
        $('#icd-tables').DataTable({
        order: [[ 2, "asc" ]],
            prossessing: true,
            serverside: true,
            "bDestroy": true,
            @if(Auth::user()->id_role == "1")
               "columnDefs": [
                    { className: "text-center", "targets": [ 2 ] }
                ],
            @endif
            "columnDefs": [
                { className: "text-center", "targets": [ 1 ] }
            ],
            ajax: '{!! route('icd.dataJSON') !!}',
            columns: [
                { name: 'id', data: 'DT_RowIndex' },
                {
                    name: 'nama_icd',
                    data: 'nama_icd'
                },
                @if(Auth::user()->id_role == "1")
                {
                    name: 'action',
                    data: 'action',
                },
                @endif
            ]
        });
    });
        
</script>
@endpush


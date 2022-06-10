@extends('layout.app')

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="{{ asset('js/datepicker/daterangepicker.css') }}" rel="stylesheet">
    <style>
        .add-new {
            float: right !important;
        }

        .fa {
            font-size: 20px;
            margin-right: 8%;
            margin-left: 6%;
        }
    </style>
@stop

@section('content')

    <div class="row">
        <div class="col">
            <h3>Student List</h1>
        </div>
        <div class="col">
            <button type="button" class="btn btn-primary add-new" data-bs-toggle="modal" data-bs-target="#myModal"
                data-bs-whatever="">Create New</button>
        </div>
    </div>

    <table class="table table-bordered" id="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Reporting Teacher</th>
                <th>Actions</th>
            </tr>
        </thead>
    </table>


    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Student </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                {{ Form::open(['url' => '#', 'id' => 'form', 'class' => '', 'method' => 'POST']) }}
                <div class="modal-body">
                    {{ csrf_field() }}
                    {{ Form::hidden('id', null) }}
                    <div class="mb-3 form-group" id="name">
                        <label for="name" class="col-form-label">Name</label>
                        <input type="text" class="form-control" name="name">
                        <small class="help-block"></small>
                    </div>
                    <div class="mb-3 form-group" id="dob">
                        <label for="dob" class="col-form-label">Date of Birth</label>
                        <input class="form-control js-datepicker" type="" name="dob">
                        <i class="zmdi zmdi-calendar-note input-icon js-btn-calendar"></i>
                        <small class="help-block"></small>
                    </div>
                    <div class="mb-3 form-group" id="gender">
                        <label for="message-text" class="col-form-label">Gender</label>
                        <div class="custom-control-inline">
                            <input type="radio" id="male" name="gender" value="M" class="custom-control-input">
                            <label class="custom-control-label" for="male">Male</label>
                            <input type="radio" id="female" name="gender" value="F" class="custom-control-input">
                            <label class="custom-control-label" for="female">Female</label>
                        </div>
                        <small class="help-block"></small>
                    </div>
                    <div class="mb-3 form-group" id="reporting_teacher_id">
                        <label for="message-text" class="col-form-label">Reporting Teacher</label>
                        {{ Form::select('reporting_teacher_id', ['' => 'Please select'] + $teachers, null, ['class' => 'form-control']) }}
                        <small class="help-block"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="Submit" class="btn btn-primary">Create</button>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>

@stop

@section('js')

    <script src="{{ asset('js/datepicker/moment.min.js') }}"></script>
    <script src="{{ asset('js/datepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('js/global.js') }}"></script>

    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(function() {
            var table = $('#table');
            $.fn.dataTable.ext.errMode = 'throw';
            try {
                table = $('#table').DataTable({
                    ajax: {
                        "url": "{{ route('students.lists') }}",
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: '',
                            sortable: false
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: null,
                            orderable: false,
                            render: function(o) {
                                var actions = moment().diff(o.dob, 'years');
                                return actions;
                            }
                        },
                        {
                            data: 'gender',
                            name: 'gender'
                        },
                        {
                            data: 'reporting_teacher.name',
                            name: 'reporting_teacher.name'
                        },

                        {
                            data: null,
                            orderable: false,
                            render: function(o) {
                                var actions = "";
                                actions +=
                                    '<a href="#" title="Edit" class="edit fa fa-edit" data-id=' +
                                    o.id + '></a>'
                                actions +=
                                    '<a href="#" title="Delete" class="delete fa fa-trash" data-id=' +
                                    o.id + '></a>';
                                return actions;
                            },
                        }
                    ]
                });
            } catch (e) {
                console.log(e.stack);
            }
            // On modal click
            $('.add-new').click(function() {
                $("#form")[0].reset();
                $('#myModal input[name="id"]').val('');
                $('#form').find('.form-group').removeClass('has-error').find('.help-block').text('');
                $('#myModal .modal-title').text("Create New Student")
            });

            // Form submit
            $('#form').submit(function(e) {
                e.preventDefault();
                if ($('#form input[name="id"]').val()) {
                    var message = 'Data has been updated successfully';
                } else {
                    var message = 'Data has been created successfully';
                }
                formSubmit($('#form'), "{{ route('students.store') }}", table, e, message,null);
            });
            // Edit
            $("#table").on("click", ".edit", function(e) {
                id = $(this).data('id');
                var url = "{{ route('students.show', ':id') }}";
                var url = url.replace(':id', id);
                $('#form').find('.form-group').removeClass('has-error').find('.help-block').text('');
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        if (data) {
                            $(".add-new").trigger("click");
                            $('#form input[name="id"]').val(data.id);
                            $('#form input[name="name"]').val(data.name);
                            $('#form input[name="dob"]').val(data.dob);
                            $('#form select[name="reporting_teacher_id"]').val(data.reporting_teacher_id);
                            if(data.gender == 'M'){
                                $("#male").prop("checked", true);
                            }else{
                                $("#female").prop("checked", true);
                            }

                            $('#myModal .modal-title').text("Edit Student: " + data.name)
                        } else {
                            console.log(data);
                            swal("Oops", "Edit was unsuccessful", "warning");
                        }
                    },
                    error: function(xhr, textStatus, thrownError) {
                        console.log(xhr.status);
                        console.log(thrownError);
                        swal("Oops", "Something went wrong", "warning");
                    },
                    contentType: false,
                    processData: false,
                });
            });

            /// Delete
            $('#table').on('click', '.delete', function(e) {
                var id = $(this).data('id');
                var base_url = "{{ route('students.destroy', ':id') }}";
                var url = base_url.replace(':id', id);
                var message = 'Data has been deleted successfully';
                deleteRecord(url, table, message,null);
            });

        });
    </script>
@stop

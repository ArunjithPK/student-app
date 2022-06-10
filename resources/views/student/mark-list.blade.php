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
            <h3>Student Mark Lists</h1>
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
                @foreach ($subjects as $subject)
                    <th>{{$subject->name}}</th>
                @endforeach
                <th>Term</th>
                <th>Total Marks</th>
                <th>Created On</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($marklists as $key=>$list)
            <tr>
                <td> {{$key+1}}</td>
                <td> {{$list['student_name']}}</td>
                    @foreach ($subjects as $subject)
                        @if (isset($list['marks']) && isset($list['marks'][$subject->id]))
                            <td>{{$list['marks'][$subject->id]}}</th>
                        @else
                            <th>0</th>
                        @endif
                    @endforeach
                <td> {{$list['term']}}</td>
                <td> {{$list['total_marks']}}</td>
                <td> {{$list['created_at']}}</td>
                <td>
                    <a href="#" title="Edit" class="edit fa fa-edit" data-id='{{$list['id']}}'></a>
                    <a href="#" title="Delete" class="delete fa fa-trash" data-id='{{$list['id']}}'></a>
                </td>
            </tr>
            @endforeach

        </tbody>


    </table>


    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New  </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                {{ Form::open(['url' => '#', 'id' => 'form', 'class' => '', 'method' => 'POST']) }}
                <div class="modal-body">
                    {{ csrf_field() }}
                    {{ Form::hidden('id', null) }}
                    <div class="mb-3 form-group" id="student_id">
                        <label for="message-text" class="col-form-label">Student</label>
                        {{ Form::select('student_id', ['' => 'Please select'] + $students, null, ['class' => 'form-control']) }}
                        <small class="help-block"></small>
                    </div>

                    @foreach ($subjects as $subject)
                        <div class="mb-3 form-group" id="mark">
                            <label for="name" class="col-form-label">{{$subject->name}}</label>
                            <input type="number" max="50" required class="form-control"
                            name="marks[{{$subject->id}}]">
                            <small class="help-block"></small>
                        </div>
                    @endforeach
                    <div class="mb-3 form-group" id="term">
                        <label for="message-text" class="col-form-label">Term</label>
                        {{ Form::select('term', ['' => 'Please select','One'=>'One','Two'=>'Two'], null, ['class' => 'form-control']) }}
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
            var table = $('#table').DataTable();
            // On modal click
            $('.add-new').click(function() {
                $("#form")[0].reset();
                $('#myModal input[name="id"]').val('');
                $('#form').find('.form-group').removeClass('has-error').find('.help-block').text('');
                $('#myModal .modal-title').text("Create New Mark List")
            });

            // Form submit
            $('#form').submit(function(e) {
                e.preventDefault();
                if ($('#form input[name="id"]').val()) {
                    var message = 'Data has been updated successfully';
                } else {
                    var message = 'Data has been created successfully';
                }
                formSubmit($('#form'), "{{ route('marklists.store') }}", table, e, message,"{{ route('marklists.index') }}");
            });
            // Edit
            $("#table").on("click", ".edit", function(e) {
                id = $(this).data('id');
                var url = "{{ route('marklists.show', ':id') }}";
                var url = url.replace(':id', id);
                $('#form').find('.form-group').removeClass('has-error').find('.help-block').text('');
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        if (data) {
                            $(".add-new").trigger("click");
                            $('#form input[name="id"]').val(data.id);
                            $('#form select[name="student_id"]').val(data.student_id);
                            $('#form select[name="term"]').val(data.term);
                            if(data.student_marks){
                                $.each(data.student_marks, function (key, value) {
                                    let name = 'name="marks['+value.subject_id+']"';
                                    $('#form input['+name+']').val(value.mark);
                                });
                            }

                            $('#myModal .modal-title').text("Edit Mark List: ")

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
                var base_url = "{{ route('marklists.destroy', ':id') }}";
                var url = base_url.replace(':id', id);
                var message = 'Data has been deleted successfully';
                deleteRecord(url, table, message,"{{ route('marklists.index') }}");
            });

        });
    </script>
@stop

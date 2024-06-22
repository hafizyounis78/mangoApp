@extends('layouts.main')

@section('content')
    <div class="page-container">
    @include('includes.side_menu')
    <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <!-- BEGIN CONTENT BODY -->
            <div class="page-content">
                <!-- BEGIN PAGE BAR -->
            @include('includes.breadcrumb')
            <!-- END PAGE BAR --> <!-- BEGIN PAGE TITLE-->

                <!-- Main Content -->
                <div class="row" style="margin-top: 30px;">

                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif
                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption font-red-sunglo">
                                        <i class="icon-settings font-red-sunglo"></i>
                                        <span class="caption-subject bold uppercase">{{trans('users.add_users')}}</span>
                                    </div>

                                </div>
                                <div class="portlet-body form">
                                    <form action="{{ route('users.store') }}" enctype="multipart/form-data"
                                          method="post" role="form" autocomplete="off">
                                        {{csrf_field()}}
                                        <div class="form-body">
                                            <div class="form-group form-md-line-input">

                                                <input type="text" class="form-control" name="name"
                                                       value="{{ old('name') }}"
                                                       placeholder="{{trans('users.name_placeholder')}}" autocomplete="off" >
                                                <label for="form_control_1">{{trans('users.name')}}</label>
                                                <span class="help-block"></span>
                                            </div>


                                            <div class="form-group form-md-line-input">
                                                <input type="text" class="form-control" name="email"
                                                       value="{{ old('email') }}"
                                                       placeholder="{{trans('users.email_placeholder')}}" autocomplete="off" >
                                                <label for="form_control_1">{{trans('users.email')}}</label>
                                                <span class="help-block"></span>
                                            </div>

                                            <div class="form-group form-md-line-input">
                                                <input type="text" class="form-control" name="mobile"
                                                       value="{{ old('mobile') }}"
                                                       placeholder="{{trans('users.mobile_placeholder')}}" autocomplete="off" >
                                                <label for="form_control_1">{{trans('users.mobile')}}</label>
                                                <span class="help-block"></span>
                                            </div>

                                            <div class="form-group form-md-line-input">

                                                <input type="text" class="form-control"
                                                       placeholder="{{trans('users.password_placeholder')}}" name="password" id="" autocomplete="off" style="-webkit-text-security: disc;" >
                                                <label for="form_control_1">{{trans('users.password')}}</label>
                                                <span class="help-block"></span>
                                            </div>

                                            <div class="form-group form-md-radios">
                                                <label for="form_control_1">{{trans('users.type_user')}}</label>
                                                <div class="md-radio-inline">
                                                    <div class="md-radio">
                                                        <input type="radio" id="checkbox2_8" name="user_type" value="1" class="md-radiobtn">
                                                        <label for="checkbox2_8">
                                                            <span></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span> {{trans('users.user_normal')}}</label>
                                                    </div>
                                                    <div class="md-radio">
                                                        <input type="radio" id="checkbox2_9" name="user_type" value="2" class="md-radiobtn">
                                                        <label for="checkbox2_9">
                                                            <span></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span> {{trans('users.user_driver')}} </label>
                                                    </div>

                                                </div>
                                            </div>

                                            {{--
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-preview thumbnail" data-trigger="fileinput"
                                                     style="width: 200px; height: 150px; line-height: 150px;">
                                                    <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image">
                                                </div>
                                                <div>
                                                                <span class="btn red btn-outline btn-file">
                                                                    <span class="fileinput-new"> Select image </span>
                                                                    <span class="fileinput-exists"> Change </span>
                                                                    <input type="hidden" value="" name="..."><input
                                                                            type="file" name="image"> </span>
                                                    <a href="javascript:;" class="btn red fileinput-exists"
                                                       data-dismiss="fileinput"> Remove </a>
                                                </div>
                                            </div>
                                            --}}
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <img id="preview_image" src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" width="200" height="150">
                                                    <img id="preview_image2" class="hidden" src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image"  width="200" height="150">

                                                </div>
                                            </div>

                                            <div class="row" style="margin-top: 10px;">
                                                <div class="col-sm-12">
                                                    <span id="field2_area" hidden><input type="file" id="field2"/></span>
                                                    <span class="btn default btn-file" id="field1_area">
                                                                    <span id="select_image" > {{trans('users.select_image')}} </span>
                                                                    <span id="change_image" class="hidden"> {{trans('users.change')}} </span>
                                                                    <input type="file" id="field1" name="image"> </span>
                                                    <a href="javascript:;" id="remove_image" class="btn red hidden">
                                                        {{trans('users.remove')}} </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions noborder">
                                            <input type="hidden" id="old_section" value="{{ old('section_id') }}">
                                            <input type="submit" class="btn btn-success" value="{{trans('users.add')}}">
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <!-- END SAMPLE FORM PORTLET-->
            </div>
            <!-- /Main Content -->
        </div>
        <!-- END CONTENT BODY -->
    </div>
    <!-- END CONTENT -->
    </div>
    <!-- END CONTAINER -->
    @include('includes/footer')
@stop

@push('css')
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="{{url('')}}/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->
@endpush

@push('js')
    <script>
        $(document).ready(function () {
            function readURL(input) {

                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#preview_image2').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);

                }
            }

            if ($('#unit_id').val() != -1) {
                $.ajax({
                    url: "{{ url('getSections') }}",
                    data: {
                        unit: $('#unit_id').val()
                    },
                    type: 'GET',
                    dataType: 'json'
                }).done(function (response) {
                    var option = $('<option />');
                    option.attr('value', -1).text('');
                    $('#section_id').append(option);
                    $(response.data).each(function () {
                        var option = $('<option />');
                        option.attr('value', this.unit_section_id).text(this.section_title);
                        $('#section_id').append(option);
                        var section = $("#old_section").val();
                        $('#section_id option[value="' + section + '"]').prop("selected", true);
                    });
                });
            }
            $('#unit_id').on('change', function () {
                $('#section_id').html('');
                var $this = $(this);
                if ($this.val() != -1) {
                    $.ajax({
                        url: "{{ url('getSections') }}",
                        data: {
                            unit: $this.val()
                        },
                        type: 'GET',
                        dataType: 'json'
                    }).done(function (response) {
                        var option = $('<option />');
                        option.attr('value', -1).text('');
                        $('#section_id').append(option);
                        $(response.data).each(function () {
                            var option = $('<option />');
                            option.attr('value', this.unit_section_id).text(this.section_title);
                            $('#section_id').append(option);
                        });
                    });
                } else {
                    $('#section_id').html('');
                }
            });


            $('#field1').change(function(){

                if($(this).val() != "") {
                    var clone = $(this).clone();
                    var clone2 = this;
                    clone.attr('id', 'field2');
                    clone.attr('name', 'image');
                    $('#field2_area').html(clone);
                    readURL(clone2);
                    $('#preview_image').addClass('hidden');
                    $('#preview_image2').removeClass('hidden');
                    $('#change_image').addClass('hidden');
                    $('#select_image').removeClass('hidden');
                }
                if($('#field1').val() != "") {
                    $('#remove_image').removeClass('hidden');
                    $('#change_image').removeClass('hidden');
                    $('#select_image').addClass('hidden');
                }

            });

            $('#remove_image').click(function() {
                $('#field2').val('');
                $('#preview_image').removeClass('hidden');
                $('#preview_image2').addClass('hidden');
            });
        });
    </script>
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="{{url('')}}/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
@endpush
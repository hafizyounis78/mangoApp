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
            <!-- END PAGE BAR -->
                <!-- BEGIN PAGE TITLE-->
                <div class="row" style="margin-top: 30px;">
                    <!-- Main Content -->

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
                                        <span class="caption-subject bold uppercase">{{trans('users.edit_user')}}</span>
                                    </div>

                                </div>
                                <div class="portlet-body form">
                                    <form action="{{ route('admin.update', $user->id) }}" enctype="multipart/form-data"
                                          method="post" role="form">
                                        {{csrf_field()}}
                                        {{ method_field('PATCH') }}
                                        <div class="form-body">
                                            <div class="form-group form-md-line-input">

                                                <input type="text" class="form-control" name="name" id=""
                                                       value="{{ $user->name }}"
                                                       placeholder="{{trans('users.name+placeholder')}}">
                                                <label for="form_control_1">{{trans('users.name')}}</label>
                                                <span class="help-block"></span>
                                            </div>

                                            <div class="form-group form-md-line-input">
                                                <input type="text" class="form-control" name="email" id=""
                                                       value="{{ $user->email }}" placeholder="{{trans('users.email_placeholder')}}">
                                                <label for="form_control_1">{{trans('users.email')}}</label>
                                                <span class="help-block"></span>
                                            </div>

                                            <div class="form-group form-md-line-input">
                                                <input type="text" class="form-control" name="mobile"
                                                       value="{{ $user->mobile }}"
                                                       placeholder="{{trans('users.mobile_placeholder')}}" autocomplete="off" >
                                                <label for="form_control_1">{{trans('users.mobile')}}</label>
                                                <span class="help-block"></span>
                                            </div>

                                            <div class="form-group form-md-radios">
                                                <label for="form_control_1">{{trans('users.type_user')}}</label>
                                                <div class="md-radio-inline">
                                                    <div class="md-radio">
                                                        <input type="radio" id="checkbox2_8" {{$user->type == 1 ?'checked' :''}} name="user_type" value="1" class="md-radiobtn">
                                                        <label for="checkbox2_8">
                                                            <span></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span> {{trans('users.user_normal')}}</label>
                                                    </div>
                                                    <div class="md-radio">
                                                        <input type="radio" id="checkbox2_9" {{$user->type == 2 ?'checked' :''}} name="user_type" value="2" class="md-radiobtn">
                                                        <label for="checkbox2_9">
                                                            <span></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span> {{trans('users.user_driver')}} </label>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-new thumbnail"
                                                     style="width: 200px; height: 150px;">
                                                    <img src="{{filter_var($user->image, FILTER_VALIDATE_URL) ?$user->image :url('storage').$user->image}}"
                                                         alt=""></div>
                                                <div class="fileinput-preview fileinput-exists thumbnail"
                                                     style="max-width: 200px; max-height: 150px; line-height: 10px;"></div>
                                                <div>
                                                                <span class="btn default btn-file">
                                                                    <span class="fileinput-new"> {{trans('users.select_image')}} </span>
                                                                    <span class="fileinput-exists"> {{trans('users.change')}}</span>
                                                                    <input type="hidden" value="" name="imafe"><input
                                                                            type="file" name="image"> </span>
                                                    <a href="javascript:;" class="btn red fileinput-exists"
                                                       data-dismiss="fileinput"> {{trans('users.remove')}} </a>
                                                </div>
                                            </div>

                                            {{--
                                               @if($user->username != "admin")
                                                   <div class="form-group form-md-checkboxes">
                                                       <label>Roles</label>
                                                       <div class="md-checkbox-list">
                                                           @foreach($roles as $role)
                                                               <div class="md-checkbox">
                                                                   <input type="checkbox" id="checkbox{{$role->id}}"
                                                                          {{in_array($role->id, $user_roles) ? "checked" : ""}} name="role[]"
                                                                          value="{{ $role->id }}" class="md-check">
                                                                   <label for="checkbox{{$role->id}}">
                                                                       <span></span>
                                                                       <span class="check"></span>
                                                                       <span class="box"></span> {{$role->display_name}}
                                                                   </label>
                                                               </div>
                                                           @endforeach

                                                       </div>
                                                   </div>
                                               @endif
   --}}
                                        </div>
                                        <div class="form-actions noborder">
                                            <input type="submit" class="btn btn-success" value="{{trans('users.save_change')}}">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- END SAMPLE FORM PORTLET-->
                </div>
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
    <link href="{{url('')}}/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet"
          type="text/css"/>
    <!-- END PAGE LEVEL PLUGINS -->
@endpush

@push('js')
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="{{url('')}}/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js"
            type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <script>
        $(document).ready(function () {
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
        });
    </script>

@endpush
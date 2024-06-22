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
                        <span class="caption-subject bold uppercase">{{$type}}</span>
                    </div>

                </div>


                <div class="portlet-body">
                    <div class="alert alert-danger display-hide">
                        <button class="close" data-close="alert"></button>
                        {{trans('category.error_validation')}}
                    </div>

                    <div class="tabbable-custom ">
                        <ul class="nav nav-tabs ">

                            @foreach($languages as $i=>$lang)
                                <li class="{{$i == 0 ? "active": ""}}">
                                    <a href="#tab{{$i}}" data-toggle="tab">{{$lang->lng_name}}</a>
                                </li>
                            @endforeach

                        </ul>

                        <div class="tab-content">

                            <input type="hidden" class="count_lng" value="{{$lang->count()}}">
                            @foreach($languages as $i=>$lang)
                                <input type="hidden" class="lang_{{$i}}" value="{{$lang->lng_id}}">
                                <div class="tab-pane {{$i == 0 ?'active' :''}}" id="tab{{$i}}">
                                    <div class="portlet-body form" style="margin-right: 10px;">
                                        <div class="form-body">

                                            @if($i == 0)
                                                <div class="form-group form-md-line-input">

                                                    <input type="text" class="form-control name_{{$lang->lng_id}}"
                                                           name="name_{{$lang->lng_id}}"
                                                           value="{{ $edit ? $arr_trn_name['name_'.$lang->lng_id] : old('name_'.$lang->lng_id) }}"
                                                           placeholder="{{trans('category.name')}}">

                                                    <label for="form_control_1">Name</label>
                                                    <span class="help-block"></span>
                                                </div>
                                                {{--
                                                  <div class="form-group form-md-line-input">
                                                      <select class="form-control parent" name="parent"
                                                              id="form_control_1">
                                                          <option value="-1">No parent</option>
                                                          @foreach($categories as $cat)
                                                              <option value="{{$cat->cat_id}}" {{$edit ? $cat->cat_id == $category->cat_parent ?'selected' : '' : ''}}>{{$cat->cat_name}}</option>
                                                          @endforeach
                                                      </select>
                                                      <label for="form_control_1">Parent</label>
                                                  </div>
                                              --}}

                                            @else
                                                <div class="form-group form-md-line-input">

                                                    <input type="text" class="form-control name_{{$lang->lng_id}}"
                                                           name="name_{{$lang->lng_id}}"
                                                           value="{{ $edit ?  $arr_trn_name['name_'.$lang->lng_id] : old('name_'.$lang->lng_id) }}"
                                                           placeholder="{{trans('category.name')}}">
                                                    <label for="form_control_1">Name</label>
                                                    <span class="help-block"></span>
                                                </div>
                                            @endif


                                        </div>



                                    </div>

                                </div>
                            @endforeach


                        </div>
                        <div class="form-group" style="margin-top: 10px;">
                            <label for="single-prepend-text" class="control-label">Parent</label>
                            <div class="input-group select2-bootstrap-prepend">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-default" type="button" data-select2-open="single-prepend-text">
                                                        <span class="glyphicon glyphicon-search"></span>
                                                    </button>
                                                </span>
                                <select id="single-prepend-text" name="parent" class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                    <option value="-1">No parent</option>
                                    @foreach($categories as $cat)
                                        <option value="{{$cat->cat_id}}" {{$edit ? $cat->cat_id == $category->cat_parent ?'selected' : '' : ''}}>{{$cat->cat_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <img id="preview_image"
                                     src="{{$edit ? url('storage').$category->cat_image : 'http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image' }}"
                                     width="200" height="150">
                                <img id="preview_image2" class="hidden"
                                     src="{{$edit ? url('storage').$category->cat_image : 'http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image' }}"
                                     width="200" height="150">

                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px;">
                            <div class="col-sm-12">
                                                        <span id="field2_area" hidden><input type="file" name="image"
                                                                                             id="field2"/></span>
                                <span class="btn default btn-file" id="field1_area">
                                                                    <span id="select_image">  {{trans('category.select_image')}} </span>
                                                                    <span id="change_image"
                                                                          class="hidden">  {{trans('category.change')}} </span>
                                                                    <input type="file" id="field1"> </span>
                                <a href="javascript:;" id="remove_image" class="btn red hidden">
                                    {{trans('category.remove')}} </a>
                            </div>
                        </div>
                        <div class="form-actions noborder" style="margin-top: 20px;">
                            <input type="hidden" id="old_section" value="{{ old('section_id') }}">
                            <input type="submit" class="btn btn-success addWithUpdate"
                                   value="{{$edit ? trans('category.save_change') : trans('category.add')}}">
                        </div>

                    </div>


                </div>


            </div>

        </div>
    </div>

</div>

@push('css')
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="{{url('')}}/assets2/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet"
          type="text/css"/>
    <!-- END PAGE LEVEL PLUGINS -->

    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="{{url('')}}/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="{{url('')}}/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->


    <style>

    </style>
@endpush
@push('js')
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="{{url('')}}/assets2/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js"
            type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->

    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="{{url('')}}/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"
            type="text/javascript"></script>
    <script src="{{url('')}}/assets/global/plugins/jquery-validation/js/additional-methods.min.js"
            type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="{{url('')}}/assets/pages/scripts/form-validation-md.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->


    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="{{url('')}}/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="{{url('')}}/assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->
    <script>
        $(document).ready(function () {
            var file_path = "";


            /* add validation */

            var form1 = $('#form_sample_1');
            $('.addWithUpdate').click(function () {

                var count_lng = $('.count_lng').val();
                var lang = "";
                for (var i = 0; i < count_lng; i++) {
                    lang = $('.lang_' + i).val();
                    $('.name_' + lang).rules('add', {
                        required: true
                    });

                }

                if (form1.valid()) {

                }


            });


            function readURL(input) {

                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#preview_image2').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);

                }
            }

            $('#field1').change(function () {

                if ($(this).val() != "") {
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
                if ($('#field1').val() != "") {
                    $('#remove_image').removeClass('hidden');
                    $('#change_image').removeClass('hidden');
                    $('#select_image').addClass('hidden');
                }

            });
            $('#remove_image').click(function () {
                $('#field2').val('');
                $('#preview_image').removeClass('hidden');
                $('#preview_image2').addClass('hidden');
            });


        });

    </script>
@endpush

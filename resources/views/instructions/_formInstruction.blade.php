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


                                                <div class="form-group form-md-line-input">

                                                    <input type="text" class="form-control name_{{$lang->lng_id}}"
                                                           name="name_{{$lang->lng_id}}"
                                                           value="{{ $edit ? $arr_trn_name['name_'.$lang->lng_id] : old('name_'.$lang->lng_id) }}"
                                                           placeholder="{{trans('instruction.title')}}">

                                                    <label for="form_control_1">{{trans('instruction.title')}}</label>
                                                    <span class="help-block"></span>
                                                </div>
                                                <div class="form-group">
                                                    <label style=" color: #888">{{trans('instruction.description')}}</label>
                                                    <textarea name="desc_{{$lang->lng_id}}" class="form-control desc_{{$lang->lng_id}}" rows="3">{{ $edit ? $arr_trn_desc['desc_'.$lang->lng_id] : old('desc_'.$lang->lng_id) }}</textarea>
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




                                        </div>



                                    </div>

                                </div>
                            @endforeach


                        </div>


                        <div class="row" style="margin-top: 15px;">
                            <div class="col-sm-4">
                                <img id="preview_image"
                                     src="{{$edit ? url('storage').$instruction->image : 'http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image' }}"
                                     width="200" height="150">
                                <img id="preview_image2" class="hidden"
                                     src="{{$edit ? url('storage').$instruction->image : 'http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image' }}"
                                     width="200" height="150">

                            </div>
                            <div class="col-sm-3">
                                <div class="form-group form-md-line-input">

                                    <input type="text" class="form-control orderBy"
                                           name="orderBy"
                                           value="{{$edit ?$instruction->orderBy :old('orderBy')}}"
                                           placeholder="">

                                    <label for="form_control_1">الترتيب</label>
                                    <span class="help-block"></span>
                                </div>
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
    <link href="{{url('')}}/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet"
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
    <script src="{{url('')}}/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js"
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
            $('.name_1').rules('add', {
                required: true
            });

            $('.name_2').rules('add', {
                required: true
            });

            $('.desc_1').rules('add', {
                required: true
            });
            $('.desc_2').rules('add', {
                required: true
            });

            $('.orderBy').rules('add', {
                required: true ,
                number:true
            });

            var form1 = $('#form_sample_1');
            $('.addWithUpdate').click(function () {

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

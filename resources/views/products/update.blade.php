<meta name="csrf-token" content="<?php echo csrf_token() ?>">
<?php
/*function get_image_mime_type($image_path)
{
    $mimes = array(
        IMAGETYPE_GIF => "image/gif",
        IMAGETYPE_JPEG => "image/jpg",
        IMAGETYPE_PNG => "image/png",
        IMAGETYPE_SWF => "image/swf",
        IMAGETYPE_PSD => "image/psd",
        IMAGETYPE_BMP => "image/bmp",
        IMAGETYPE_TIFF_II => "image/tiff",
        IMAGETYPE_TIFF_MM => "image/tiff",
        IMAGETYPE_JPC => "image/jpc",
        IMAGETYPE_JP2 => "image/jp2",
        IMAGETYPE_JPX => "image/jpx",
        IMAGETYPE_JB2 => "image/jb2",
        IMAGETYPE_SWC => "image/swc",
        IMAGETYPE_IFF => "image/iff",
        IMAGETYPE_WBMP => "image/wbmp",
        IMAGETYPE_XBM => "image/xbm",
        IMAGETYPE_ICO => "image/ico");

    if (($image_type = exif_imagetype($image_path))
        && (array_key_exists($image_type, $mimes))) {
        return $mimes[$image_type];
    } else {
        return FALSE;
    }
}*/

/*$prd_id = $prd_image = $prd_price = $prd_isVariable = $cat_id = NULL;
$prd_id = null;
$prd_image = null;
$prd_price = null;
$prd_isVariable = -1;
$cat_id = null;
$prd_minQuantity = 0;
$prd_maxQuantity = 0;
$prd_unit = 0;
$prd_unitValue = 0;
$ptr_name = '';
$ptr_description = '';*/
$ptr_name = $ptr_description = '';

$prd_id = $product->prd_id;

$prd_image = $product->prd_image;
$prd_price = $product->prd_price;
$prd_isVariable = $product->prd_isVariable;
$cat_id = $product->cat_id;
$prd_minQuantity = $product->prd_minQuantity;
$prd_maxQuantity = $product->prd_maxQuantity;
$prd_unit = $product->prd_unit;
$prd_unitValue = $product->prd_unitValue;
$prd_barcode = $product->prd_barcode;
$translations = $product->translations;
$translations = indexedArrayOfEloquent($translations, 'lng_id');

// dd($translations);
// foreach ($)

?>
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


                            <div class="portlet-title">
                                <div class="caption font-red-sunglo">
                                    <i class="icon-settings font-red-sunglo"></i>
                                    <span class="caption-subject bold uppercase">تعديل منتج</span>
                                </div>
                            </div>
                            <div class="alert alert-success form1-success  display-hide">
                                تمت العملية بنجاح
                            </div>

                            <div class="alert alert-danger form1-error  display-hide">
                                لم تتم العملية بنجاح

                            </div>

                            <div class="portlet-body">
                                {!! Form::open(array('url' => route('saveProduct2'), 'method' => 'post', 'files' => true, 'id' => 'product_form')) !!}

                                <input type="hidden" class="prd_id" name="prd_id"
                                       value="<?php echo $product->prd_id; ?>">

                                <div class="row form-group">
                                    <div class=" col-md-6">
                                        {!! Form::label('ptr_name_en', 'اسم المنتج بالانجليزي', array('class' => 'control-label')) !!}
                                        {!! Form::text('ptr_name_en', $translations[1]->ptr_name, array('autocomplete' => 'off', 'id' => 'ptr_name_en', 'class' => 'form-control')) !!}
                                    </div>
                                    <div class=" col-md-6">
                                        {!! Form::label('ptr_name_ar','اسم المنتج بالعربي', array('class' => 'control-label')) !!}
                                        {!! Form::text('ptr_name_ar',$translations[2]->ptr_name, array('autocomplete' => 'off', 'id' => 'ptr_name_ar', 'class' => 'form-control')) !!}
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class=" col-md-6">
                                        {!! Form::label('ptr_description_en','وصف المنتج بالانجليزي', array('class' => 'control-label ')) !!}
                                        {!! Form::textarea('ptr_description_en', $translations[1]->ptr_description, array('autocomplete' => 'off', 'id' => 'ptr_description_en', 'class' => 'form-control', 'rows' => 3)) !!}
                                    </div>

                                    <div class=" col-md-6">
                                        {!! Form::label('ptr_description_ar', 'وصف المنتج بالعربي', array('class' => 'control-label')) !!}
                                        {!! Form::textarea('ptr_description_ar', $translations[2]->ptr_description, array('autocomplete' => 'off', 'id' => 'ptr_description_ar', 'class' => 'form-control', 'rows' => 3)) !!}
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class=" col-md-4">
                                        {!! Form::label('prd_price', trans('products.price'), array('class' => 'control-label')) !!}
                                        {!! Form::number('prd_price', $prd_price, array('autocomplete' => 'off', 'id' => 'prd_price', 'class' => 'form-control','min'=>1)) !!}
                                    </div>

                                <!-- <div class=" col-md-4">
                                        {!! Form::label('cat_id', trans('products.category'), array('class' => 'control-label')) !!}
                                {!! Form::select('cat_id', $categories, $cat_id, array('class' => 'form-control custList')) !!}
                                        </div>
-->
                                    <div class=" col-md-4">
                                        {!! Form::label('prd_price', trans('products.category'), array('class' => 'control-label')) !!}
                                        <select id="single-prepend-text" name="cat_id"
                                                class="form-control select2 select2-hidden-accessible" tabindex="-1"
                                                aria-hidden="true">

                                            <?php
                                            $selected = '';
                                            ?>
                                            @foreach($categories as $cat)
                                                <?php if ($cat_id == $cat->cat_id)
                                                    $selected = 'selected="selected"';
                                                else
                                                    $selected = '';
                                                ?>
                                                <option value="{{$cat->cat_id}}" {{$selected}}>{{$cat->cat_name}}</option>
                                            @endforeach


                                        </select>
                                    </div>
                                    <div class=" col-md-4 p-t-25">
                                        <label for="cat_id" class="control-label">{{ trans('products.is_variable') }}
                                            {!! Form::checkbox('prd_isVariable', 1, ($prd_isVariable)) !!}
                                        </label>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class=" col-md-4">
                                        {!! Form::label('prd_minQuantity', trans('products.prd_minQuantity'), array('class' => 'control-label')) !!}
                                        {!! Form::number('prd_minQuantity', $prd_minQuantity, array('autocomplete' => 'off', 'id' => 'prd_minQuantity', 'class' => 'form-control','min'=>1)) !!}
                                    </div>
                                    <div class=" col-md-4">
                                        {!! Form::label('prd_maxQuantity', trans('products.prd_maxQuantity'), array('class' => 'control-label')) !!}
                                        {!! Form::number('prd_maxQuantity', $prd_maxQuantity, array('autocomplete' => 'off', 'id' => 'prd_maxQuantity', 'class' => 'form-control','min'=>1)) !!}
                                    </div>

                                </div>
                                <div class="row form-group">
                                    <div class=" col-md-4">
                                        {!! Form::label('prd_unit', 'وحدة القياس', array('class' => 'control-label')) !!}
                                        {!! Form::select('prd_unit', $units, $prd_unit, array('class' => 'form-control custList')) !!}
                                    </div>

                                    <div class=" col-md-4">
                                        {!! Form::label('prd_unitValue', trans('products.prd_unitValue'), array('class' => 'control-label')) !!}
                                        {!! Form::number('prd_unitValue', $prd_unitValue, array('autocomplete' => 'off', 'id' => 'prd_unitValue', 'class' => 'form-control','min'=>1)) !!}
                                    </div>
                                    <div class=" col-md-4">
                                        {!! Form::label('prd_barcode','باركود', array('class' => 'control-label')) !!}
                                        {!! Form::text('prd_barcode', $prd_barcode, array('autocomplete' => 'off', 'id' => 'prd_barcode', 'class' => 'form-control')) !!}
                                    </div>
                                </div>


                                <div class="row form-group">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <?php
                                            // A few settings
                                            $img_file = url('public/storage/product/img/') . '/' . $product->prd_image;
                                            // dd($img_file);

                                            // Read image path, convert to base64 encoding
                                            $imgData = base64_encode(file_get_contents($img_file));

                                            //dd($imgData);
                                            // Format the image SRC:  data:{mime};base64,{data};
                                            //$src = 'data: '.mime_content_type($img_file).';base64,'.$imgData;
                                            ?>

                                            <div class="col-sm-5">
                                                <img id="preview_image"
                                                     src="{{$product ? url('public/storage/product/img/').'/'.$product->prd_image : 'http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image' }}"
                                                     width="200" height="150">
                                                <img id="preview_image2" class="hidden"
                                                     src="{{$product ? url('public/storage/product/img/').'/'.$product->prd_image : 'http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image' }}"
                                                     width="200" height="150">

                                            </div>


                                        </div>

                                        <div class="row" style="margin-top: 10px;">
                                            <div class="col-sm-12">
                                                <span id="field2_area" hidden>
                                                    <input type="file" id="field2"
                                                           value="{{$product->prd_image ?  url('public/storage/product/img/').'/'.$product->prd_image:''}}"></span>
                                                <span class="btn default btn-file" id="field1_area">
                                                    <span id="select_image" class="hidden"> تغيير الصورة </span>
                                                       <span id="change_image"
                                                             class=""> {{trans('users.change')}} </span>
                                                    <input type="file" id="prd_image" name="prd_image"
                                                           value="{{$product->prd_image ?  url('public/storage/product/img/').'/'.$product->prd_image:''}}"> </span>
                                                {{--<a href="javascript:;" id="remove_image" class="btn red">--}}
                                                {{--{{trans('users.remove')}} </a>--}}
                                            </div>
                                        </div>
                                        {{--  {!! Form::label('prd_image', trans('products.image'), array('class' => 'control-label')) !!}
                                          {!! Form::file('prd_image') !!}
                                          {!! Form::hidden('prd_image_value', $prd_image) !!}

                                          @if(!empty($prd_image))
                                              <div class="image-preview-wrapper">
                                                  <div class="image-preview">
                                                      <img src="{{ url('storage/product/img/' . $prd_image) }}">
                                                  </div>

                                                  <div class="image-preview-actions">
                                                      <span class="closer"><i class="fa fa-times"></i></span>
                                                      <span class="preview">
                                                          <a href="{{ url('storage/product/img/' . $prd_image) }}"
                                                             data-lightbox="image-1"><i class="fa fa-eye"></i></a>
                                                      </span>
                                                  </div>
                                              </div>
                                          @endif--}}
                                    </div>
                                </div>


                                <div class="form-actions noborder">
                                    {!! Form::submit(trans('products.save_product'), array('class' => 'btn btn-success')) !!}
                                </div>
                                {!! Form::close() !!}
                                <div class="portfolio-content portfolio-1">

                                    <div id="js-grid-juicy-projects" class="cbp">
                                        @if(isset($gallery) && $gallery!='')
                                            @for($i=0;$i<count($gallery);$i++)

                                                <div class="cbp-item graphic logos">
                                                    <div class="cbp-caption" style="alignment: center !important;">
                                                        <div class="cbp-caption-defaultWrap">
                                                            <img src="{{url('')}}/public/storage/product/img/{{$gallery[$i]}}"
                                                                 alt="" style="width: 50% !important;"></div>
                                                        <div class="cbp-caption-activeWrap"
                                                             style="width: 50% !important;">
                                                            <div class="cbp-l-caption-alignCenter">
                                                                <div class="cbp-l-caption-body">
                                                                    <button onclick="delImge('{{$gallery[$i]}}',$(this));"
                                                                            class=" cbp-l-caption-buttonLeft btn red uppercase btn red uppercase">
                                                                        delete
                                                                    </button>
                                                                    {{--<a href="{{url('')}}/assets/global/img/portfolio/1200x900/57.jpg" class="cbp-lightbox cbp-l-caption-buttonRight btn red uppercase btn red uppercase" data-title="Dashboard<br>by Paul Flavius Nechita">view larger</a>--}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- <div class="cbp-l-grid-projects-title uppercase text-center uppercase text-center">Dashboard</div>
                                                     <div class="cbp-l-grid-projects-desc uppercase text-center uppercase text-center">Web Design / Graphic</div>--}}
                                                </div>
                                            @endfor
                                        @endif
                                        {{--  <div class="cbp-item web-design logos">
                                              <div class="cbp-caption">
                                                  <div class="cbp-caption-defaultWrap">
                                                      <img src="{{url('')}}/assets/global/img/portfolio/600x600/05.jpg" alt=""> </div>
                                                  <div class="cbp-caption-activeWrap">
                                                      <div class="cbp-l-caption-alignCenter">
                                                          <div class="cbp-l-caption-body">
                                                              <a href="{{url('')}}/assets/global/plugins/cubeportfolio/ajax/project2.html" class="cbp-singlePage cbp-l-caption-buttonLeft btn red uppercase btn red uppercase" rel="nofollow">delete</a>
                                                              --}}{{--<a href="{{url('')}}/assets/global/img/portfolio/1200x900/50.jpg" class="cbp-lightbox cbp-l-caption-buttonRight btn red uppercase btn red uppercase" data-title="World Clock Widget<br>by Paul Flavius Nechita">view larger</a>--}}{{--
                                                          </div>
                                                      </div>
                                                  </div>
                                              </div>
                                            --}}{{--  <div class="cbp-l-grid-projects-title uppercase text-center">World Clock Widget</div>
                                              <div class="cbp-l-grid-projects-desc uppercase text-center">Logo / Web Design</div>--}}{{--
                                          </div>
                                          <div class="cbp-item graphic logos">
                                              <div class="cbp-caption">
                                                  <div class="cbp-caption-defaultWrap">
                                                      <img src="{{url('')}}/assets/global/img/portfolio/600x600/16.jpg" alt=""> </div>
                                                  <div class="cbp-caption-activeWrap">
                                                      <div class="cbp-l-caption-alignCenter">
                                                          <div class="cbp-l-caption-body">
                                                              <a href="{{url('')}}/assets/global/plugins/cubeportfolio/ajax/project1.html" class="cbp-singlePage cbp-l-caption-buttonLeft btn red uppercase btn red uppercase" rel="nofollow">delete</a>
                                                              --}}{{--<a href="http://vimeo.com/14912890" class="cbp-lightbox cbp-l-caption-buttonRight btn red uppercase btn red uppercase" data-title="To-Do Dashboard<br>by Tiberiu Neamu">view video</a>--}}{{--
                                                          </div>
                                                      </div>
                                                  </div>
                                              </div>
                                            --}}{{--  <div class="cbp-l-grid-projects-title uppercase text-center">To-Do Dashboard</div>
                                              <div class="cbp-l-grid-projects-desc uppercase text-center">Graphic / Logo</div>--}}{{--
                                          </div>--}}

                                    </div>
                                    {{--<div id="js-loadMore-juicy-projects" class="cbp-l-loadMore-button">
                                        <a href="../assets/global/plugins/cubeportfolio/ajax/loadMore.html" class="cbp-l-loadMore-link btn grey-mint btn-outline" rel="nofollow">
                                            <span class="cbp-l-loadMore-defaultText">LOAD MORE</span>
                                            <span class="cbp-l-loadMore-loadingText">LOADING...</span>
                                            <span class="cbp-l-loadMore-noMoreLoading">NO MORE WORKS</span>
                                        </a>
                                    </div>--}}
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel panel-success">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">تحميل صور المنتج</h3>
                                            </div>
                                            <div class="panel-body">
                                                <form id="fileupload" action="{{url('product/saveGallery')}}"
                                                      method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" class="prd_id" name="prd_id"
                                                           value="{{$prd_id}}">
                                                    <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
                                                    <div class="row fileupload-buttonbar">
                                                        <div class="col-lg-7">
                                                            <!-- The fileinput-button span is used to style the file input field as button -->
                                                            <span class="btn green fileinput-button">
                                                <i class="fa fa-plus"></i>
                                                <span> Add files... </span>
                                                <input type="file" name="prd_gallery" multiple=""> </span>
                                                            <button type="submit" class="btn blue start">
                                                                <i class="fa fa-upload"></i>
                                                                <span> Start upload </span>
                                                            </button>
                                                            <button type="reset" class="btn warning cancel">
                                                                <i class="fa fa-ban-circle"></i>
                                                                <span> Cancel upload </span>
                                                            </button>
                                                            <button type="button" class="btn red delete">
                                                                <i class="fa fa-trash"></i>
                                                                <span> Delete </span>
                                                            </button>
                                                            <input type="checkbox" class="toggle">
                                                            <!-- The global file processing state -->
                                                            <span class="fileupload-process"> </span>
                                                        </div>
                                                        <!-- The global progress information -->
                                                        <div class="col-lg-5 fileupload-progress fade">
                                                            <!-- The global progress bar -->
                                                            <div class="progress progress-striped active"
                                                                 role="progressbar" aria-valuemin="0"
                                                                 aria-valuemax="100">
                                                                <div class="progress-bar progress-bar-success"
                                                                     style="width:0%;"></div>
                                                            </div>
                                                            <!-- The extended global progress information -->
                                                            <div class="progress-extended"> &nbsp;</div>
                                                        </div>
                                                    </div>
                                                    <!-- The table listing the files available for upload/download -->
                                                    <table role="presentation" class="table table-striped clearfix">
                                                        <tbody class="files"></tbody>
                                                    </table>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- The blueimp Gallery widget -->
                                <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls"
                                     data-filter=":even">
                                    <div class="slides"></div>
                                    <h3 class="title"></h3>
                                    <a class="prev"> ‹ </a>
                                    <a class="next"> › </a>
                                    <a class="close white"> </a>
                                    <a class="play-pause"> </a>
                                    <ol class="indicator"></ol>
                                </div>
                                <!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
                                <script id="template-upload" type="text/x-tmpl"> {% for (var i=0, file; file=o.files[i]; i++) { %}
                            <tr class="template-upload fade">
                                <td>
                                    <span class="preview"></span>
                                </td>
                                <td>
                                    <p class="name">{%=file.name%}</p>
                                    <strong class="error text-danger label label-danger"></strong>
                                </td>
                                <td>
                                    <p class="size">Processing...</p>
                                    <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                        <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                                    </div>
                                </td>
                                <td> {% if (!i && !o.options.autoUpload) { %}
                                    <button class="btn blue start" disabled>
                                        <i class="fa fa-upload"></i>
                                        <span>Start</span>
                                    </button> {% } %} {% if (!i) { %}
                                    <button class="btn red cancel">
                                        <i class="fa fa-ban"></i>
                                        <span>Cancel</span>
                                    </button> {% } %} </td>
                            </tr> {% } %}

                                </script>
                                <!-- The template to display files available for download -->
                                <script id="template-download" type="text/x-tmpl"> {% for (var i=0, file; file=o.files[i]; i++) { %}
                            <tr class="template-download fade">
                                <td>
                                    <span class="preview"> {% if (file.thumbnailUrl) { %}
                                        <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery>
                                            <img src="{%=file.thumbnailUrl%}">
                                        </a> {% } %} </span>
                                </td>
                                <td>
                                    <p class="name"> {% if (file.url) { %}
                                        <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl? 'data-gallery': ''%}>{%=file.name%}</a> {% } else { %}
                                        <span>{%=file.name%}</span> {% } %} </p> {% if (file.error) { %}
                                    <div>
                                        <span class="label label-danger">Error</span> {%=file.error%}</div> {% } %} </td>
                                <td>
                                    <span class="size">{%=o.formatFileSize(file.size)%}</span>
                                </td>
                                <td> {% if (file.deleteUrl) { %}
                                    <button class="btn red delete btn-sm" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}" {% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}' {% } %}>
                                        <i class="fa fa-trash-o"></i>
                                        <span>Delete</span>
                                    </button>
                                    <input type="checkbox" name="delete" value="1" class="toggle"> {% } else { %}
                                    <button class="btn yellow cancel btn-sm">
                                        <i class="fa fa-ban"></i>
                                        <span>Cancel</span>
                                    </button> {% } %} </td>
                            </tr> {% } %}

                                </script>


                                <div class="c_attribute">
                                    <div class="alert alert-success form2-success display-hide">
                                        تمت اضافة الخصائص بنجاح
                                    </div>

                                    <div class="alert alert-danger form2-error display-hide">
                                        فشل عملية الإضافة

                                    </div>
                                    <br/>
                                    <div class="row">
                                        <div class="col-sm-6">

                                            {!! Form::open(array('url' => route('saveAttributes'), 'method' => 'post', 'id' => 'attributes_form')) !!}

                                            <input type="hidden" class="prd_id" name="prd_id" value="{{$prd_id}}">

                                            <fieldset>
                                                <legend>{{ trans('products.attributes') }}</legend>

                                                <div class="row">
                                                    <div class="col-xs-9 col-sm-6 col-md-4">
                                                        {!! Form::label('atr_id', trans('products.select_attributes'), array('class' => 'control-label')) !!}
                                                        <select class="form-control custList" id="atr_id" name="atr_id">
                                                            <option value="">...</option>
                                                            @foreach($attributes as $id => $name)
                                                                <option value="{{ $id }}">{{ $name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-xs-3 col-md-2 p-t-25">
                                                        {!! Form::button(trans('products.add'), array('class' => 'btn btn-default btn-md', 'type' => 'button', 'onclick' => 'add_product_attribue()')) !!}
                                                    </div>
                                                </div>
                                                <br/>

                                                <div class="row">
                                                    <div class="col-xs-9 col-sm-6 col-md-4">
                                                        {{--<input type="hidden" name="attributes[0][attribute]" id="attributes_0_attribute" value="">--}}
                                                        <select name="attributes_values[]" id="attributes_values"
                                                                class="form-control " multiple="multiple">
                                                            {{--<option value="12">wdqvq qdqd</option>
                                                            <option value="32">ef qwef qwef</option>
                                                            <option value="234">ewf qwe qwef wqef</option>
                                                            <option value="344">fffffqwwwwf dfdfdfdf</option>--}}
                                                        </select>
                                                        <script>
                                                            /*$(document).ready(function() {
                                                                $("#attributes_0_values").select2();
                                                            });*/
                                                        </script>
                                                    </div>
                                                    <div class="col-xs-3 col-md-6 p-t-25">
                                                        <label><input type="checkbox" value="1"
                                                                      name="attributes_variation">&nbsp; هل تؤثر الخاصية
                                                            على سعر المنتج
                                                        </label>
                                                    </div>

                                                </div>
                                            </fieldset>
                                            <br/>
                                            <div class="form-actions noborder">
                                                {!! Form::submit(trans('products.save_attributes'), array('class' => 'btn btn-success')) !!}
                                            </div>
                                            {!! Form::close() !!}
                                        </div>
                                        <div class="col-sm-6">
                                            <table class="table table-hover">
                                                <thead>
                                                <tr>
                                                    <th>
                                                        #
                                                    </th>
                                                    <th>
                                                        الخاصية
                                                    </th>
                                                    <th>
                                                        مؤثر بالسعر
                                                    </th>

                                                    <th>
                                                        حذف
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody id="attribut_tb">
                                                <?php $i = 1;?>
                                                @if(isset($attrTable))
                                                    @foreach($attrTable as $key => $value)

                                                        <tr>
                                                            <td>{{$i++}}</td>
                                                            <td data-id="{{$value['id']}}">{{ $value['value']}}</td>
                                                            @if($value['pat_isVariation']==1)
                                                                <td><i class="fa fa-check"></i></td>
                                                            @else
                                                                <td>&nbsp;&nbsp;&nbsp;</td>
                                                            @endif
                                                            <td>
                                                                <button class="btn btn-icon-only btn-circle red"
                                                                        onclick="delAttr({{$value['id'] }})">
                                                                    <i class="fa fa-times"></i></button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>


                                <div class="c_variation">
                                    <div class="alert alert-success form3-success display-hide">
                                        تمت الاضافة بنجاح
                                    </div>

                                    <div class="alert alert-danger form3-error display-hide">
                                        فشل عملية الإضافة

                                    </div>
                                    <br/>
                                    <div class="row">

                                        {!! Form::open(array('url' => route('saveVariation'), 'method' => 'post', 'id' => 'variations_form')) !!}

                                        <input type="hidden" class="prd_id" name="prd_id" value="{{$prd_id}}">

                                        <fieldset>
                                            <legend>{{ trans('products.variation') }}</legend>


                                            <div class="row">
                                                <div class="col-xs-9 col-sm-6 col-md-4">
                                                    {{--<input type="hidden" name="attributes[0][attribute]" id="attributes_0_attribute" value="">--}}

                                                    <select name="attributes_variation[]" id="attributes_variation"
                                                            class="form-control " multiple="multiple">
                                                        <?php $i = 1;?>

                                                        @if(isset($varTextBox))

                                                            @foreach($varTextBox as $key => $value)
                                                                @if(isset($value['trn_foreignKey']))
                                                                    <option value="{{ $value['trn_foreignKey']}}">{{$value['trn_text']}}</option>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <script>
                                                        /*$(document).ready(function() {
                                                            $("#attributes_0_values").select2();
                                                        });*/
                                                    </script>
                                                </div>
                                                <div class="col-xs-3 col-md-2 p-t-25">
                                                    <label><input type="checkbox" value="1"
                                                                  name="pvr_isDefault">&nbsp;افتراضي
                                                    </label>
                                                </div>
                                                <div class="col-xs-3 col-md-2 p-t-25">
                                                    <label><input type="checkbox" value="1"
                                                                  name="pvr_isDiscount">&nbsp;خاضع للخصم
                                                    </label>
                                                </div>
                                                <div class="col-xs-3 col-md-2 p-t-25">
                                                    {!! Form::label('pvr_price', trans('products.pvr_price'), array('class' => 'control-label')) !!}
                                                    {!! Form::number('pvr_price', '', array('autocomplete' => 'off', 'id' => 'pvr_price', 'class' => 'form-control','min'=>1)) !!}
                                                </div>
                                                <div class="col-xs-3 col-md-2 p-t-25">
                                                    {!! Form::label('pvr_discount', trans('products.pvr_discount'), array('class' => 'control-label')) !!}
                                                    {!! Form::number('pvr_discount', '', array('autocomplete' => 'off', 'id' => 'pvr_discount', 'class' => 'form-control','min'=>1)) !!}
                                                </div>

                                            </div>
                                        </fieldset>
                                        <br/>
                                        <div class="form-actions noborder">
                                            {!! Form::submit(trans('products.save_variation'), array('class' => 'btn btn-success')) !!}
                                        </div>
                                        {!! Form::close() !!}

                                    </div>
                                    <div class="row">
                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th>
                                                    #
                                                </th>
                                                <th>
                                                    الخاصية
                                                </th>
                                                <th>
                                                    السعر
                                                </th>
                                                <th>
                                                    نسبة الخصم
                                                </th>
                                                <th>
                                                    افتراضي
                                                </th>
                                                <th>
                                                    خاضع للخصم
                                                </th>
                                                <th>
                                                    حذف
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody id="variations_tb">
                                            <?php $i = 1;
                                            $isDefault = '<i class="fa fa-check"></i>';
                                            ?>
                                            @if(isset($attrVar))

                                                @foreach($attrVar as $key => $value)

                                                    <tr>
                                                        <td>{{$i++}}</td>
                                                        <td>{{$value['value']}}</td>
                                                        <td>{{$value['pvr_price']}}</td>
                                                        <td>{{$value['pvr_discount']}}</td>
                                                        @if($value['pvr_isDefault']==1)
                                                            <td><i class="fa fa-check"></i></td>
                                                        @else
                                                            <td>&nbsp;</td>
                                                        @endif
                                                        @if($value['pvr_isDiscount']==1)
                                                            <td><i class="fa fa-check"></i></td>
                                                        @else
                                                            <td>&nbsp;</td>
                                                        @endif
                                                        <td>
                                                            <button class="btn btn-icon-only btn-circle red"
                                                                    onclick="delVariation({{$value['id']}})"><i
                                                                        class="fa fa-times"></i></button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <br/>

                            <br/>
                        </div>

                    </div>
                </div>
            </div>

            @push('css')
                <style>
                    .custList {
                        height: 40px !important;
                    }
                </style>
                <!-- BEGIN PAGE LEVEL PLUGINS -->
                <link href="{{url('')}}/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css"
                      rel="stylesheet"
                      type="text/css"/>
                <link href="{{ url('/js/lightbox2/css/lightbox.min.css') }}" rel="stylesheet" type="text/css"/>
                <!-- END PAGE LEVEL PLUGINS -->

            <!--<link href="{{url('libs/select2/select2.css')}}" rel="stylesheet" type="text/css" />-->

                <!-- BEGIN PAGE LEVEL PLUGINS -->
                <link href="{{url('')}}/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet"
                      type="text/css"/>
                <link href="{{url('')}}/assets/global/plugins/select2/css/select2-bootstrap.min.css"
                      rel="stylesheet" type="text/css"/>
                <!-- END PAGE LEVEL PLUGINS -->
                <link href="{{url('')}}/assets/global/plugins/cubeportfolio/css/cubeportfolio.css" rel="stylesheet"
                      type="text/css"/>
                <!-- END PAGE LEVEL PLUGINS -->
                <link href="{{url('')}}/assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet"
                      type="text/css"/>
                <link href="{{url('')}}/assets/global/plugins/jquery-file-upload/blueimp-gallery/blueimp-gallery.min.css"
                      rel="stylesheet" type="text/css"/>
                <link href="{{url('')}}/assets/global/plugins/jquery-file-upload/css/jquery.fileupload.css"
                      rel="stylesheet" type="text/css"/>
                <link href="{{url('')}}/assets/global/plugins/jquery-file-upload/css/jquery.fileupload-ui.css"
                      rel="stylesheet" type="text/css"/>
                <link href="{{ url('css/custom-rtl.css') }}" rel="stylesheet" type="text/css"/>
            @endpush
            @push('js')
            <!-- BEGIN PAGE LEVEL PLUGINS -->
                <script src="{{url('')}}/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js"
                        type="text/javascript"></script>
                <script src="{{ url('/js/lightbox2/js/lightbox.min.js') }}" type="text/javascript"></script>
                <!-- END PAGE LEVEL PLUGINS -->
                <script src="{{url('')}}/assets/global/plugins/fancybox/source/jquery.fancybox.pack.js"
                        type="text/javascript"></script>
                <script src="{{url('')}}/assets/global/plugins/jquery-file-upload/js/vendor/jquery.ui.widget.js"
                        type="text/javascript"></script>
                <script src="{{url('')}}/assets/global/plugins/jquery-file-upload/js/vendor/tmpl.min.js"
                        type="text/javascript"></script>
                <script src="{{url('')}}/assets/global/plugins/jquery-file-upload/js/vendor/load-image.min.js"
                        type="text/javascript"></script>
                <script src="{{url('')}}/assets/global/plugins/jquery-file-upload/js/vendor/canvas-to-blob.min.js"
                        type="text/javascript"></script>
                <script src="{{url('')}}/assets/global/plugins/jquery-file-upload/blueimp-gallery/jquery.blueimp-gallery.min.js"
                        type="text/javascript"></script>
                <script src="{{url('')}}/assets/global/plugins/jquery-file-upload/js/jquery.iframe-transport.js"
                        type="text/javascript"></script>
                <script src="{{url('')}}/assets/global/plugins/jquery-file-upload/js/jquery.fileupload.js"
                        type="text/javascript"></script>
                <script src="{{url('')}}/assets/global/plugins/jquery-file-upload/js/jquery.fileupload-process.js"
                        type="text/javascript"></script>
                <script src="{{url('')}}/assets/global/plugins/jquery-file-upload/js/jquery.fileupload-image.js"
                        type="text/javascript"></script>

                <script src="{{url('')}}/assets/global/plugins/jquery-file-upload/js/jquery.fileupload-validate.js"
                        type="text/javascript"></script>
                <script src="{{url('')}}/assets/global/plugins/jquery-file-upload/js/jquery.fileupload-ui.js"
                        type="text/javascript"></script>
                <script src="{{url('')}}/assets/pages/scripts/form-fileupload.js" type="text/javascript"></script>
                <!-- BEGIN PAGE LEVEL PLUGINS -->
                <script src="{{url('')}}/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"
                        type="text/javascript"></script>

                <script src="{{url('')}}/assets/global/plugins/jquery-validation/js/additional-methods.min.js"
                        type="text/javascript"></script>
                <!-- END PAGE LEVEL PLUGINS -->

                <!-- BEGIN PAGE LEVEL SCRIPTS -->
                <script src="{{url('')}}/assets/pages/scripts/form-validation-md.js"
                        type="text/javascript"></script>
                <!-- END PAGE LEVEL SCRIPTS -->

                <!-- BEGIN PAGE LEVEL PLUGINS -->
                <script src="{{url('')}}/assets/global/plugins/select2/js/select2.full.min.js"
                        type="text/javascript"></script>
                <!-- END PAGE LEVEL PLUGINS -->

                <!-- BEGIN PAGE LEVEL SCRIPTS -->
                <script src="{{url('')}}/assets/pages/scripts/components-select2.min.js"
                        type="text/javascript"></script>
                <!-- END PAGE LEVEL SCRIPTS -->

                <script src="{{ url('')}}/assets/js/custom.js" type="text/javascript"></script>
                <script src="{{url('')}}/assets/global/plugins/cubeportfolio/js/jquery.cubeportfolio.min.js"
                        type="text/javascript"></script>
                <script src="{{url('')}}/assets/pages/scripts/portfolio-1.min.js" type="text/javascript"></script>
                <script>

                        prductFormValidation.init();

                </script>
            @endpush
        </div>
        <!-- /Main Content -->
    </div>
    <!-- END CONTENT BODY -->

@stop
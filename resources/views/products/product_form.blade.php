<meta name="csrf-token" content="<?php echo csrf_token() ?>">
<?php
$editMode = (isset($product) && $product instanceof \App\Product);
//dd($editMode);
//$pageTitle = ($editMode) ? sprintf(trans('products.editing_product'), $product->translations()->where('lng_id', '=', lang())->first()->ptr_name) : trans('products.adding_new');

$prd_id = $prd_image = $prd_price = $prd_isVariable = $cat_id = NULL;
$prd_id = null;
$prd_image = null;
$prd_price = 0;
$prd_isVariable = -1;
$cat_id = null;
$prd_minQuantity = 1;
$prd_maxQuantity =1;
$prd_unit = 0;
$prd_unitValue =1;
$ptr_name = '';
$ptr_description = '';
$prd_barcode='';

/*if ($editMode) {

    $prd_id = $product->prd_id;
    $prd_image = $product->prd_image;
    $prd_price = $product->prd_price;
    $prd_isVariable = $product->prd_isVariable;
    $cat_id = $product->cat_id;
    $prd_minQuantity = $product->prd_minQuantity;
    $prd_maxQuantity = $product->prd_maxQuantity;
    $prd_unit = $product->prd_unit;
    $prd_unitValue = $product->prd_unitValue;

    $translations = $product->translations;
    $translations = indexedArrayOfEloquent($translations, 'lng_id');
} else {
    $prd_id = null;
    $prd_image = null;
    $prd_price = null;
    $prd_isVariable = -1;
    $cat_id = null;
    $prd_minQuantity = 0;
    $prd_maxQuantity = 0;
    $prd_unit = 0;
    $prd_unitValue = 0;
}*/
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
                                    <span class="caption-subject bold uppercase">اضافة منتج جديد</span>
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

                                <input type="hidden" class="prd_id" name="prd_id" value="">

                                <div class="row form-group">
                                    <div class=" col-md-6">
                                        {!! Form::label('ptr_name_en', 'اسم المنتج بالانجليزي', array('class' => 'control-label')) !!}
                                        {!! Form::text('ptr_name_en', $ptr_name, array('autocomplete' => 'off', 'id' => 'ptr_name_en', 'class' => 'form-control')) !!}
                                    </div>
                                    <div class=" col-md-6">
                                        {!! Form::label('ptr_name_ar','اسم المنتج بالعربي', array('class' => 'control-label')) !!}
                                        {!! Form::text('ptr_name_ar', $ptr_name, array('autocomplete' => 'off', 'id' => 'ptr_name_ar', 'class' => 'form-control')) !!}
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class=" col-md-6">
                                        {!! Form::label('ptr_description_en','وصف المنتج بالانجليزي', array('class' => 'control-label ')) !!}
                                        {!! Form::textarea('ptr_description_en', $ptr_description, array('autocomplete' => 'off', 'id' => 'ptr_description_en', 'class' => 'form-control', 'rows' => 3)) !!}
                                    </div>

                                    <div class=" col-md-6">
                                        {!! Form::label('ptr_description_ar', 'وصف المنتج بالعربي', array('class' => 'control-label')) !!}
                                        {!! Form::textarea('ptr_description_ar', $ptr_description, array('autocomplete' => 'off', 'id' => 'ptr_description_ar', 'class' => 'form-control', 'rows' => 3)) !!}
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class=" col-md-4">
                                        {!! Form::label('prd_price', trans('products.price'), array('class' => 'control-label')) !!}
                                        {!! Form::number('prd_price', $prd_price, array('autocomplete' => 'off', 'id' => 'prd_price', 'class' => 'form-control','min'=>1)) !!}
                                    </div>

                                    <!--<div class=" col-md-4">
                                        {!! Form::label('cat_id', trans('products.category'), array('class' => 'control-label')) !!}
                                        {!! Form::select('cat_id', $categories, $cat_id, array('class' => 'form-control custList')) !!}
                                    </div>-->
                                     <div class=" col-md-4">
                                        {!! Form::label('prd_price', trans('products.category'), array('class' => 'control-label')) !!}
                                    <select id="single-prepend-text" name="cat_id" class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                       
                                        @foreach($categories as $cat)
                                            <option value="{{$cat->cat_id}}"> {{$cat->cat_name}}</option>
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
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <img id="preview_image"
                                                     src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image"
                                                     width="200" height="150">
                                                <img id="preview_image2" class="hidden"
                                                     src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image"
                                                     width="200" height="150"
                                                     style="border: #0a001f;border-width: thin">

                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 10px;">
                                            <div class="col-sm-12">
                                                <span id="field2_area" hidden><input type="file" id="field2"/></span>
                                                <span class="btn default btn-file" id="field1_area">
                                                                    <span id="select_image"> {{trans('users.select_image')}} </span>
                                                                    <span id="change_image"
                                                                          class="hidden"> {{trans('users.change')}} </span>
                                                                    <input type="file" id="prd_image" name="prd_image"> </span>
                                                <a href="javascript:;" id="remove_image" class="btn red hidden">
                                                    {{trans('users.remove')}} </a>
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

   <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel panel-success">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">تحميل صور المنتج</h3>
                                            </div>
                                            <div class="panel-body">
                                                <form id="fileupload" action="{{url('product/saveGallery')}}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" class="prd_id" name="prd_id" value="{{$prd_id}}">
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
                                                            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                                                <div class="progress-bar progress-bar-success" style="width:0%;"> </div>
                                                            </div>
                                                            <!-- The extended global progress information -->
                                                            <div class="progress-extended"> &nbsp; </div>
                                                        </div>
                                                    </div>
                                                    <!-- The table listing the files available for upload/download -->
                                                    <table role="presentation" class="table table-striped clearfix">
                                                        <tbody class="files"> </tbody>
                                                    </table>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- The blueimp Gallery widget -->
                                <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-filter=":even">
                                    <div class="slides"> </div>
                                    <h3 class="title"></h3>
                                    <a class="prev"> ‹ </a>
                                    <a class="next"> › </a>
                                    <a class="close white"> </a>
                                    <a class="play-pause"> </a>
                                    <ol class="indicator"> </ol>
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
                            </tr> {% } %} </script>
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
                            </tr> {% } %} </script>

                                <div class="c_attribute" style="display: none">
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

                                            <input type="hidden" class="prd_id" name="prd_id" value="">

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
                                                                      name="attributes_variation">&nbsp; هل تؤثر الخاصية على سعر المنتج
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

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>


                                <div class="c_variation" style="display: none">
                                    <div class="alert alert-success form3-success display-hide">
                                        تمت الاضافة بنجاح
                                    </div>

                                    <div class="alert alert-danger form3-error display-hide">
                                        فشل عملية الإضافة

                                    </div>
                                    <br/>
                                    <div class="row">

                                        {!! Form::open(array('url' => route('saveVariation'), 'method' => 'post', 'id' => 'variations_form')) !!}

                                        <input type="hidden" class="prd_id" name="prd_id" value="">

                                        <fieldset>
                                            <legend>{{ trans('products.variation') }}</legend>


                                            <div class="row">
                                                <div class="col-xs-9 col-sm-6 col-md-4">
                                                    {{--<input type="hidden" name="attributes[0][attribute]" id="attributes_0_attribute" value="">--}}
                                                    <select name="attributes_variation[]" id="attributes_variation"
                                                            class="form-control " multiple="multiple">

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
                         <link href="{{url('')}}/assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css" />
                <link href="{{url('')}}/assets/global/plugins/jquery-file-upload/blueimp-gallery/blueimp-gallery.min.css" rel="stylesheet" type="text/css" />
                <link href="{{url('')}}/assets/global/plugins/jquery-file-upload/css/jquery.fileupload.css" rel="stylesheet" type="text/css" />
                <link href="{{url('')}}/assets/global/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet" type="text/css" />
                <!-- END PAGE LEVEL PLUGINS -->

                <link href="{{ url('css/custom-rtl.css') }}" rel="stylesheet" type="text/css"/>
            @endpush
            @push('js')
            <!-- BEGIN PAGE LEVEL PLUGINS -->
                <script src="{{url('')}}/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js"
                        type="text/javascript"></script>
                <script src="{{ url('/js/lightbox2/js/lightbox.min.js') }}" type="text/javascript"></script>
                <!-- END PAGE LEVEL PLUGINS -->
                 <script src="{{url('')}}/assets/global/plugins/fancybox/source/jquery.fancybox.pack.js" type="text/javascript"></script>
                <script src="{{url('')}}/assets/global/plugins/jquery-file-upload/js/vendor/jquery.ui.widget.js" type="text/javascript"></script>
                <script src="{{url('')}}/assets/global/plugins/jquery-file-upload/js/vendor/tmpl.min.js" type="text/javascript"></script>
                <script src="{{url('')}}/assets/global/plugins/jquery-file-upload/js/vendor/load-image.min.js" type="text/javascript"></script>
                <script src="{{url('')}}/assets/global/plugins/jquery-file-upload/js/vendor/canvas-to-blob.min.js" type="text/javascript"></script>
                <script src="{{url('')}}/assets/global/plugins/jquery-file-upload/blueimp-gallery/jquery.blueimp-gallery.min.js" type="text/javascript"></script>
                <script src="{{url('')}}/assets/global/plugins/jquery-file-upload/js/jquery.iframe-transport.js" type="text/javascript"></script>
                <script src="{{url('')}}/assets/global/plugins/jquery-file-upload/js/jquery.fileupload.js" type="text/javascript"></script>
                <script src="{{url('')}}/assets/global/plugins/jquery-file-upload/js/jquery.fileupload-process.js" type="text/javascript"></script>
                <script src="{{url('')}}/assets/global/plugins/jquery-file-upload/js/jquery.fileupload-image.js" type="text/javascript"></script>

                <script src="{{url('')}}/assets/global/plugins/jquery-file-upload/js/jquery.fileupload-validate.js" type="text/javascript"></script>
                <script src="{{url('')}}/assets/global/plugins/jquery-file-upload/js/jquery.fileupload-ui.js" type="text/javascript"></script>
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

            <!--<script src="{{url('libs/select2/select2.min.js')}}" type="text/javascript"></script>-->
                <!-- BEGIN PAGE LEVEL PLUGINS -->
                <script src="{{url('')}}/assets/global/plugins/select2/js/select2.full.min.js"
                        type="text/javascript"></script>
                <!-- END PAGE LEVEL PLUGINS -->

                <!-- BEGIN PAGE LEVEL SCRIPTS -->
                <script src="{{url('')}}/assets/pages/scripts/components-select2.min.js"
                        type="text/javascript"></script>
                <!-- END PAGE LEVEL SCRIPTS -->

                <script src="{{ url('')}}/assets/js/custom.js" type="text/javascript"></script>
                <script>
                    prductFormValidation.init();
                      $(document).ready(function () {
                        $('#product_form').find('.prd_id').val('');
                        $('#attributes_form').find('.prd_id').val('');
                        $('#variations_form').find('.prd_id').val('');
                        $('#fileupload').find('.prd_id').val('');
                        });

                </script>
            @endpush
        </div>
        <!-- /Main Content -->
    </div>
    <!-- END CONTENT BODY -->
    </div>
    <!-- END CONTENT -->
    <!-- END CONTAINER -->
    @include('includes/footer')
@stop
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


                {!! Form::model($category,['method'=>'PATCH','url'=>'category/'.$category->cat_id,'id'=>'form_sample_1','enctype'=>'multipart/form-data']) !!}
                 @include('category._formCategory',['edit'=>true ,  'type' => trans('category.edit_category')])
                {!! Form::close() !!}

            </div>
            <!-- /Main Content -->
        </div>
        <!-- END CONTENT BODY -->
    </div>
    <!-- END CONTENT -->
    <!-- END CONTAINER -->
    @include('includes/footer')
@stop

@push('css')

@endpush
@push('js')

@endpush
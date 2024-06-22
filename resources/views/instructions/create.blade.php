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


                {!! Form::open(['url'=>'instructions' , 'role'=>'form','id'=>'form_sample_1','enctype'=>'multipart/form-data']) !!}
                @include('instructions._formInstruction',['edit'=>null , 'type' => trans('instruction.add_instruction')])

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


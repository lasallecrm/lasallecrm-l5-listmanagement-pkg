@extends('lasallecmsadmin::bob1.layouts.default')

@section('content')

        <!-- Main content -->
<section class="content">

    <div class="container">

        <div class="row">

            {{-- form's title --}}
            <div class="row">
                <br /><br />
                {!! $HTMLHelper::adminPageTitle($data['packageName'], (ucwords($data['tableName'])), '') !!}
                <br /><br />
            </div>

        </div>


        <div class="row">

            @include('lasallecmsadmin::bob1.partials.message')

            <div class="col-md-2 text-center">
                <div class="boxX">
                    <div class="box-content">
                        {{-- empty on purpose --}}
                    </div>
                </div>
            </div>


            <div class="col-md-8 text-center">
                <div>
                    <div class="alert alert-warning" role="alert">

                        <h1 class="tag-title" style="color:black;">
                            Hey, {{ $user->name }}!
                            <hr />
                            You selected a workflow status code to fire an event(s).
                            <hr />
                            Do you really want to {{ $data['eventDescription'] }}?
                        </h1>

                    </div>

                    <hr />
                    <p>&nbsp;</p>
                    <br />

                    {!! Form::open(array('url' => 'admin/'.$data['formActionRoute'])) !!}

                    <input name="postID" type="hidden" value="{{{ $data['id'] }}}">
                    <input name="listID" type="hidden" value="{{{ $data['listID'] }}}">
                    <input name="eventDescription" type="hidden" value="{{{ $data['eventDescription'] }}}">

                    <button type="submit" class="btn btn-block btn-success">
                        <i class="fa fa-check fa-2x"></i>&nbsp;&nbsp; <strong><u>Yes</u>, I am absolutely sure that I want to fire this event!</strong>
                    </button>

                    {!! Form::close() !!}


                    <br /><br />

                    <a href="{{{ URL::route('admin.'.$data['resourceRouteName'].'.index') }}}" class="btn btn-block btn-danger"><i class="fa fa-times fa-2x"></i>&nbsp;&nbsp; <strong>Oh <u>No</u>, I do <u>not</u> want to fire this event. Take me back to the {{{ $data['tableName'] }}}.</strong></a>



                </div>
            </div>


            <div class="col-md-2 text-center">
                <div class="boxX">
                    <div class="box-content">
                        {{-- empty on purpose --}}
                    </div>
                </div>
            </div>



        </div> <!-- row -->

    </div> <!-- container -->

</section>
@stop
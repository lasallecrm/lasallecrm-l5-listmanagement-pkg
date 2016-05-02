<!doctype html>
<html lang="en">

@include('lasallecrmlistmanagement::subscribe-unsubscribe-list.header')

        <!-- Custom styles for this template http://getbootstrap.com/examples/navbar-fixed-top/-->
<link media="all" type="text/css" rel="stylesheet" href="{{{ Config::get('app.url') }}}/packages/usermanagement/frontend/{{{ Config::get('lasallecmsfrontend.frontend_template_name') }}}/login/login.css">
</head>

<body>

<div class="container">

    <div class="col-sm-offset-2 col-sm-8" style="margin-top:200px;">
        <div class="panel panel-default">

            <div class="panel-heading">
                {{{ Config::get('lasallecmsfrontend.site_name') }}}
                <br />Subscribe To {{{ $list }}}
            </div>

            <div class="panel-body text-center">

                <br />

                <!-- New Task Form -->
                {!! Form::open(['action' => '\Lasallecrm\Listmanagement\Http\Controllers\FrontendListSubscribeController@postSubscribe']) !!}

                        <!-- E-Mail Address -->
                <div style="margin-bottom: 25px; margin-top: 25px;" class="input-group">
                    <span class="input-group-addon"><i class="fa fa-btn fa-envelope"></i></span>
                    {!! Form::email('email', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'email']) !!}
                </div>




                @if (!$email_field_only)

                    <!-- first_name -->
                   <div style="margin-bottom: 25px" class="input-group">
                       <span class="input-group-addon"><i class="fa fa-btn fa-user"></i></span>
                       {!! Form::text('first_name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'first name']) !!}
                   </div>

                    <!-- surname -->
                    <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="fa fa-btn fa-user"></i></span>
                        {!! Form::text('surname', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'surname']) !!}
                    </div>

                @endif

                            <!-- Login Button -->
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-btn fa-sign-in"></i>&nbsp;&nbsp;Sign up for {{{ $list }}}
                    </button>




                    </form>

                <br /><br />

            </div>

        </div>

    </div>
</div>


</body>
</html>
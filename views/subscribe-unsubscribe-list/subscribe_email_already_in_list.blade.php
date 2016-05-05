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
                <br />Subscribe To Our
                <br />{!! $title !!}
                <br />Email LIst
            </div>

            <div class="panel-body text-center">

                <br />

                 <button type="button" class="btn btn-lg btn-info" style="color: purple;">
                    Your email address
                    <br >
                    {{{ $email }}}
                    <br />
                    is already subscribed to the
                    <br />
                    {!! $title !!}
                    <br />
                    email list.
                    <br /><br />
                </button>

                <br /><br />

            </div>

        </div>

    </div>
</div>


</body>
</html>
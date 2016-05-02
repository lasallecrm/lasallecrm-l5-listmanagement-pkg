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
                <br />Unsubscribe From Email List
            </div>

            <div class="panel-body text-center">

                <br />

                 <button type="button" class="btn btn-lg btn-success" style="color: purple;">
                    You have successfully unsubscribed your email
                    <br /><br />
                    "{{{ $email  }}}"
                     <br /><br />
                     from our email list
                     <br /><br />
                     "{{{ $listName }}}"
                </button>

                <br /><br />

            </div>

        </div>

    </div>
</div>


</body>
</html>
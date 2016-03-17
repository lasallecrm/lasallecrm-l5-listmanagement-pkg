<!doctype html>
<html lang="en">

@include('lasallecrmlistmanagement::unsubscribe.header')

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

                 <button type="button" class="btn btn-lg btn-warning" style="color: purple;">
                    We are trying to unsubscribe you from our email list.
                    <br /><br />
                    However, the token that you specified is not in our records.
                    <br /><br />
                    So we are unable to unsubscribe you.
                </button>

                <br /><br />

            </div>

        </div>

    </div>
</div>


</body>
</html>
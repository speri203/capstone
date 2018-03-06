<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>NGAFID</title>

    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">

    <!-- Styles -->
    {{-- @TODO: remove bootstrap styles from resources/assets/css/app.css so that we can use the CDN instead. --}}
    {{--<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">--}}
    <link href="{{ elixir("css/vendor.css") }}" rel="stylesheet">
    <link href="{{ elixir("css/custom.css") }}" rel="stylesheet">

    @yield('cssScripts')

    <!-- Fonts -->
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button"
                        class="navbar-toggle collapsed"
                        data-toggle="collapse"
                        data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">
                    N<span style="color:#00008b">GA</span>FID
                </a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="{{ url('/') }}">Home</a>
                    </li>
                    @if (Auth::user())
                        @include('partials.custom-nav')
                    @endif
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    @if (Auth::guest())
                        <li>
                            <a href="{{ url('/auth/login') }}">Login</a>
                        </li>
                        <li>
                            <a href="{{ url('/auth/register') }}">Register</a>
                        </li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->email }}
                                <span class="glyphicon glyphicon-cog"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ url('/profile') }}">
                                        My Account
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('/faq') }}">FAQ's</a>
                                </li>
                                <li>
                                    <a href="{{ env('MAILTO_STRING') }}">
                                        Contact Us
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('/auth/logout') }}">
                                        Logout
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>

                @if (Auth::check())
                    <form class="navbar-form navbar-right form-horizontal">
                        <div class="form-group">
                            @if (Auth::user()->isFleetAdministrator())
                                @if (Auth::user()->fleet->wantsDataEncrypted())
                                    {{-- Fleet is enrolled in encryption, show a toggle for them to either show their flight data encrypted (toggle='On') or decrypted (toggle='Off') --}}
                                    <div class="checkbox">
                                        <input type="checkbox" id="toggleEncryption" {{ Session::get('toggleEnc') === 'F' ? '' : 'checked' }}>
                                    </div>
                                @else
                                    {{-- Fleet has not signed up for encryption yet, give them a button to enroll --}}
                                    <a href="{{ url('cryptosystem') }}" class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="bottom" title="Click to Enroll">
                                        Enable Encryption
                                    </a>
                                @endif
                            @endif
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </nav>

    @include('flash::message')

    @yield('content')

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="{{ elixir('js/jquery.min.js') }}"><\/script>')</script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="{{ elixir('js/vendor.js') }}"></script>

    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();

            // This will automatically dismiss alerts after a 5 second delay;
            // commented out for now because some error messages are too long
            // to read in 5 seconds
//            $('div.alert').not('alert-important').delay(5000).fadeOut(300);

            $('#toggleEncryption').bootstrapSwitch({
                size: 'mini',
                onColor: 'success',
                offColor: 'danger',
                labelText: 'Encryption',
                onSwitchChange: function (event, state) {
                    console.log('Toggle: ' + $(this).prop('checked'));

                    window.location.href = $(this).prop('checked')
                        ? "{{ url('/decrypt?toggle=T') }}"
                        : "{{ url('/decrypt?toggle=F') }}";
                }
            });
        })
    </script>

    @yield('jsScripts')

    <div class="footer">
        <div class="container col-xs-12">
            <div id="copyright">
                &copy; Copyright {{ date("Y") }}
                National General Aviation Flight Information Database
                <img class="pull-right" src="{{ elixir('images/aerospace-logo.webp') }}" height="17" alt="UND Aerospace">
            </div>
        </div>
    </div>

</body>

</html>

<!DOCTYPE html>
<html>
<head>
     <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
    <title>Test</title>
       <script type="text/javascript" src="{{ asset('Cesium/Apps/Sandcastle/Sandcastle-header.js') }}"></script>
       <script type="text/javascript" src="{{ asset('Cesium/ThirdParty/requirejs-2.1.9/require.js') }}"></script>

        <script type="text/javascript">
        require.config({
            baseUrl: "{{ asset('Cesium/Source') }}",
            waitSeconds: 60
        });
    </script>
  
</head>
<body class="sandcastle-loading" data-sandcastle-bucket="bucket-requirejs.html">
    <style>
        @import url("{{ asset('Cesium/Apps/Sandcastle/templates/bucket.css') }}");
    </style>
    <div id="cesiumContainer" class="fullSize"></div>
    

    <script id="cesium_sandcastle_script">
        function startup(Cesium) 
        {
            'use strict';
            //Sandcastle_Begin
            var viewer = new Cesium.Viewer('cesiumContainer');
            //Sandcastle_End
            Sandcastle.finishedLoading();
        }
        if (typeof Cesium !== "undefined") {
            startup(Cesium);
        } else if (typeof require === "function") {
            require(["Cesium"], startup);
        }
    </script>


</body>
</html>
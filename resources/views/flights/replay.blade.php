<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NGAFID</title>
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

    {!! Form::open(['method' => 'GET', 'route' => ['flights/replay', $flight], 'class' => 'form-horizontal']) !!}
        <div id="cesiumContainer" class="fullSize"></div>
        <div id="loadingOverlay">
            <h1>Loading...</h1>
        </div>
        <div id="toolbar"></div>
    {!! Form::close() !!}


    <script id="cesium_sandcastle_script">
        var flightID = "{{ json_encode($flight) }}";
        flightID = flightID.replace(/&quot;/g, '');

        var path = "{{ json_encode(asset('tmp/' . $flight . '.czml') ) }}";
        path = path.replace(/&quot;/g, '');

        function startup(Cesium) {
            Cesium.BingMapsApi.defaultKey = 'AihImmOR6pfmLJCWML_PiheiY1etbNFlXAItDEC89jvF34Y2FGLuYu8LZqSz4Yzz';
            "use strict";

            //Sandcastle_Begin
            var viewer = new Cesium.Viewer('cesiumContainer', {
                terrainProviderViewModels: [],  // Disable terrain changing
                //infoBox : false, // Disable InfoBox widget
                selectionIndicator: false  // Disable selection indicator
            });

            var cesiumTerrainProviderMeshes = new Cesium.CesiumTerrainProvider({
                url: '//assets.agi.com/stk-terrain/world',
                requestWaterMask: true,
                requestVertexNormals: true
            });

            viewer.terrainProvider = cesiumTerrainProviderMeshes;

            // Enable lighting based on sun/moon positions
            viewer.scene.globe.enableLighting = false;

            // Use STK World Terrain
            viewer.terrainProvider = new Cesium.CesiumTerrainProvider({
                url: '//assets.agi.com/stk-terrain/world',
                requestWaterMask: true,
                requestVertexNormals: true
            });

            // Enable depth testing so things behind the terrain disappear.
            viewer.scene.globe.depthTestAgainstTerrain = false;

            var PropPlane = viewer.entities.add({
                name: 'Flight Reanimation',
                model: {
                    uri: '../../Cesium/Apps/SampleData/models/CesiumAir/Cesium_Air.bgltf',
                    show: false,
                    minimumPixelSize: 128
                },
                label: {
                    text: '',
                    font: 'bold 12pt Segoe UI Semibold',
                    fillColor: Cesium.Color.GREENYELLOW,
                    outlineColor: Cesium.Color.BLACK,
                    outlineWidth: 2,
                    style: Cesium.LabelStyle.FILL_AND_OUTLINE,
                    pixelOffset: new Cesium.Cartesian2(0, -100),
                    horizontalOrigin: Cesium.HorizontalOrigin.CENTER,
                    verticalOrigin: Cesium.HorizontalOrigin.CENTER
                }
            });

            var StartPositionMarker = viewer.entities.add({
                name: 'Starting Position',
                billboard: {
                    image: '../../Cesium/Apps/Sandcastle/images/StartRoundPin.png',
                    show: true, // default
                    pixelOffset: new Cesium.Cartesian2(0, 0), // default: (0, 0)
                    eyeOffset: new Cesium.Cartesian3(0.0, 0.0, 0.0), // default
                    horizontalOrigin: Cesium.HorizontalOrigin.CENTER, // default
                    verticalOrigin: Cesium.VerticalOrigin.BOTTOM, // default: CENTER
                    scale: 1.0, // default: 1.0
                    width: 25, // default: undefined
                    height: 25 // default: undefined
                },
                description: "<p>Origin</p>"
            });

            var StopPositionMarker = viewer.entities.add({
                name: 'Ending Position',
                billboard: {
                    image: '../../Cesium/Apps/Sandcastle/images/StopRoundPin.png',
                    show: true, // default
                    pixelOffset: new Cesium.Cartesian2(0, 0), // default: (0, 0)
                    eyeOffset: new Cesium.Cartesian3(0.0, 0.0, 0.0), // default
                    horizontalOrigin: Cesium.HorizontalOrigin.CENTER, // default
                    verticalOrigin: Cesium.VerticalOrigin.BOTTOM, // default: CENTER
                    scale: 1.0, // default: 1.0
                    width: 25, // default: undefined
                    height: 25 // default: undefined
                },
                description: "<p>Destination</p>"
            });

            Sandcastle.addDefaultToolbarButton('CZML Flight ' + flightID, function () {

                reloadData();

                viewer.dataSources.add(Cesium.CzmlDataSource.load(path)).then(function (dataSource) {
                    viewer.clock.shouldAnimate = false;
                    var PlaneData = dataSource.entities.getById('FlightTrack/Replay');
                    PropPlane.model.show = true;

                    var timeInterval = dataSource.entities.computeAvailability();

                    // Get the delta time in seconds
                    var DeltaSecs = Cesium.JulianDate.secondsDifference(timeInterval.stop, timeInterval.start);
                    var PosOrientProperty = new Cesium.SampledPositionProperty();
                    var FirstSample = false;
                    var EndPosition;

                    for (var i = 0; i <= DeltaSecs; i += 1) {
                        var t = Cesium.JulianDate.addSeconds(timeInterval.start, i, new Cesium.JulianDate());
                        var pos = PlaneData.position.getValue(t);
                        if (pos !== undefined) {
                            // If this is the first valid position data coordinate, capture it
                            if (FirstSample === false) {
                                StartPositionMarker.position = pos;
                                FirstSample = true;
                            }

                            PosOrientProperty.addSample(t, pos);
                            EndPosition = pos;
                        }
                    }

                    StopPositionMarker.position = EndPosition;

                    PropPlane.position = PosOrientProperty;
                    PropPlane.orientation = new Cesium.VelocityOrientationProperty(PosOrientProperty);
                    PropPlane.label.text = PlaneData.name;

                    // We don't have orientation data yet
                    viewer.flyTo(PropPlane).then(function () {
                        viewer.trackedEntity = PropPlane;
                        viewer.selectedEntity = viewer.trackedEntity;
                        viewer.clock.multiplier = 10;
                        viewer.clock.shouldAnimate = true;

                    });

                });

            });


            var reloadData = function () {
                viewer.dataSources.removeAll();
                viewer.homeButton.viewModel.command();
                viewer.clock.clockRange = Cesium.ClockRange.UNBOUNDED;
                viewer.clock.clockStep = Cesium.ClockStep.SYSTEM_CLOCK;
            };


            //Add button to view the path from the top down
            Sandcastle.addToolbarButton('Top Down View', function () {
                viewer.trackedEntity = undefined;
                // Setting the view to 90 degrees (Straight down) generates some clipping issues with
                //Billboard text, so shave a few degrees off
                viewer.zoomTo(PropPlane, new Cesium.HeadingPitchRange(0, Cesium.Math.toRadians(-88.9), 30000));
            });

            //Add button to view the path from the side
            Sandcastle.addToolbarButton('Side View', function () {
                viewer.trackedEntity = undefined;
                viewer.zoomTo(PropPlane, new Cesium.HeadingPitchRange(Cesium.Math.toRadians(-90), Cesium.Math.toRadians(-15), 7500));
            });

            //Add button to track the entity as it moves
            Sandcastle.addToolbarButton('Focus Aircraft', function () {
                viewer.trackedEntity = PropPlane;
            });

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

Cesium.BingMapsApi.defaultKey = 'AoUP29Z-v0eqHOJaE4BaVhYJ1XuRZX04Oeiiw8if5KliJq7BbbJw9t0IrPe-Uix1';

/*
    Creating viewer and adding specific controls to it to display when app is launched
*/
var viewer = new Cesium.Viewer('cesiumContainer', {
    scene3DOnly: true,
    selectionIndicator: false,
    baseLayerPicker: false
});

/*
    Adding the actual bing image to the cesium viewer and setting default values
*/
viewer.imageryLayers.addImageryProvider(new Cesium.BingMapsImageryProvider({
    url : 'https://dev.virtualearth.net',
    mapStyle: Cesium.BingMapsStyle.ARIEL // Can also use Cesium.BingMapsStyle.ROADS
}));

/*
    Loading up terrain textures and adding it to the cesium map
*/
viewer.terrainProvider = new Cesium.CesiumTerrainProvider({
    url : 'https://assets.agi.com/stk-terrain/world',
    requestWaterMask : false, // required for water effects
    requestVertexNormals : false // required for terrain lighting
});

// Enable depth testing so things behind the terrain disappear. Not necessarly needed for our purposes
// viewer.scene.globe.depthTestAgainstTerrain = true;

//Enabling lighting depending on time of day
viewer.scene.globe.enableLighting = true;

/*
    Creating initial camera load to look over grand forks airport
    Default und airport cordinates: Lat: 47.957674, Long: -97.181238
    Default view is birds eye directly from top, can change heading, pitch, and roll variables to change view
*/
var gfLocation = new Cesium.Cartesian3.fromDegrees(-97.181238, 47.957674, 2631.0827);

var homeCameraView = {
    destination: gfLocation,
    orientation: {
        heading: 0.0,
        pitch: -Cesium.Math.PI_OVER_TWO,
        roll: 0.0
    }
};

//Sets the default view and goes to location
viewer.scene.camera.setView(homeCameraView);

/*
    Enable the home button to return to airport starting point rather than zoomed out at globe
    *TODO* Camera home button does not work. Resets to default zoomed out view. Need to fix (Will leave in for now, doesn't error out)
*/
viewer.homeButton.viewModel.command.beforeExecute.addEventListener(function (e) {
    e.cancel = true;
    viewer.scene.camera.flyTo(homeCameraView);
});

// Set up clock and timeline.
viewer.clock.shouldAnimate = true; // default
/*
    Time settings for clock in order to start tracking data from startTime to endTime. 
    NOTE: Cesium uses JulianDate which stores the number of days since January 1, -4712 (4713 BC)
*/
//viewer.clock.startTime = Cesium.JulianDate.fromIso8601("2017-07-11T16:00:00Z");
//viewer.clock.stopTime = Cesium.JulianDate.fromIso8601("2017-07-11T16:20:00Z");
//viewer.clock.currentTime = Cesium.JulianDate.fromIso8601("2017-07-11T16:00:00Z");
viewer.clock.multiplier = 2; // sets a speedup
viewer.clock.clockStep = Cesium.ClockStep.SYSTEM_CLOCK_MULTIPLIER; // tick computation mode
viewer.clock.clockRange = Cesium.ClockRange.LOOP_STOP; // loop at the end
viewer.timeline.zoomTo(viewer.clock.startTime, viewer.clock.stopTime); // set visible range


/*
    Adding Spacial data to Cesium using Entities. There are three vector formats (positional) supported by cesium
    GeoJson, KML, and their own CZML format. Entities are objects that can represent a point in time and space
    and Cesium can add graphical representation to those points
    (Main form of flight data representation for our project)
    *TODO* Add spacial data and frame code for easy data representation (Dynamic needed)
*/
var flightData = Cesium.CzmlDataSource.load('./Source/FlightData/flight.czml'); //This is a static file with only one flights data. Need to be dynamic
flightData.then(function(dataSource) {
    viewer.dataSource.add(dataSource);
});



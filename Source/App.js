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



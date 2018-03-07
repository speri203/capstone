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
    Enable the home button to return to airport starting point rather than zoomed out at globe. Now fixed
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

var flightPath = viewer.entities.add({
    name: "Test flight",
    polyline: {
        positions: Cesium.Cartesian3.fromDegreesArray([-97.1471939,47.9760742,-97.1477509,47.9760666,-97.1482925,47.9760628,-97.1488495,47.976059,-97.1493988,47.9760513,-97.1499481,47.9760475,-97.1505203,47.9760399,-97.1510544,47.9760361,-97.1515884,47.9760246,-97.1521454,47.9760208,-97.1526947,47.976017,-97.153244,47.9760132,-97.1538086,47.9760132,-97.1543427,47.9760132,-97.1548767,47.9760056,-97.155426,47.9760056,-97.1559601,47.9760017,-97.1564941,47.9759903,-97.1570053,47.9759789,-97.1575165,47.975975,-97.1580048,47.9759636,-97.1585236,47.9759598,-97.1590042,47.9759483,-97.1594772,47.9759407,-97.1599503,47.9759369,-97.1604309,47.9759216,-97.1609039,47.9759102,-97.161377,47.9758911,-97.16185,47.9758606,-97.1623077,47.9758301,-97.1627579,47.9757805,-97.1632156,47.9757309,-97.1636429,47.975666,-97.1640701,47.9755859,-97.1644669,47.9754829,-97.1648407,47.9753532,-97.1651611,47.9752007,-97.1654358,47.9750175,-97.165657,47.9748116,-97.1658249,47.9745941,-97.1659317,47.9743652,-97.1660004,47.9741325,-97.1660233,47.9738884,-97.1660538,47.9736481,-97.1660538,47.9734001,-97.166069,47.9731522,-97.1660614,47.9728966,-97.1660614,47.9726486,-97.1660614,47.9723854,-97.1660614,47.9721451,-97.1660614,47.9718971,-97.1660538,47.9716377,-97.1660538,47.971386,-97.1660538,47.9711266,-97.1660614,47.9708748,-97.1660767,47.9705925,-97.1660843,47.970108,-97.1660995,47.9698448,-97.1661224,47.969593,-97.1661377,47.9693375,-97.1661606,47.9690819,-97.1661835,47.9688263,-97.1662064,47.9685669,-97.166214,47.9683113,-97.1662369,47.9680443,-97.1662445,47.9677925,-97.1662521,47.9675217,-97.1662521,47.9672737,-97.1662598,47.9670219,-97.1662521,47.9667664,-97.1662521,47.9665108,-97.1662369,47.966259,-97.1662292,47.9660034,-97.1662216,47.9657516,-97.166214,47.9654922,-97.1662064,47.9652405,-97.1661987,47.9649887,-97.1661758,47.9647293,-97.1661758,47.964489,-97.1661682,47.964241,-97.1661682,47.9639931,-97.1661606,47.9637451,-97.1661606,47.9634933,-97.1661606,47.9632492,-97.1661606,47.9629974,-97.1661606,47.9627457,-97.166153,47.9625092,-97.166153,47.962265,-97.1661453,47.9620209,-97.1661453,47.9617729,-97.1661377,47.961525,-97.1661377,47.9612808,-97.1661224,47.9610252,-97.1661301,47.9607849,-97.1661224,47.9605408,-97.1661224,47.9602928,-97.1661224,47.9600449,-97.1661224,47.9597969,-97.1661301,47.9595375,-97.1661301,47.9592972,-97.1661377,47.959053,-97.1661377,47.9587975,-97.1661453,47.9585533,-97.166153,47.9583054,-97.1661606,47.9580383,-97.1661682,47.9577942,-97.1661835,47.9575462,-97.1661758,47.9572868,-97.1661758,47.9570312,-97.1661911,47.9567757,-97.1661911,47.9565125,-97.1661987,47.9562492,-97.1661987,47.9557648,-97.1661987,47.9555092,-97.1661987,47.9552574,-97.1662064,47.9550056,-97.1662064,47.9547348,-97.1662064,47.9544907,-97.166214,47.9542389,-97.166214,47.9539795,-97.1662216,47.9537277,-97.1662216,47.953476,-97.1662216,47.9532204,-97.1662216,47.9529686,-97.1662216,47.9527092,-97.1662216,47.9524574,-97.1662216,47.9522057,-97.1662216,47.9519463]), 
        // positions: Cesium.Cartesian3.fromDegreesArrayHeights([]), //Includes heights for the lines as well
        width: 2,
        material: Cesium.Color.RED
    }
});
viewer.zoomTo(viewer.entities);



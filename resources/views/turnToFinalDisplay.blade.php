@extends ('NGAFID-master')

@section('content')

<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE-Edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale-1, user-scalable=no">
	<title>Turn to Final</title>
	<!--Cesium libraries -->
	<script src="ThirdParty/Cesium/Cesium.js"></script>
	<style>@import url(../../public/Cesium/ThirdParty/Cesium/Widgets/widgets.css);</style>
	<link rel="stylesheet" href="../../public/Cesium/index.css">
</head>
<body>
	<div id="cesiumContainer"></div>
	<script src="../../public/Cesium/Source/App.js"></script>
</body>
</html>

<!--
<script>
    <?php
        echo "var obj = $data;";
    ?>
    // obj = JSON.parse(obj)
    var testArr = obj.f381001.split(',');
    testArr.forEach(function(element) {
        console.log(element);
    });
    
</script>
-->

@endsection
@extends ('NGAFID-master')

@section('content')

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

@endsection
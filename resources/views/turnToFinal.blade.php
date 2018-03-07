@extends ('NGAFID-master')


@section ('content')




    <div class="container-fluid">
        <h1>Turn To Final</h1>

        Enter flights IDs, comma separated

        <form action="turnToFinal/runQuery">
            <input type="textField" name="flightIDs">
            <button type="submit" name = "submit" value="idString">View flights</button>
        </form>
        

    </div>


   


@endsection


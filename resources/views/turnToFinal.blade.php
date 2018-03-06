@extends ('NGAFID-master')


@section ('content')




    <div class="container-fluid">
        <h1>Turn To Final</h1>

        Enter flights IDs, comma separated

        <form action="turnToFinal/runQuery">
            <input type="textField" name="flightIDs">
            <button type="submit" name = "submit" value="idString">View flights</button>
        </form>
        <h2>Or</h2>

         View Flights from
        <form action="turnToFinal/viewFlights">
        <input type="date" name="startDate" value="2015-09-01">
        to
        <input type="date" name="endDate" value="2015-09-01">
        <button type="submit" name="submit" value = "viewFlights">View Flights</button>
        </form>
        <br><br>
        <?php
            if (isset($flights))
            {
                echo "<form action=\"turnToFinal/runQuery\">";
                $counter = 0;
                foreach ($flights as $flight)
                {
                    echo ("<input type= \"checkbox\"name=\"$counter\" checked = 'checked'>");
                    echo "<label for='$counter'>$counter $flight->n_number $flight->date</label><br>";
                    $counter++;
                }
            }
        ?>

    </div>


   


@endsection


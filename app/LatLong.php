<?php
namespace NGAFID;

use DB;


$str = DB::select('select * from flight_id limit 10');
print_r($str);



?>
<?php
namespace NGAFID;

use Eloquent;
use DB;

/**
 * NGAFID\Main
 *
 * @property int                   $id
 * @property int                   $flight
 * @property bool                  $phase
 * @property int                   $time                         milliseconds
 * @property float|null            $pressure_altitude
 * @property float|null            $msl_altitude
 * @property float|null            $indicated_airspeed
 * @property float|null            $vertical_airspeed
 * @property float|null            $tas
 * @property float|null            $mach
 * @property float|null            $heading
 * @property float|null            $course
 * @property float|null            $pitch_attitude
 * @property float|null            $roll_attitude
 * @property string|null           $radio_transmit
 * @property float|null            $eng_1_rpm
 * @property float|null            $eng_2_rpm
 * @property float|null            $eng_3_rpm
 * @property float|null            $eng_4_rpm
 * @property float|null            $eng_1_mp
 * @property float|null            $eng_2_mp
 * @property float|null            $eng_3_mp
 * @property float|null            $eng_4_mp
 * @property float|null            $prop_1_angle
 * @property float|null            $prop_2_angle
 * @property float|null            $prop_3_angle
 * @property float|null            $prop_4_angle
 * @property string|null           $autopilot
 * @property float|null            $pitch_control_input
 * @property float|null            $lateral_control_input
 * @property float|null            $rudder_pedal_input
 * @property float|null            $pitch_control_surface_position
 * @property float|null            $lateral_control_surface_position
 * @property float|null            $yaw_control_surface_position
 * @property float|null            $vertical_acceleration
 * @property float|null            $longitudinal_acceleration
 * @property float|null            $lateral_acceleration
 * @property float|null            $pitch_trim_surface_position
 * @property bool|null             $trailing_edge_flap_selection
 * @property bool|null             $leading_edge_flap_selection
 * @property float|null            $thrust_reverse_position_1
 * @property float|null            $thrust_reverse_position_2
 * @property float|null            $thrust_reverse_position_3
 * @property float|null            $thrust_reverse_position_4
 * @property bool|null             $ground_spoiler_speed_brake_position
 * @property float|null            $oat
 * @property int|null              $afcs_mode
 * @property int|null              $radio_altitude_actual
 * @property int|null              $radio_altitude_derived
 * @property float|null            $localizer_deviation
 * @property float|null            $glideslope_deviation
 * @property string|null           $marker_beacon_passage
 * @property string|null           $master_warning
 * @property string|null           $weight_on_wheels
 * @property float|null            $aoa
 * @property string|null           $hydraulic_pressure_low
 * @property float|null            $groundspeed
 * @property string|null           $terrain_warning
 * @property string|null           $landing_gear_position
 * @property float|null            $drift_angle
 * @property float|null            $wind_speed
 * @property int|null              $wind_direction
 * @property float|null            $latitude
 * @property float|null            $longitude
 * @property string|null           $stall_warning
 * @property string|null           $stick_shaker
 * @property string|null           $stick_pusher
 * @property string|null           $windshear
 * @property float|null            $throttle_lever_position_1
 * @property float|null            $throttle_lever_position_2
 * @property float|null            $throttle_lever_position_3
 * @property float|null            $throttle_lever_position_4
 * @property bool|null             $traffic_alert
 * @property float|null            $dme_1_distance
 * @property float|null            $dme_2_distance
 * @property float|null            $nav_1_freq
 * @property float|null            $nav_2_freq
 * @property float|null            $obs_1
 * @property float|null            $obs_2
 * @property float|null            $altimeter
 * @property int|null              $selected_altitude
 * @property int|null              $selected_speed
 * @property float|null            $selected_mach
 * @property int|null              $selected_vertical_speed
 * @property int|null              $selected_heading
 * @property bool|null             $selected_flight_path
 * @property int|null              $selected_decision_height
 * @property bool|null             $efis_display_format          separate table
 * @property bool|null             $mfd_display_format           separate table
 * @property string|null           $thrust_command
 * @property string|null           $thrust_target
 * @property float|null            $fuel_quantity_total
 * @property float|null            $fuel_quantity_left_main
 * @property float|null            $fuel_quantity_right_main
 * @property float|null            $fuel_quantity_aux_1
 * @property float|null            $fuel_quantity_aux_2
 * @property float|null            $fuel_quantity_aux_3
 * @property float|null            $fuel_quantity_cg_trim_tank
 * @property float|null            $eng_1_fuel_flow
 * @property float|null            $eng_2_fuel_flow
 * @property float|null            $eng_3_fuel_flow
 * @property float|null            $eng_4_fuel_flow
 * @property bool|null             $primary_nav_system_reference separate table
 * @property string|null           $icing
 * @property string|null           $eng_1_vibration_warning
 * @property string|null           $eng_2_vibration_warning
 * @property string|null           $eng_3_vibration_warning
 * @property string|null           $eng_4_vibration_warning
 * @property string|null           $eng_1_overtemp_warning
 * @property string|null           $eng_2_overtemp_warning
 * @property string|null           $eng_3_overtemp_warning
 * @property string|null           $eng_4_overtemp_warning
 * @property float|null            $eng_1_oil_press
 * @property float|null            $eng_2_oil_press
 * @property float|null            $eng_3_oil_press
 * @property float|null            $eng_4_oil_press
 * @property string|null           $eng_1_oil_press_low_warning
 * @property string|null           $eng_2_oil_press_low_warning
 * @property string|null           $eng_3_oil_press_low_warning
 * @property string|null           $eng_4_oil_press_low_warning
 * @property float|null            $eng_1_oil_temp
 * @property float|null            $eng_2_oil_temp
 * @property float|null            $eng_3_oil_temp
 * @property float|null            $eng_4_oil_temp
 * @property string|null           $eng_1_overspeed_warning
 * @property string|null           $eng_2_overspeed_warning
 * @property string|null           $eng_3_overspeed_warning
 * @property string|null           $eng_4_overspeed_warning
 * @property float|null            $eng_1_cht_1
 * @property float|null            $eng_1_cht_2
 * @property float|null            $eng_1_cht_3
 * @property float|null            $eng_1_cht_4
 * @property float|null            $eng_1_cht_5
 * @property float|null            $eng_1_cht_6
 * @property float|null            $eng_2_cht_1
 * @property float|null            $eng_2_cht_2
 * @property float|null            $eng_2_cht_3
 * @property float|null            $eng_2_cht_4
 * @property float|null            $eng_2_cht_5
 * @property float|null            $eng_2_cht_6
 * @property float|null            $eng_3_cht_1
 * @property float|null            $eng_3_cht_2
 * @property float|null            $eng_3_cht_3
 * @property float|null            $eng_3_cht_4
 * @property float|null            $eng_3_cht_5
 * @property float|null            $eng_3_cht_6
 * @property float|null            $eng_4_cht_1
 * @property float|null            $eng_4_cht_2
 * @property float|null            $eng_4_cht_3
 * @property float|null            $eng_4_cht_4
 * @property float|null            $eng_4_cht_5
 * @property float|null            $eng_4_cht_6
 * @property float|null            $eng_1_egt_1
 * @property float|null            $eng_1_egt_2
 * @property float|null            $eng_1_egt_3
 * @property float|null            $eng_1_egt_4
 * @property float|null            $eng_1_egt_5
 * @property float|null            $eng_1_egt_6
 * @property float|null            $eng_2_egt_1
 * @property float|null            $eng_2_egt_2
 * @property float|null            $eng_2_egt_3
 * @property float|null            $eng_2_egt_4
 * @property float|null            $eng_2_egt_5
 * @property float|null            $eng_2_egt_6
 * @property float|null            $eng_3_egt_1
 * @property float|null            $eng_3_egt_2
 * @property float|null            $eng_3_egt_3
 * @property float|null            $eng_3_egt_4
 * @property float|null            $eng_3_egt_5
 * @property float|null            $eng_3_egt_6
 * @property float|null            $eng_4_egt_1
 * @property float|null            $eng_4_egt_2
 * @property float|null            $eng_4_egt_3
 * @property float|null            $eng_4_egt_4
 * @property float|null            $eng_4_egt_5
 * @property float|null            $eng_4_egt_6
 * @property float|null            $yaw_trim_surface_position
 * @property float|null            $roll_trim_surface_position
 * @property float|null            $brake_pressure_system_1
 * @property float|null            $brake_pressure_system_2
 * @property float|null            $brake_pressure_system_3
 * @property float|null            $brake_pedal_application_left
 * @property float|null            $brake_pedal_application_right
 * @property float|null            $sideslip_angle
 * @property float|null            $eng_1_bleed_valve_position
 * @property float|null            $eng_2_bleed_valve_position
 * @property float|null            $eng_3_bleed_valve_position
 * @property float|null            $eng_4_bleed_valve_position
 * @property bool|null             $deicing_system_selection
 * @property float|null            $computed_cg
 * @property string|null           $ac_bus_1_status
 * @property string|null           $ac_bus_2_status
 * @property string|null           $ac_bus_3_status
 * @property string|null           $ac_bus_4_status
 * @property string|null           $dc_bus_1_status
 * @property string|null           $dc_bus_2_status
 * @property string|null           $dc_bus_3_status
 * @property string|null           $dc_bus_4_status
 * @property float|null            $system_1_volts
 * @property float|null            $system_2_volts
 * @property float|null            $system_1_amps
 * @property float|null            $system_2_amps
 * @property float|null            $apu_bleed_valve_position
 * @property float|null            $hydraulic_1_pressure
 * @property float|null            $hydraulic_2_pressure
 * @property float|null            $hydraulic_3_pressure
 * @property float|null            $hydraulic_4_pressure
 * @property string|null           $loss_cabin_pressure
 * @property string|null           $fms_failure
 * @property string|null           $hud_status
 * @property string|null           $synthetic_vision_display_status
 * @property string|null           $paravisual_display_status
 * @property float|null            $pitch_trim_control_selection
 * @property float|null            $roll_trim_control_selection
 * @property float|null            $yaw_trim_control_selection
 * @property float|null            $trailing_edge_flap_position
 * @property float|null            $leading_edge_flap_position
 * @property float|null            $spoiler_position
 * @property float|null            $spoiler_selection
 * @property float|null            $cockpit_control_wheel_input_force
 * @property float|null            $cockpit_control_column_input_force
 * @property float|null            $cockpit_rudder_pedal_left_input_force
 * @property float|null            $cockpit_rudder_pedal_right_input_force
 * @property-read \NGAFID\FlightID $flightIDData
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         flightParameters($parameters, $flightID)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         flightSummary($flightID)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereAcBus1Status($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereAcBus2Status($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereAcBus3Status($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereAcBus4Status($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereAfcsMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereAltimeter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereAoa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereApuBleedValvePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereAutopilot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereBrakePedalApplicationLeft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereBrakePedalApplicationRight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereBrakePressureSystem1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereBrakePressureSystem2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereBrakePressureSystem3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereCockpitControlColumnInputForce($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereCockpitControlWheelInputForce($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereCockpitRudderPedalLeftInputForce($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereCockpitRudderPedalRightInputForce($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereComputedCg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereCourse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereDcBus1Status($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereDcBus2Status($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereDcBus3Status($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereDcBus4Status($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereDeicingSystemSelection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereDme1Distance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereDme2Distance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereDriftAngle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEfisDisplayFormat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng1BleedValvePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng1Cht1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng1Cht2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng1Cht3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng1Cht4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng1Cht5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng1Cht6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng1Egt1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng1Egt2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng1Egt3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng1Egt4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng1Egt5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng1Egt6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng1FuelFlow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng1Mp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng1OilPress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng1OilPressLowWarning($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng1OilTemp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng1OverspeedWarning($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng1OvertempWarning($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng1Rpm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng1VibrationWarning($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng2BleedValvePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng2Cht1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng2Cht2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng2Cht3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng2Cht4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng2Cht5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng2Cht6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng2Egt1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng2Egt2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng2Egt3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng2Egt4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng2Egt5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng2Egt6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng2FuelFlow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng2Mp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng2OilPress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng2OilPressLowWarning($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng2OilTemp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng2OverspeedWarning($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng2OvertempWarning($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng2Rpm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng2VibrationWarning($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng3BleedValvePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng3Cht1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng3Cht2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng3Cht3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng3Cht4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng3Cht5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng3Cht6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng3Egt1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng3Egt2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng3Egt3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng3Egt4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng3Egt5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng3Egt6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng3FuelFlow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng3Mp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng3OilPress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng3OilPressLowWarning($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng3OilTemp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng3OverspeedWarning($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng3OvertempWarning($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng3Rpm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng3VibrationWarning($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng4BleedValvePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng4Cht1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng4Cht2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng4Cht3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng4Cht4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng4Cht5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng4Cht6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng4Egt1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng4Egt2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng4Egt3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng4Egt4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng4Egt5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng4Egt6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng4FuelFlow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng4Mp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng4OilPress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng4OilPressLowWarning($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng4OilTemp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng4OverspeedWarning($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng4OvertempWarning($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng4Rpm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereEng4VibrationWarning($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereFlight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereFmsFailure($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereFuelQuantityAux1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereFuelQuantityAux2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereFuelQuantityAux3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereFuelQuantityCgTrimTank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereFuelQuantityLeftMain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereFuelQuantityRightMain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereFuelQuantityTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereGlideslopeDeviation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereGroundSpoilerSpeedBrakePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereGroundspeed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereHeading($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereHudStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereHydraulic1Pressure($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereHydraulic2Pressure($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereHydraulic3Pressure($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereHydraulic4Pressure($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereHydraulicPressureLow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereIcing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereIndicatedAirspeed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereLandingGearPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereLateralAcceleration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereLateralControlInput($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereLateralControlSurfacePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereLeadingEdgeFlapPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereLeadingEdgeFlapSelection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereLocalizerDeviation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereLongitudinalAcceleration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereLossCabinPressure($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereMach($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereMarkerBeaconPassage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereMasterWarning($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereMfdDisplayFormat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereMslAltitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereNav1Freq($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereNav2Freq($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereOat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereObs1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereObs2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereParavisualDisplayStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         wherePhase($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         wherePitchAttitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         wherePitchControlInput($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         wherePitchControlSurfacePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         wherePitchTrimControlSelection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         wherePitchTrimSurfacePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         wherePressureAltitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         wherePrimaryNavSystemReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereProp1Angle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereProp2Angle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereProp3Angle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereProp4Angle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereRadioAltitudeActual($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereRadioAltitudeDerived($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereRadioTransmit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereRollAttitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereRollTrimControlSelection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereRollTrimSurfacePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereRudderPedalInput($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereSelectedAltitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereSelectedDecisionHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereSelectedFlightPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereSelectedHeading($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereSelectedMach($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereSelectedSpeed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereSelectedVerticalSpeed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereSideslipAngle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereSpoilerPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereSpoilerSelection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereStallWarning($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereStickPusher($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereStickShaker($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereSyntheticVisionDisplayStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereSystem1Amps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereSystem1Volts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereSystem2Amps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereSystem2Volts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereTas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereTerrainWarning($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereThrottleLeverPosition1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereThrottleLeverPosition2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereThrottleLeverPosition3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereThrottleLeverPosition4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereThrustCommand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereThrustReversePosition1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereThrustReversePosition2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereThrustReversePosition3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereThrustReversePosition4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereThrustTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereTrafficAlert($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereTrailingEdgeFlapPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereTrailingEdgeFlapSelection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereVerticalAcceleration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereVerticalAirspeed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereWeightOnWheels($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereWindDirection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereWindSpeed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereWindshear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereYawControlSurfacePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereYawTrimControlSelection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Main
 *         whereYawTrimSurfacePosition($value)
 * @mixin \Eloquent
 */
class Main extends Eloquent
{
    protected $table = 'main';

    public $timestamps = false;

    public function flightIDData()
    {
        return $this->belongsTo('NGAFID\FlightID', 'flight');
    }

    // @TODO: find all uses of this scope & refactor them to use scopeFlightParameters2()
    public function scopeFlightParameters($query, $parameters, $flightID)
    {
        return $query->select(\DB::raw($parameters))
            ->where('flight', '=', $flightID)
            ->orderBy('time', 'ASC');
    }

    public function scopeFlightParameters2($query, $parameters, $flightID)
    {
        return $query->select($parameters)
            ->where('flight', '=', $flightID)
            ->orderBy('time', 'ASC');
    }

    public function scopeFlightSummary($query, $flightID)
    {
        return $query->select(
            \DB::raw(
                "
                AVG(indicated_airspeed) AS 'avg_airspeed', MAX(indicated_airspeed) AS 'max_airspeed', MIN(indicated_airspeed) AS 'min_airspeed',
                AVG(msl_altitude) AS 'avg_msl', MAX(msl_altitude) AS 'max_msl', MIN(msl_altitude) AS 'min_msl',
                AVG(eng_1_rpm) AS 'avg_eng_rpm', MAX(eng_1_rpm) AS 'max_eng_rpm', MIN(eng_1_rpm) AS 'min_eng_rpm',
                AVG(pitch_attitude) AS 'avg_pitch', MAX(pitch_attitude) AS 'max_pitch', MIN(pitch_attitude) AS 'min_pitch',
                AVG(roll_attitude) AS 'avg_roll', MAX(roll_attitude) AS 'max_roll', MIN(roll_attitude) AS 'min_roll',
                AVG(vertical_airspeed) AS 'avg_vert', MAX(vertical_airspeed) AS 'max_vert', MIN(vertical_airspeed) AS 'min_vert'
                "
            )
        )
            ->where('flight', '=', $flightID)
            ->groupBy('flight');
    }
}

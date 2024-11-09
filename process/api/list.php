<?php

$API_PATH = "https://api.duffel.com/";
$TOKEN = "duffel_test_ZgNwOQ6vA1oMHPy7r1UY5j5VI4xItZuwRKxOcpcsjMj";


function curlRequestPost($url, $TOKEN, $fields)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);


    $headers = array(
        "Content-type: application/json",
        "Authorization: Bearer $TOKEN",
        "Duffel-Version: beta"
    );
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

    //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}


function curlRequestGet($url, $TOKEN)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);


    $headers = array(
        "Content-type: application/json",
        "Authorization: Bearer $TOKEN",
        "Duffel-Version: beta"
    );
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}



function offerProcessOneWay($offers = [])
{

    if (!$offers) {
        return '<div class="no-data">
        <i class="fa fa-window-close"></i> <p>No data found</p>
        </div>';
    }

    $hold = "";
    foreach ($offers as $item) {
        $segment_count = count($item->slices[0]->segments);
        $segment_index = ($segment_count - 1);

        $hold .= '<tr class="flight-item" data-offer-id="' . $item->id . '">
      <td>
          <div class="widget-26-job-emp-img">
              <img src="' . $item->owner->logo_symbol_url . '" alt="Company" title="' . $item->owner->name . '"/>
          </div>
      </td>

      <td>
      <div class="widget-26-job-title">
          <!-- <a href="#"> -->
          <div class="flight-deatils">
          <div class="flight-origin">
          <p>' . $item->slices[0]->origin->iata_code . '</p>
          <p class="timetable"> ' . timeFormat($item->slices[0]->segments[0]->departing_at) . '</p>
          </div>
          
          <div class="flight-duration">
          <div class="flight-stops">' . durationFormat($item->slices[0]->duration) . ' ( ' . layover($segment_count) . ' )</div>
          <div class="flight-details__line"></div>
          <div class="flight-details__duration">' . dateFormat($item->slices[0]->segments[$segment_index]->departing_at) . '</div>
          </div>

          <div class="flight-origin">
          <p>' . $item->slices[0]->destination->iata_code . '</p>
          <p class="timetable"> ' . timeFormat($item->slices[0]->segments[$segment_index]->arriving_at) . '</p>
          </div>


          

          </div>
          <!-- </a> -->
          <!--<p class="m-0"><a href="#" class="employer-name">Axiom Corp.</a> <span class="text-muted time">1 days ago</span></p>-->
      </div>
    </td>
    
    <td align="center">
        <div class="widget-26-job-salary">$ ' . numberFormat($item->total_amount) . ' ' . $item->total_currency . '</div>
    </td>
    <td align="center">
        <div class="widget-26-job-category bg-soft-base">            
            <span>' . $item->slices[0]->segments[0]->passengers[0]->cabin_class_marketing_name . '</span>
        </div>
    </td>
    <td>
        <div class="widget-26-job-starred">
        <button type="submit" class="btn btn-primary float-right book-button" id="flight_search">Book</button>
        </div>
    </td>      

  </tr>';
    }

    return $hold;
}

function offerProcessRound($offers)
{

    if (!$offers) {
        return '<div class="no-data">
        <i class="fa fa-window-close"></i> <p>No data found</p>
        </div>';
    }


    $hold = "";
    foreach ($offers as $item) {
        $segment_count = count($item->slices[0]->segments);
        $segment_index = ($segment_count - 1);

        $hold .= '<tr class="flight-item" data-offer-id="' . $item->id . '">
      <td>
          <div class="widget-26-job-emp-img">
              <img src="' . $item->owner->logo_symbol_url . '" alt="Company 123" title="' . $item->owner->name . '"/>
          </div>
      </td>

      <td>
      <div class="widget-26-job-title">
          <!-- <a href="#"> -->
          <div class="flight-deatils">
          <div class="flight-origin">
          <p>' . $item->slices[0]->origin->iata_code . '</p>
          <p class="timetable"> ' . timeFormat($item->slices[0]->segments[0]->departing_at) . '</p>
          </div>
          
          <div class="flight-duration">
          <div class="flight-stops">' . durationFormat($item->slices[0]->duration) . ' ( ' . layover($segment_count) . ' )</div>
          <div class="flight-details__line"></div>
          <div class="flight-details__duration">' . dateFormat($item->slices[0]->segments[$segment_index]->departing_at) . '</div>
          </div>

          <div class="flight-origin">
          <p>' . $item->slices[0]->destination->iata_code . '</p>
          <p class="timetable"> ' . timeFormat($item->slices[0]->segments[$segment_index]->arriving_at) . '</p>
          </div>
          </div>

          <hr/>          
          
          <div class="flight-deatils">
          <div class="flight-origin">
          <p>' . $item->slices[1]->origin->iata_code . '</p>
          <p class="timetable"> ' . timeFormat($item->slices[1]->segments[0]->departing_at) . '</p>
          </div>
          
          <div class="flight-duration">
          <div class="flight-stops">' . durationFormat($item->slices[1]->duration) . ' ( ' . layover($segment_count) . ' )</div>
          <div class="flight-details__line"></div>
          <div class="flight-details__duration">' . dateFormat($item->slices[1]->segments[$segment_index]->departing_at) . '</div>
          </div>

          <div class="flight-origin">
          <p>' . $item->slices[1]->destination->iata_code . '</p>
          <p class="timetable"> ' . timeFormat($item->slices[1]->segments[$segment_index]->arriving_at) . '</p>
          </div>
          </div>        

          <!-- </a> -->
          <!--<p class="m-0"><a href="#" class="employer-name">Axiom Corp.</a> <span class="text-muted time">1 days ago</span></p>-->
      </div>
    </td>
    
    <td align="center">
        <div class="widget-26-job-salary">$ ' . numberFormat($item->total_amount) . ' ' . $item->total_currency . '</div>
    </td>
    <td align="center">
        <div class="widget-26-job-category bg-soft-base">            
            <span>' . $item->slices[0]->segments[0]->passengers[0]->cabin_class_marketing_name . '</span>
        </div>
    </td>
    <td>
        <div class="widget-26-job-starred">
        <button type="submit" class="btn btn-primary float-right book-button" id="flight_search">Book</button>
        </div>
    </td>      

  </tr>';
    }

    return $hold;
}


function durationFormat($duration)
{
    //if ($count == 1) {
    //$stag = $duration[0]->duration;
    $d = new DateInterval($duration);
    return $d->format('%h h %i m');
    // } else {

    //   $e = new DateTime('00:00');
    //   $f = clone $e;
    //   foreach ($duration as $key => $duration_step) {
    //     $stag = new DateInterval($duration[$key]->duration);
    //     $e->add($stag);
    //   }

    //   return $f->diff($e)->format("%H h %I m");
    // }
}


function dateFormat($date)
{
    $timestamp = strtotime($date);
    return $new_date = date('d-M-Y', $timestamp);
}


function timeFormat($date)
{
    $timestamp = strtotime($date);
    return $new_date = date('h:s A', $timestamp);
}


function numberFormat($value)
{
    return number_format($value, 2);
}


function numberFormatWc($value)
{
    return number_format((float)$value, 2, '.', '');
}



function price_calculate($price)
{
    $val = (($price + 10) / (1 - 0.029));
    $current_price = numberFormat($val);

    return $current_price;
}


function price_calculate_wf($price)
{
    $val = (($price + 10) / (1 - 0.029));
    $current_price = numberFormatWc($val);

    return $current_price;
}




function layover($value)
{

    switch ($value) {
        case 1:
            $message = 'Direct';
            break;
        case 2:
            $message = '1 Stop';
            break;
        case 3:
            $message = '2 Stop';
            break;
        case 4:
            $message = '3 Stop';
            break;
        default:
            $message = 'Direct';
    }

    return $message;
}


function tripModeCalculate($value)
{
    switch ($value) {
        case 1:
            $message = 'Oneway';
            break;
        case 2:
            $message = 'Roundtrip';
            break;
        default:
            $message = 'Oneway';
    }

    return $message;
}

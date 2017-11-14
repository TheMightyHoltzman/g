<?php
/**
 * Created by PhpStorm.
 * User: heiko
 * Date: 27.06.17
 * Time: 15:14
 *
 * NOTICE: no data-validation, since you wanted this quick
 */

// for brevity i am assuming there is no malicious data inserted (though one never should)
$api          = $argv[1];
$start_time   = (new DateTime($argv[2]))->getTimestamp();
$end_time     = (new DateTime($argv[3]))->getTimestamp();
$nr_travelers = intval($argv[4]);

// a handler would be a better idea, but this is more readable ;-)
$data = file_get_contents($api);

if ($data) {
    $data = json_decode($data, true);
    $data = $data['product_availabilities'];

    $data_filtered = array_filter($data, function($item) use ($start_time, $end_time, $nr_travelers) {
        $item_start_time   = (new DateTime($item['activity_start_datetime']))->getTimestamp();
        $activity_duration = intval($item['activity_duration_in_minutes']) * 60;
        $item_end_time     = $item_start_time + $activity_duration;
        $places_available  = $item['places_available'];

        return $start_time <= $item_start_time && $end_time >= $item_end_time && $places_available >= $nr_travelers;
    });

    // sort the data according to product-id, if same, start-datetime
    usort($data_filtered, function($itemA, $itemB) {
        // first product-id...
        $idA = $itemA['product_id'];
        $idB = $itemB['product_id'];

        if ($idA !== $idB) {
            return $idA - $idB;
        }

        // .. then start-date
        $startA = (new DateTime($itemA['activity_start_datetime']))->getTimestamp();
        $startB = (new DateTime($itemB['activity_start_datetime']))->getTimestamp();

        return $startA - $startB;
    });

    // aggregate data
    $data_aggregated         = [];
    $aggregation_product_id  = null;
    foreach ($data_filtered as $product) {
        $product_id = $product['product_id'];
        if ($aggregation_product_id !== $product_id) {
            $aggregation_product_id = $product_id;
        }
        if (!array_key_exists($aggregation_product_id, $data_aggregated)) {
            $data_aggregated[$aggregation_product_id] = [
                'product_id'           => $aggregation_product_id,
                'available_starttimes' => [],
            ];
        }

        $data_aggregated[$aggregation_product_id]['available_starttimes'][] = $product['activity_start_datetime'];
    }

    // reset keys...
    $data_aggregated = array_values($data_aggregated);

    var_dump(json_encode($data_aggregated));
    return json_encode($data_aggregated);

    function doWhatever() {
        $morning = new DateTime();
        $morning->format("H");
    }
}

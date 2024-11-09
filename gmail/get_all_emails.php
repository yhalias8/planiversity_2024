<?php
require '../vendor/autoload.php';

include_once("../config.ini.php");
include("common.php");

// Print the labels in the user's account.
$list = $service->users_messages->listUsersMessages('me', ['maxResults' => 10000, 'q' => 'category:primary']);

if (count($list->getMessages()) == 0) {
    print "No labels found.\n";
} else {
    foreach ($list->getMessages() as $message) {
        $optParamsGet2['format'] = 'minimal';
        $single_message = $service->users_messages->get('me', $message->id, ['format' => 'metadata', 'metadataHeaders' => ['To', 'X-Original-To', 'X-Original-From', 'From', 'Reply-To', 'Subject']]);
        /*echo "<pre>";
        print_r($single_message);
        echo "</pre>";
        die;*/
        $from = $single_message->getPayload()->getHeaders()[0]->value;
        $subject = $single_message->getPayload()->getHeaders()[1]->value;
        $reply_to = $single_message->getPayload()->getHeaders()[2]->value;
        if ($reply_to != "do-not-reply@tripit.com") {
            $booking_reference = get_string_between($subject, 'Itinerary #', ')');
            $email = get_string_between($from, '<', '>');
            if (!empty($booking_reference)) {
                include("../class/class.TripitItinerary.php");
                $trip = new TripitItinerary();
                $trip->put_data(0, $booking_reference, $email);
            }
        }
    }
}
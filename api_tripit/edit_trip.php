<?php

// Copyright 2008-2018 Concur Technologies, Inc.
//
// Licensed under the Apache License, Version 2.0 (the "License"); you may
// not use this file except in compliance with the License. You may obtain
// a copy of the License at
//
//     http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
// WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
// License for the specific language governing permissions and limitations
// under the License.

include_once('tripit.php');
include_once('common.php');

$tripit = new TripIt($oauth_credential);

$data = "<Request>
  <Trip>
    <start_date>2019-12-19</start_date>
    <end_date>2019-12-27</end_date>
    <primary_location>New York, NY</primary_location>
    <PrimaryLocationAddress>
        <address>New York, NY</address>
        <city>New York</city>
        <state>NY</state>
        <country>US</country>
        <latitude>40.714269</latitude>
        <longitude>-74.005973</longitude>
    </PrimaryLocationAddress>
  </Trip>
</Request>";

/*print_r($tripit->create($data));*/
print_r($tripit->get_trip(294279643));

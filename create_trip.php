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

$json = <<<EOT
{"Trip":
   {"start_date":"2021-12-01",
    "end_date":"2021-12-10",
    "primary_location":"New York, NY"
   }
}
EOT;

print_r($tripit->create($json, 'json'));

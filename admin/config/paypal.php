<?php
return [
    //'client_id' => 'AWDljO0aE2niUp1U2atYIXp--9DW_M-9OkFMFemTC8PxWPrTBHd4XiKMTFScOMD_C6xAJxjBkN21tOnN',
    //'secret' => 'EEtAk9Fnq-IBwJTxUpKUYBxLCo8iP9Ztj4D3TaCIleTg92_SX0Eyh1dWzRGq9RmNIOHXQFDuDRdoNw7D',
    'client_id' => 'AdKZ80BsrwbM2j2NhIeprp2JCSjnA2S_q5wdLO_OkzXBsE5tgge32PqjOr4VBNIxLw9Ks8qJ7f0-ZRHO',
    'secret' => 'EMf3mqRdKRn6XyVqYCt-3V4moKedu_463fHmLPx8InJuiTYVPbeVQdVHJQUC2JCc1tZw4Am8wtYvEAl5',
    'settings' => array(
        'mode' => 'live',
        'http.ConnectionTimeOut' => 1000,
        'log.LogEnabled' => true,
        'log.FileName' => storage_path() . '/logs/paypal.log',
        'log.LogLevel' => 'FINE'
    ),
];

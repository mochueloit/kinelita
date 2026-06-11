<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Notificaciones de ranking por correo
    |--------------------------------------------------------------------------
    |
    | batch_size: cantidad de correos por job en cola
    | batch_delay: segundos de espera entre cada lote para no saturar SMTP
    |
    */

    'notification_batch_size' => (int) env('KINELA_NOTIFICATION_BATCH_SIZE', 5),

    'notification_batch_delay' => (int) env('KINELA_NOTIFICATION_BATCH_DELAY', 15),

];

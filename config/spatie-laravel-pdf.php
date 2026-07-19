<?php

return [
    /*
     * The only valid value for this option is `browsershot`.
     * Everything else will default to `dompdf`.
     */
    'pdf_engine' => 'dompdf',

    'default_paper_format' => 'a4',

    'orientation' => 'portrait',

    'default_font' => 'Helvetica',

    'default_font_size' => '12px',

    /*
     * The pdf generator disk. This disk will be used to store temporary files.
     */
    'disk' => 'local',
];

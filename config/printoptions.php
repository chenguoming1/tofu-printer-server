<?php

return [
    [
        "name" => "basic",
        "display_name" => "Basic Copy",
        "option_items" => [
            [
                "name" => "quantity",
                "display_name" => "No of copies",
                "type" => "number",
                "min" => 1,
                "max" => 100
            ],
            [
                "name" => "color",
                "display_name" => "Color Mode",
                "type" => "select",
                "options" => [
                    [
                        "name" => "color",
                        "display_name" => "Color",
                        "icon" => ""
                    ],
                    [
                        "name" => "mono",
                        "display_name" => "Mono",
                        "icon" => ""
                    ]
                ]
            ],
            [
                "name" => "paper_size",
                "display_name" => "Original Size",
                "type" => "select",
                "options" => [
                    [
                        "name" => "a3",
                        "display_name" => "A3"
                    ],
                    [
                        "name" => "a4",
                        "display_name" => "A4"
                    ],
                    [
                        "name" => "a5",
                        "display_name" => "A5"
                    ]
                ]
            ],
            [
                "name" => "orientation",
                "display_name" => "Original Orientation",
                "type" => "select",
                "options" => [
                    [
                        "name" => "portrait",
                        "display_name" => "Upright Images"
                    ],
                    [
                        "name" => "landscape",
                        "display_name" => "Sideway Images"
                    ]
                ]
            ],
            [
                "name" => "layout",
                "display_name" => "N-Up",
                "type" => "select",
                "options" => [
                    [
                        "name" => "1up",
                        "display_name" => "1 Up"
                    ],
                    [
                        "name" => "2up",
                        "display_name" => "2 Up"
                    ]
                ]
            ],
            [
                "name" => "sides",
                "display_name" => "Duplex",
                "type" => "select",
                "options" => [
                    [
                        "name" => "single",
                        "display_name" => "1 Sided"
                    ],
                    [
                        "name" => "duplex",
                        "display_name" => "2 Sided"
                    ]
                ]
            ]
        ]
    ],
    [
        "name" => "id_card",
        "display_name" => "ID Copy",
        "option_items" => [
            [
                "name" => "quantity",
                "display_name" => "No of copies",
                "type" => "number",
                "min" => 1,
                "max" => 100
            ],
            [
                "name" => "color",
                "display_name" => "Color Mode",
                "type" => "select",
                "options" => [
                    [
                        "name" => "color",
                        "display_name" => "Color",
                        "icon" => ""
                    ],
                    [
                        "name" => "mono",
                        "display_name" => "Mono",
                        "icon" => ""
                    ]
                ]
            ],
            [
                "name" => "paper_size",
                "display_name" => "Original Size",
                "type" => "select",
                "options" => [
                    [
                        "name" => "idcard",
                        "display_name" => "NRIC/ID Card"
                    ]
                ]
            ],
            [
                "name" => "darkness",
                "display_name" => "Darkness",
                "type" => "number_slider",
                "min" => 1,
                "max" => 100
            ]
        ]
    ],
    [
        "name" => "passport",
        "display_name" => "Passport Copy",
        "option_items" => [
            [
                "name" => "quantity",
                "display_name" => "No of copies",
                "type" => "number",
                "min" => 1,
                "max" => 100
            ],
            [
                "name" => "color",
                "display_name" => "Color Mode",
                "type" => "select",
                "options" => [
                    [
                        "name" => "color",
                        "display_name" => "Color",
                        "icon" => ""
                    ],
                    [
                        "name" => "mono",
                        "display_name" => "Mono",
                        "icon" => ""
                    ]
                ]
            ],
            [
                "name" => "paper_size",
                "display_name" => "Original Size",
                "type" => "select",
                "options" => [
                    [
                        "name" => "passport",
                        "display_name" => "Passport"
                    ]
                ]
            ],
            [
                "name" => "darkness",
                "display_name" => "Darkness",
                "type" => "number_slider",
                "min" => 1,
                "max" => 100
            ]
        ]
    ]
];
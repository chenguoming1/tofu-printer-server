<?php
return [
    "paper_sizes" => [
      [
        "name" => "a3",
        "display_name" => "A3",
        "type" => "paper_size",
      ],
      [
        "name" => "a4",
        "display_name" => "A4",
        "type" => "paper_size",
      ],
      [
        "name" => "a5",
        "display_name" => "A5",
        "type" => "paper_size",
      ],
    ],
    "colors" => [
      [
        "name" => "mono",
        "display_name" => "Mono",
        "type" => "color",
        "icon" => PHP_SAPI === 'cli' ? false : url("/icons/mono.png"),
      ],
      [
        "name" => "color",
        "display_name" => "Color",
        "type" => "color",
        "icon" => PHP_SAPI === 'cli' ? false : url("/icons/color.png"),
      ],
    ],
    "sides" => [
      [
        "name" => "single",
        "display_name" => "Single Sided",
        "type" => "side",
        "price" => 0.0,
      ],
      [
        "name" => "double",
        "display_name" => "Double Sided",
        "type" => "side",
        "price" => 0.0,
      ],
    ],
];
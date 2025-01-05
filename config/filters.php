<?php
return [
    "job_types" => [
      [
        "name" => "copy",
        "display_name" => "Copy",
        "type" => "job_type",
      ],
      [
        "name" => "print",
        "display_name" => "Print",
        "type" => "job_type",
      ],
      [
        "name" => "scan",
        "display_name" => "Scan",
        "type" => "job_type",
      ],
    ],
    "sub_categories" => [
      [
        "name" => "basic_copy",
        "display_name" => "Basic Copy",
        "type" => "sub_category",
        "options" => "basic_copy_options",
      ],
      [
        "name" => "id_copy",
        "display_name" => "ID Copy",
        "type" => "sub_category",
        "options" => "id_copy_options",
      ],
      [
        "name" => "passport",
        "display_name" => "Passport",
        "type" => "sub_category",
        "options" => "passport_options",
      ],
    ],
    "payment_types" => [
      [
        "name" => "cash",
        "display_name" => "Cash",
        "type" => "payment_type",
      ],
      [
        "name" => "card",
        "display_name" => "Card",
        "type" => "payment_type",
      ],
    ],
    "job_statuses" => [
      [
        "name" => "in_progress",
        "display_name" => "In Progress",
        "type" => "job_status",
      ],
      [
        "name" => "done",
        "display_name" => "Done",
        "type" => "job_status",
      ],
      [
        "name" => "cancelled",
        "display_name" => "Cancelled",
        "type" => "job_status",
      ],
    ],
    "payment_statuses" => [
      [
        "name" => "pending",
        "display_name" => "Pending",
        "type" => "payment_status",
      ],
      [
        "name" => "paid",
        "display_name" => "Paid",
        "type" => "payment_status",
      ],
      [
        "name" => "unpaid",
        "display_name" => "Unpaid",
        "type" => "payment_status",
      ],
      [
        "name" => "failed",
        "display_name" => "Failed",
        "type" => "payment_status",
      ],
      [
        "name" => "success",
        "display_name" => "Success",
        "type" => "payment_status",
      ],
    ],
];

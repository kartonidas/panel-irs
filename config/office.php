<?php

return [
    "lists" => [
        "size" => 50,
        "sizes" => [
            20,
            50,
            100
        ]
    ],
    "permissions" => [
        "admin" => [
            "customers" => [
                "module" => "Klienci",
                "operation" => ["list", "create", "update", "delete"]
            ],
            "permissions" => [
                "module" => "Uprawnienia",
                "operation" => ["list", "create", "update", "delete"]
            ],
            "users" => [
                "module" => "Pracownicy",
                "operation" => ["list", "create", "update", "delete"]
            ],
        ],
        "employee" => [
            "invoicess" => [
                "module" => "Faktury",
                "operation" => ["list", "create", "update", "delete"]
            ],
            "documents" => [
                "module" => "Dokumenty",
                "operation" => ["list", "create", "update", "delete"]
            ],
            "others" => [
                "module" => "Inne",
                "operation" => ["list", "create", "update", "delete"]
            ],
        ]
    ],
];
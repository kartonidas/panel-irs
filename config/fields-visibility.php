<?php

return [
    "saldo" => [
        "label" => "Saldo",
        "fields" => [
            "saldo_total" => "Saldo zadłużenia total",
            "saldo" => "Saldo zadłużenia (poszczególne składniki salda)",
        ],
    ],
    "wplaty" => [
        "label" => "Wpłaty w sprawie",
        "fields" => [
            "data" => "Data wpłaty",
            "kwota" => "Kwota wpłaty",
            "suma_wplat" => "Suma wpłat w sprawie",
        ],
    ],
    "dokumenty" => [
        "label" => "Dokumenty w sprawie",
        "fields" => [
        ],
    ],
    "dluznik" => [
        "label" => "Dane dłużnika",
        "fields" => [
            "debtor_id_data" => "Dane identyfikacyjne dłużnika",
            "debtor_contact" => "Dane teleadresowe dłużnika",
        ],
    ],
    "faktury" => [
        "label" => "Faktury / usługi",
        "fields" => [
            "date_of_issue" => "Data wystawienia",
            "due_date" => "Data wymagalności",
            "type" => "Typ",
            "number" => "Numer",
            "name" => "Nazwa",
            "amount" => "Kwota faktury",
        ],
    ]
];
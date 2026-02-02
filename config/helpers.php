<?php

/**
 * Kitap adını URL uyumlu hale getirir
 * Örnek: "Suç ve Ceza" -> "suc-ve-ceza"
 */
function slugify(string $text): string
{
    $text = mb_strtolower($text, 'UTF-8');

    $map = [
        'ç' => 'c',
        'ğ' => 'g',
        'ı' => 'i',
        'ö' => 'o',
        'ş' => 's',
        'ü' => 'u'
    ];

    $text = strtr($text, $map);

    // Harf ve rakam dışındaki her şeyi tire yap
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);

    return trim($text, '-');
}

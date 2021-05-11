<?php

function getKeyString($array): string {
    return implode(', ', array_keys($array));
}

function getKeyTagString($array): string {
    return implode(', ', array_map(function ($item) {
        return ':' . $item;
    }, array_keys($array)));
}

function getTagValues($array): array {
    return array_combine(array_map(function ($item) {
        return ':' . $item;
    }, array_keys($array)), array_values($array));
}

function getKeyValuesString($array): string {
    return implode(', ', array_map(function ($item) {
        return $item . '=:' . $item;
    }, array_keys($array)));
}
<?php
namespace OpenSB;

global $twig, $database;

use SquareBracket\Utilities;
use SquareBracket\UploadQuery;

$upload_query = new UploadQuery($database);
$uploads = $upload_query->query("v.id DESC", 2);

$data["color_types"] = [
    "primary",
    "secondary",
    "success",
    "danger",
    "warning"
];

$data["color_types_admin"] = [
    "banned-other-unbanned",
    "unbanned-other-banned",
    "banned",
    "shadow-banned",
    "unbanned-other-unbanned",
    "partner",
    "staff"
];

$data["icons"] = [
    "star-full",
    "star-half",
    "star-empty",
    "partner",
    "staff",
    "b-danger",
    "b-primary",
    "b-secondary",
    "b-success",
    "b-warning",
    "search",
    "placeholder",
    "hamburger",
];

$data["uploads"] = Utilities::makeUploadArray($database, $uploads);

echo $twig->render('design_test.twig', $data);
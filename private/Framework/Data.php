<?php

namespace OpenSB\Framework;

interface Data
{
    /**
     * this is where all the database fetching stuff should happen
     */
    public function __construct();

    /**
     * returns cleaner array to be used by the frontend
     *
     * @return array
     */
    public function getData(): array;

    /**
     * modify the data, like when an upload gets edited by its author for example.
     *
     * @param $data
     * @return bool
     */
    public function modifyData($data): bool;
}
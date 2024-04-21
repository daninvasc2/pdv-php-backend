<?php

interface ValidatorInterface
{
    /**
     * Validate data
     * 
     * @param mixed $data
     * 
     * @throws Exception
     * 
     * @return void
     */
    public static function validate(mixed $data): void;
}
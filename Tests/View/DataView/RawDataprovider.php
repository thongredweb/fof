<?php

class RawDataprovider
{
    public static function getTest__construct()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'isCli' => false
                )
            ),
            array(
                'case' => 'We are not in CLI',
                'permissions' => (object)array(
                    'create' => false,
                    'edit' => false,
                    'editown' => false,
                    'editstate' => false,
                    'delete' => false,
                )
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'isCli' => true
                )
            ),
            array(
                'case' => 'We are in CLI',
                'permissions' => (object)array(
                    'create' => true,
                    'edit' => true,
                    'editown' => true,
                    'editstate' => true,
                    'delete' => true,
                )
            )
        );

        return $data;
    }
}
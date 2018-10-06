<?php

main::start( "example.csv");

class main  {

    static public function start($filename) {

        $records = csv::getRecords($filename);

    }

}


class csv {

    static public function getRecords($filename) {

        $file = fopen($filename,"r");

        while(! feof($file))
        {
            $record = fgetcsv($file);

            $records[] = recordFactory::create($record);
        }

        fclose($file);
        return $records;
    }
}

class record {

    public function  __construct(Array $record = null)
    {

        print_r($record);
        $this->createProperty();

    }

    public function createProperty($name = 'first', $value = 'aheel') {

        $this->{$name} = $value;

    }

}

class recordFactory {

    public static function create (array $array = null) {

        $record = new record($array);

        return $record;

    }
}
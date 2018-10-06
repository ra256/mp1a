<?php









main::start("example.csv");
class main  {
    static public function start($filename) {
        $records = csv::getRecords($filename);
        $table = html::generateTable($records);
    }
}
class html {

    private static $jsURLs = [
        'https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js',
        'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js',
    ];
    private static $cssURLs = [
        'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'
    ];

    public static function printTableStyles() {
        print "<style>\n";
        print "th { background-color : black; color : white }\n";
        print ".table-zebra { background-color : lightgrey; color : black }\n";
        print "</style>\n";
    }
    public static function printExternalUrls()
    {
        foreach (html::$cssURLs as $cssUrl) {
            print "<link rel='stylesheet' href='$cssUrl'>\n";
        }
        foreach (html::$jsURLs as $jsUrl) {
            print "<script src='$jsUrl'></script>\n";
        }
    }

    public  static function print_row($cells, $rowstart, $rowend, $cellstart, $cellend) {
        $ncells = count($cells);
        echo $rowstart;
        for ($c=0; $c < $ncells; $c++) {
            $value = "&nbsp;";
            if(!empty($cells[$c])) {
                $value = $cells[$c];
            }
            echo $cellstart.$value.$cellend."\n";
        }
        echo $rowend;
    }

    public static function generateTable($records) {
        $count = 0;
        print "<!DOCTYPE html>\n";
        print "<html lang='en'>\n";
        print "<head>\n";
        print("<title>CSV Data to HTML Table </title>\n");

        html::printExternalUrls();
        html::printTableStyles();

        print "</head>\n";
        print "<body>\n";
        print('<table class="table table-striped table-zebra"\n');

        foreach ($records as $record) {
            if($count == 0) {
                $array = $record->returnArray();
                $fields = array_keys($array);
                $values = array_values($array);
                html::print_row($fields, "<thead><tr>", "</tr></thead><tbody>", "<th>", "</th>");
                html::print_row($values, "<tr>", "</tr>", "<td>", "</td>");
            } else {
                $array = $record->returnArray();
                $values = array_values($array);
                html::print_row($values, "<tr>", "</tr>", "<td>", "</td>");
            }
            $count++;
        }
        print('</tbody></table>\n');
        print "</body>\n";
        print "</html>";
    }   }


class csv {
    static public function getRecords($filename) {
        $file = fopen($filename,"r");
        $fieldNames = array();
        $count = 0;
        while(! feof($file))
        {
            $record = fgetcsv($file);
            if($count == 0) {
                $fieldNames = $record;
            } else {
                $records[] = recordFactory::create($fieldNames, $record);
            }
            $count++;
        }
        fclose($file);
        return $records;
    }
}
class record {
    public function __construct(Array $fieldNames = null, $values = null )
    {
        $record = array_combine($fieldNames, $values);
        foreach ($record as $property => $value) {
            $this->createProperty($property, $value);
        }
    }
    public function returnArray() {
        $array = (array) $this;
        return $array;
    }
    public function createProperty($name = 'first', $value = 'Raheel') {
        $this->{$name} = $value;
    }
}
class recordFactory {
    public static function create(Array $fieldNames = null, Array $values = null) {
        $record = new record($fieldNames, $values);
        return $record;
    }
}

<?php 
class ExcelExporter {

    const CRLF = "\r\n";

    /**
     * Output active record resultset to an xml based excel file
     *
     * @param $data - active record data set
     * @param $header - boolean show/hide header
     * @param $filename - name of output filename
     */
    public static function sendAsXLS($data, $header, $filename) {
        $export = self::xls($data, $header);
        self::sendHeader($filename, strlen($export), 'vnd.ms-excel');
        echo $export;
    }

   
     /* Send file header 
     * 
     * @param $filename - filename for created file
     * @param $length - size of file
     * @param $type - mime type of exported data
     */
    private static function sendHeader($filename, $length, $type='octet-stream') {
        header("Content-type: application/$type");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-length: $length");
        header("Pragma: no-cache");
        header("Expires: 0");
    }

  
    /**
     * Private method to create xls string from active record data set
     *
     * @param  $data - active record data set
     * @param  $header - boolean to show/hide header
     */
    private static function xls($data, $header) {
        $str = "<?xml version='1.0' encoding='utf-8'?>" . self::CRLF .
               "<Workbook xmlns='urn:schemas-microsoft-com:office:spreadsheet' xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:x='urn:schemas-microsoft-com:office:excel' xmlns:ss='urn:schemas-microsoft-com:office:spreadsheet' xmlns:html='http://www.w3.org/TR/REC-html40'>" . self::CRLF .
               "<ss:Worksheet ss:Name='Sheet1'>" . self::CRLF .
               "<ss:Table>" . self::CRLF;

        foreach($data as $row) {
            // check if header row required
            if ($header) {
                $str .= "<ss:Row>" . self::CRLF;
                foreach($row->attributes as $k=>$v) {
                	//saltarse el id
                	if($k != 'id' && $k != 'propio_arrendado' && $k != 'origen_destino' && $k != 'faena_id' && $k != 'maquina_id' && $k != 'maquina_camion' && $k != 'chofer_id'){
                		if($k == 'vehiculo'){
	                		$str .= self::xlsCell("Veh??culo");
	                	}
                		else if($k == 'pu'){
	                		$str .= self::xlsCell("Valor del Flete");
	                	}
                		else if($k == 'maquina'){
	                		$str .= self::xlsCell("Veh??culo o M??quina");
	                	}
                		else if($k == 'faena'){
	                		$str .= self::xlsCell("Centro de Gesti??n");
	                	}
                		else if($k == 'origen_destino_nombre'){
	                		$str .= self::xlsCell("Origen Destino");
	                	}
	                    else{
	                    	$str .= self::xlsCell($k);	
	                    }
                	}
                	
                }
                $str .= "</ss:Row>" . self::CRLF;
                // reset header
                $header = false;
            }
            $str .= "<ss:Row>" . self::CRLF;
            // output values in row
            foreach($row->attributes as $k=>$v) {
            	if($k!='id' && $k!='propio_arrendado' && $k != 'origen_destino' && $k != 'faena_id' && $k != 'maquina_id' && $k != 'maquina_camion' && $k != 'chofer_id'){
	            	$str .= self::xlsCell($v);
            	}
            }
            $str .= "</ss:Row>" . self::CRLF;
        }
        $str .= "</ss:Table>" . self::CRLF .
                   "</ss:Worksheet>" . self::CRLF .
                   "</ss:Workbook>";
        return $str;
    }

    /**
     * Private method to create xls cell string
     *
     * @param <type> $v - value to encode in cell
     */
    private static function xlsCell($v) {
        $t = (is_numeric($v)) ? "Number" : "String";
        return "<ss:Cell><ss:Data ss:Type='" . $t . "'>" . $v . "</ss:Data></ss:Cell>" . self::CRLF;
    }

}

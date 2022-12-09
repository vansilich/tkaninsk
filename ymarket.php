<?php
    $inc_path = "admin/";
    include($inc_path."class/header.php");
    $root_path = "";

    $q = new query();
    ini_set("display_errors", 1);


    $data = '';
    function print_header() {
        global $eur_rate;
        $text = '';
        $current_date = date("Y-m-d H:i",time());
        $text .= "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
        $text .= "<!DOCTYPE yml_catalog SYSTEM \"shops.dtd\">\n";
        $text .= "<yml_catalog date=\"".$current_date."\">\n";

        $text .= "<shop>\n";
        $text .= "<name>tkaninsk</name>\n";
        $text .= "<company>tkaninsk</company>\n";
        $text .= "<url>https://tkaninsk.com/</url>\n";

        $text .= "<currencies>\n";
        $text .= "	<currency id=\"RUR\" rate=\"1\"/>\n";
        $text .= "</currencies>\n";
        return $text;
    }

    function print_footer() {
        $text = '';
        $text .= "</shop>\n";
        $text .= "</yml_catalog>";
        return $text;
    }



    function convert_text($text){
        $text = str_replace('&','&amp;',$text);
        $text = str_replace('"','&quot;',$text);
        $text = str_replace('>','&gt;',$text);
        $text = str_replace('<','&lt;',$text);
        $text = str_replace('"','&quot;',$text);
        $text = str_replace("'",'&apos;',$text);
        return $text;
    }
    function covert_html_to_text_all($document) {
// $document должен содержать HTML-документ.
// Здесь будут удалены тэги HTML, разделы javascript
// и пустое пространство. Также некоторые обычные элементы
// HTML конвертируются в их текстовые эквиваленты.

        $search = array ("'<script[^>]*?>.*?</script>'si",  // Вырезается javascript
            "'<[\/\!]*?[^<>]*?>'si",           // Вырезаются html-тэги
            "'([\r\n])[\s]+'",                 // Вырезается пустое пространство
            "'&(bull|#149);'i",                 // Замещаются html-элементы
            "'&(quot|#34);'i",                 // Замещаются html-элементы
            "'&(amp|#38);'i",
            "'&(lt|#60);'i",
            "'&(gt|#62);'i",
            "'&(nbsp|#160);'i",
            "'&(iexcl|#161);'i",
            "'&(cent|#162);'i",
            "'&(pound|#163);'i",
            "'&(copy|#169);'i");                    // вычисляется как php
//,
//            "'&#(\d+);'e"
        $replace = array ("",
            "",
            "\\1",
            "",
            "\"",
            "&",
            "<",
            ">",
            " ",
            chr(161),
            chr(162),
            chr(163),
            chr(169),
            "chr(\\1)");

        return preg_replace($search, $replace, $document);






    }

    $data .= print_header();

    $all_cat = '1';
    $data .=  "<categories>\n";

    function print_categories($parent) {
        global $all_cat, $prefix;
        $q = new query();
        $text = '';
        $child = $q->select("select id,name from ".$prefix."catalog where parent=".to_sql($parent)." ");
        foreach($child as $row){
            $text .= "<category id=\"".$row["id"]."\">".convert_text($row["name"])."</category>\n";
            $all_cat .= ','.$row["id"];

            $text .=print_categories($row['id']);

        }
        return $text;
    }
    $data .= print_categories(0);
    $data .=  "</categories>
";
//$data .= "<local_delivery_cost>200</local_delivery_cost>\n";


    function print_offers() {
        global $kurs,$all_cat, $prefix, $root_path,$db;
        $q = new query();
        $dir_folder = 'files/goods/';
        $text = '';




        //$sql = "select * from ".$prefix."goods where status=1 and (nal=1 or fororder=1) and for_market=1 and img1!='' and partner_uid>0 ";
        $sql = "select * from ".$prefix."goods where status=1 and nal>0 and img1!='' ";




        $goods = mysql_query($sql,$db);
        while ($row = mysql_fetch_assoc($goods)) {

            //$goodimg = get_image($row,'files/goods/1/','img1',0);
            $goodimg = get_image_cpu($row,$dir_folder.'1/','img1',0);

            if(empty($goodimg)){
                continue;
            }



            if($row['nal'] == 1) $nal = 'true';
            else $nal = 'false';


            $descr = covert_html_to_text_all(strip_tags($row['text']));


            if (strlen($descr)>2500) $descr=substr($descr,0,2500)."...";

            $text .= '<offer id="'.$row["id"].'" available="'.$nal.'">
		<url>https://tkaninsk.com/goods/'.$row['cpu'].'/</url>
		<price>'.$row["price"].'</price>
		<currencyId>RUR</currencyId>
		<categoryId>'.$row['catalog'].'</categoryId>
		';


            $goodimg = get_image_cpu($row,$dir_folder.'1/','img1',0);

            if(!empty($goodimg)){
                $text .= "<picture>https://tkaninsk.com".$goodimg."</picture>\n";
                for($i=2; $i<=6;$i++){
                   // $goodimg = get_image($row,'files/goods/'.$i.'/','img'.$i,0);
                    //$goodimg =  get_image_cpu($row,$dir_folder.'1/','img1',0);
                    $goodimg = get_image_cpu($row,$dir_folder.$i.'/','img'.$i,0);
                    if(!empty($goodimg)){
                        $text .= "		<picture>https://tkaninsk.com".$goodimg."</picture>\n";
                    }
                }




            }


            $text .= '<name>'. convert_text($row["name"]).'</name>
		<description><![CDATA['.$descr.']]></description>';



            $text .= '
			<pickup>true</pickup>
			<delivery>true</delivery>
			';
            if(!empty($row['dop_teh'])){
                $mas = unserialize($row['dop_teh']);
                foreach($mas as $v){
                    if($v['val'] == '+') $v['val'] = 'да';
                    $text .= '<param name="'.$v['name'].'">'.$v['val'].'</param>'."\n";
                }
            }



            $text .= '
</offer>
';







        }
        return $text;
    }

    $data .= "<offers>\n";
    $data .=  print_offers();
    $data .=  "</offers>\n";


    $data .=  print_footer();
    /**** для market.yandex.ru *****/
    $filename = 'yprice.xml';
    if (!$handle = fopen($filename, 'w+')) {
        echo "Error opening file (".$filename.")";
        return -1;
    }
    if (fwrite($handle, $data) === FALSE) {
        echo "Cannot write to file (".$filename.")";
        return -2;
    }

    /***** для price.ru *****/
    /* Теперь в отдельном файле
    $data2 = str_replace('{DOP_LINK}','?gift=58982b4145&amp;rel=price.ru&amp;utm_source=priceru&amp;utm_medium=cpc&amp;utm_campaign=goods',$data);
    $filename = '/var/www/vakas/data/www/tkaninsk.com/priceru.xml';
    if (!$handle = fopen($filename, 'w+')) {
         echo "Error opening file (".$filename.")";
         return -1;
    }
    if (fwrite($handle, $data2) === FALSE) {
        echo "Cannot write to file (".$filename.")";
        return -2;
    }
    */

    echo $data;
    //echo 'done';
?>
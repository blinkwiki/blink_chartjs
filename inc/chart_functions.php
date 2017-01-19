<?php
/**
* 
* ADDING A NEW TREND TO A CHART
* 
* To add new trend to a chart, simply go the get_state_input() function
* and add new parameters for the trend: 
* 
*   the name of the trend
*   the color of the trend
*   the dom_id to distinguish the chart elements from others
*   the primary MySQL table
*   the key_field in the primary table
*   sql_whr to retrieve relevant values related to the x_data 
* 
*/
/**
* 
* ADDING A NEW CHART
* 
* To add new chart, go to the function and add new chart name and parameters: 
* 
*   1. add the name and title of the chart to the $all_charts();
*   2. add the case contruct for the new chart name in the get_x_input(); function
*   3. add the trend input  in the get_state_input();
*
*   the dom_id to distinguish the trend elements from others
*   the chart input function
*   the x_data query
*   define the get_data() JS funtion for the chart
* 
*/
?>

<?php

function get_chart_data($conn)
{
    // get all the chart names and titles
    $all_charts = get_all_charts();

    // get all the x values
    foreach($all_charts['chart_name'] as $chart_key => $chart_val)
    {
        $x_data[$chart_val] = get_x_data($chart_val, $conn);
    }

    // get all values in the trend
    foreach($x_data as $xdata_key => $xdata_val)
    {
        $y_trends[$xdata_key] = get_trends($xdata_val, $conn);
    }
    
    // gather and return
    $chart_data['all_charts'] = $all_charts;
    $chart_data['x_data'] = $x_data;
    $chart_data['y_trends'] = $y_trends;
    
    return $chart_data;
}

function get_all_charts()
{
    // all charts in an array
    $all_charts = array(

        // the chart name
        'chart_name' => array(
            'state_aviation_data'
            , 'state_airport_data'
            , 'state_aircraft_data'
            , 'annual_inspection_data'
        )

        // the chart title
        , 'chart_title' => array(
            'Country Aviation Data'
            , 'Country Airport Data'
            , 'Country Aircraft Data'
            , 'Distribution of Inspections by Year'
        )
    );
    return $all_charts;
}

function get_x_input ($chart_name='state_aviation_data')
{
    // initialise the x value array
    $x_data = array();
    
    switch($chart_name)
    {
        case 'annual_inspection_data':
        {
            // list all the dates
            $x_data = array(

                // the name of the chart
                'chart_name' => $chart_name

                // the field title tjhat will appear in the x axis
                , 'x_title' => 'year'

                // the sql to retrieve x values
                , 'sql' => array('2011', '2012', '2013', '2014', '2015', '2016')
                
                // do not run the sql 
                , 'run_sql' => false

            );
            break;
        }
        case 'state_airport_data':
        case 'state_aircraft_data':
        default:
        {
            /* Australia 11, Canada 34, Germany 77, Japan 101, South Africa 274, UAE 299, UK 300, US 301*/
            
            $x_data = array(

                // the name of the chart
                'chart_name' => $chart_name

                // the field title tjhat will appear in the x axis
                , 'x_title' => 'state_code'

                // the sql to retrieve x values
                , 'sql' => get_read_sql('tbl_states')
                    // include the x values in the where clause
                    .' AND `tbl_states`.`state_id` IN (11, 34, 77, 101, 274, 299, 300, 301)'
                
                // run the sql 
                , 'run_sql' => true

            );
        }
    }
    return $x_data;
}

function get_x_data($chart_name='state_aviation_data', $conn)
{
    // initialise the x value array
    $x_data = get_x_input($chart_name);
    
    if ($x_data['run_sql'] == false)
    {
        // save the total x values
        $x_data['total'] = count($x_data['sql']);
        
        // save the data
        $x_data[$x_data['x_title']] = $x_data['sql'];
    }
    else
    {
        
        $rs_x = mysql_query($x_data['sql'], $conn) or die(mysql_error());
        $row_x = mysql_fetch_assoc($rs_x);
        $total_x = mysql_num_rows($rs_x);

        // save the total x values
        $x_data['total'] = $total_x;

        // loop through
        for ($i=0; $i<$total_x; $i++)
        {
            // append the state title to the array
            //array_push($x_data['values'], $row_x['state_code']);

            // the line above can be modified to deposite all the values in the row into separate named arrays. This will allow for the use of the other field values e.g. the state name instead of the state code
            foreach($row_x as $rkey => $rval)
            {
                if (!is_array($x_data[$rkey]))
                {
                    $x_data[$rkey] = array();
                }

                // append the state title to the array
                array_push($x_data[$rkey], $rval);
            }

            // fetch the next row
            $row_x = mysql_fetch_assoc($rs_x);
        }
    }
    
    // return the data
    return $x_data;
}

function get_trends($x_data, $conn)
{
    
    $y_trends = array(

        // The name of the trend.
        'name' => array(),

        // the color of the trend
        'color' => array(),

        // y-values of the trend
        'values' => array(),

        // detailed csv the trend values above of the trend: used to enumerate counts
        'csv' => array(),

        // The DOM identifyier for the HTML element repsenting the trend.
        'dom_id' => array(),

    );
    
    // get the input paramters for this chart
    $trend_count = get_state_input('name', 'count', $x_data['chart_name']);
    
    for ($y=0; $y<($trend_count); $y++)
    {

        $trend_id = $y;

        // define the name of the trend 1
        $y_trends['name'][$trend_id] = get_state_input('name', $trend_id, $x_data['chart_name']);

        // define the color of the trend 1
        $y_trends['color'][$trend_id] = get_state_input('color', $trend_id, $x_data['chart_name']);

        // define the DOM ID of the trend 1
        // : again a switch-case construct can be used to
        // distinguish the name of each trend
        $y_trends['dom_id'][$trend_id] = get_state_input('dom_id', $trend_id, $x_data['chart_name']);

        // define the sql or api to retrieve trend 1 values
        // this segment can be put into a switch case to determine the sql for each trend: this will enable the whole trend segment to enter a perfect loop
        for ($x=0; $x<$x_data['total']; $x++)
        {
            // SQL: get the y values for this current x value
            $sql_y = ''

                // get all rows in the trend table or service
                . get_read_sql(get_state_input('table', $trend_id, $x_data['chart_name']))

                // that meet the trend requirements

                    // trend where statement opens
                    . get_state_input('sql_whr', $trend_id, $x_data['chart_name'], $x_data, $x)
            ;
            //if ($x_data['chart_name'] == 'state_aircraft_data')
            //{
            //echo $sql_y.'<br><br>';
            //}
            
            // run the query
            $rs_y = mysql_query($sql_y, $conn) or die(mysql_error());
            $row_y = mysql_fetch_assoc($rs_y);
            $total_y = mysql_num_rows($rs_y);

            // save the total count
            if (!is_array($y_trends['values'][$trend_id]))
            {
                $y_trends['values'][$trend_id] = array();
            }
            array_push($y_trends['values'][$trend_id], $total_y);

            // loop through the row to save the details
            for ($i=0; $i<$total_y; $i++)
            {
                // in case there is a need for more details,
                // supporting the count saved above.
                // This segement saves the CSV for the row id
                if ($i > 0)
                {
                    $y_trends['csv'][$trend_id][$x] .= ',';
                }
                $y_trends['csv'][$trend_id][$x] .= $row_y[get_state_input('key_field', $trend_id, $x_data['chart_name'])];

            }

        }
    }
    return $y_trends;
}

/**
*
* SWITCH CASE FUNCTIONS
*
* This segment will hold several different functions of similar structure
* that will define the input needed to generate data for the different charts
*
*/
    
function get_state_input(
    // the name of the chart
    $trend_value
    , $trend_id
    , $chart_name = 'state_aviation_data'
    , $x_data=array()
    , $x=0
)
{
    switch($chart_name)
    {
        case 'annual_inspection_data':
        {
            $input_data = array(

                // trend name
                'name' => array(
                    'Number of inspections carried out by this year'
                    , 'Number of inspections entered by this year'
                    , 'Number of inspections modified by this year'
                ),
                
                // trend color
                'color' => array(
                    'rgb(235, 152, 78)'
                    , 'rgb(192, 90, 92)'
                    , 'rgb(93, 173, 226)'
                ),
                
                // trend DOM identifyer : must be perculiar
                'dom_id' => array(
                    'inspections_by_year'
                    , 'inspections_created_by_year'
                    , 'inspections_modified_by_year'
                ),
                
                // the MySQL table or API service
                'table' => array(
                    'tbl_inspections'
                    , 'tbl_inspections'
                    , 'tbl_inspections'
                ),
                
                // the table or services key field
                'key_field' => array(
                    'insp_id'
                    , 'insp_id'
                    , 'insp_id'
                ),
                
                // the where clause that retrieves the values for the trend
                'sql_whr' => array(

                    // All inspections done in the specified year
                    ' AND `tbl_inspections`.`date_of_insp` LIKE "'.$x_data['year'][$x].'-%"'

                    // All inspections created in the specified year
                    , ' AND `tbl_inspections`.`date_created` LIKE "'.$x_data['year'][$x].'-%"'

                    // All inspections modified in the specified year
                    , ' AND `tbl_inspections`.`date_modified` LIKE "'.$x_data['year'][$x].'-%"'

                ),
            );
        }
            break;
        case 'state_airport_data':
        {
        
            $input_data = array(

                // trend name
                'name' => array(
                    'Number of airports'
                    , 'Number of airports with IATA codes'
                ),

                // trend color
                'color' => array(
                    'rgb(69, 179, 157)'
                    , 'rgb(22, 160, 133)'
                ),

                // trend DOM identifyer : must be perculiar
                'dom_id' => array(
                    'state_by_airports'
                    , 'state_by_iata_airports'
                ),

                // the MySQL table or API service
                'table' => array(
                    'tbl_airports'
                    , 'tbl_airports'
                ),

                // the table or services key field
                'key_field' => array(
                    'airport_id'
                    , 'airport_id'
                ),

                // the where clause that retrieves the values for the trend
                'sql_whr' => array(

                    // All Airports in the specified country
                    ' AND `tbl_airports`.`airport_country_id` = "'.$x_data['state_id'][$x].'"'

                    // Airports with IATA codes
                    , ' AND `tbl_airports`.`airport_iata_code` <> ""'
                    // All Airports in the specified country
                    .' AND `tbl_airports`.`airport_country_id` = "'.$x_data['state_id'][$x].'"'

                ),

            );
        }
            break;
        case 'state_aircraft_data':
        {
            $input_data = array(

                // trend name
                'name' => array(
                    'Number of aircraft manufacturers'
                    , 'Number of aircraft types manufactured'
                ),

                // trend color
                'color' => array(
                    'rgb(52, 152, 219)'
                    , 'rgb(93, 173, 226)'
                ),

                // trend DOM identifyer : must be perculiar
                'dom_id' => array(
                    'state_by_manu'
                    , 'state_by_aircraft'
                ),

                // the MySQL table or API service
                'table' => array(
                    'tbl_manu_codes'
                    , 'tbl_aircraft_types'
                ),

                // the table or services key field
                'key_field' => array(
                    'manu_id'
                    , 'aircraft_id'
                ),

                // the where clause that retrieves the values for the trend
                'sql_whr' => array(

                    // All Manufacturers in the specified country
                    ''
                    .' AND ('

                            .'`tbl_manu_codes`.`manu_name` LIKE "%('.$x_data['state_name'][$x].')%"'

                            .' OR `tbl_manu_codes`.`manu_name` LIKE "%('.$x_data['state_code'][$x].')%"'

                    .')'

                    // All Aircraft Types from Manufactuters in the specified country
                    , ''
                    .' AND `tbl_aircraft_types`.`aircraft_manu_id` IN ('
                        .'SELECT `tbl_manu_codes`.`manu_id`'
                        .' FROM `tbl_manu_codes`'
                        .' WHERE 1'
                        .' AND ('

                            .'`tbl_manu_codes`.`manu_name` LIKE "%('.$x_data['state_name'][$x].')%"'

                            .' OR `tbl_manu_codes`.`manu_name` LIKE "%('.$x_data['state_code'][$x].')%"'

                        .')'
                    .')'
                ),

            );
        }
            break;
        default: 
        // If you wish to develop a new chart, make a copy of this in a new case construct and modify according.
        {
            $input_data = array(

                // trend name
                'name' => array(
                    'Number of airports'
                    , 'Number of airports with IATA codes'
                    , 'Number of airlines'
                    , 'Number of aircraft manufacturers'
                    , 'Number of aircraft types manufactured'
                ),

                // trend color
                'color' => array(
                    'rgb(69, 179, 157)'
                    , 'rgb(22, 160, 133)'
                    , 'rgb(155, 89, 182)'
                    , 'rgb(52, 152, 219)'
                    , 'rgb(93, 173, 226)'
                    , ''
                    , 'rgb(255, 0, 0)'
                    , 'rgb(235, 152, 78)'
                    , 'rgb(192, 192, 192)'
                ),

                // trend DOM identifyer : must be perculiar
                'dom_id' => array(
                    'state_by_airports'
                    , 'state_by_iata_airports'
                    , 'state_by_airlines'
                    , 'state_by_manu'
                    , 'state_by_aircraft'
                ),

                // the MySQL table or API service
                'table' => array(
                    'tbl_airports'
                    , 'tbl_airports'
                    , 'tbl_aoc'
                    , 'tbl_manu_codes'
                    , 'tbl_aircraft_types'
                ),

                // the table or services key field
                'key_field' => array(
                    'airport_id'
                    , 'airport_id'
                    , 'aoc_id'
                    , 'manu_id'
                    , 'aircraft_id'
                ),

                // the where clause that retrieves the values for the trend
                'sql_whr' => array(

                    // All Airports in the specified country
                    ' AND `tbl_airports`.`airport_country_id` = "'.$x_data['state_id'][$x].'"'

                    // Airports with IATA codes
                    , ' AND `tbl_airports`.`airport_iata_code` <> ""'
                    // All Airports in the specified country
                    .' AND `tbl_airports`.`airport_country_id` = "'.$x_data['state_id'][$x].'"'

                    // All Airlines in the specified country
                    , ' AND `tbl_aoc`.`aoc_country_id` = "'.$x_data['state_id'][$x].'"'

                    // All Manufactuters in the specified country
                    , ''
                    .' AND ('

                            .'`tbl_manu_codes`.`manu_name` LIKE "%('.$x_data['state_name'][$x].')%"'

                            .' OR `tbl_manu_codes`.`manu_name` LIKE "%('.$x_data['state_code'][$x].')%"'

                    .')'

                    // All Aircraft Types from Manufactuters in the specified country
                    , ''
                    .' AND `tbl_aircraft_types`.`aircraft_manu_id` IN ('
                        .'SELECT `tbl_manu_codes`.`manu_id`'
                        .' FROM `tbl_manu_codes`'
                        .' WHERE 1'
                        .' AND ('

                            .'`tbl_manu_codes`.`manu_name` LIKE "%('.$x_data['state_name'][$x].')%"'

                            .' OR `tbl_manu_codes`.`manu_name` LIKE "%('.$x_data['state_code'][$x].')%"'

                        .')'
                    .')'
                ),

            );
        }
    }

    
    $result = $input_data[$trend_value][$trend_id];
    if ($trend_id === 'count')
    {
        $result = count($input_data[$trend_value]);
    }
    
    return $result;
    
}


/**
*
* This function simply spits out javascript functions that will
* build the different charts. Hand-coding these functions into JS
* will prove tedious as the number of charts grows
* 
*/
function build_chart_data_js($x_data, $y_trends)
{
    // initialise the code string
    $cdb_code = '';
    
    foreach($x_data as $xdata_key => $xdata_val)
    {
        // the chart name
        $chart_name = $xdata_key;
        
        // define the JS function
        $cdb_code .= ''
            .'function build_'.$chart_name.'()'
            .'{'

            // build the data
            .'var chart_data = {'

                // the x axis
                .'labels : ['
                    // x axis data in CSV format
                    .'"'.implode('","', $x_data[$chart_name][$x_data[$chart_name]['x_title']]).'"'
                .'],'

                // the datasets
                .'datasets : ['
        ;
    
        for ($y=0; $y<(get_state_input('name', 'count', $chart_name)); $y++)
        {
            $cdb_code .= ''
            .'{'
                
                // the fill color
                .'fillColor : "'
                    .$y_trends[$chart_name]['color'][$y]
                .'",'
                
                // the stroke color
                .'strokeColor : "'
                    .$y_trends[$chart_name]['color'][$y]
                .'",'
                
                // the data in CSV
                .'data : ['
                    .implode(',', $y_trends[$chart_name]['values'][$y])
                .']'
                
            .'},'
            ;
        }
        
        // close out the JS function
        $cdb_code .= ''
                .']'
            .'};'

            // get all the points on the trend
            .'var set_unset = new Array(chart_data.datasets.length);'
            
            // set all the values to 1 to show all bars or points on the trend
            .'for (i=0; i<set_unset.length; i++)'
            .'{'
                // set the value to 1 to display
                .'set_unset[i] = 1;'
            .'}'
            
            // hide all the points that have been deselected
            .'var trend_links = document.getElementsByName("'.$chart_name.'_trend_links");'
            // loop through the trend links
            .'for (i=0; i<trend_links.length; i++)'
            .'{'
                // if the class name of the link is red
                .'if (trend_links[i].className == "red")'
                .'{'
                    // set the chart to hidden
                    .'set_unset[i] = 0;'
                .'}'
            .'}'
            
            // splice the data
            .'chart_data = splice_data_set(chart_data, set_unset);'
            //.'chart_data = splice_data_set(chart_data);'

            // return the generated chart data
            .'return chart_data;'

        .'}'
        ;
    }
    
    // this JS function builds a set of chart data
    // based on the chart name given to it
    {
        
    $cdb_code .= ''
        .'function get_chart_data(chart)'
        .'{'
    ;
    
    $count = 0;
    foreach($x_data as $xdata_key => $xdata_val)
    {
        // the chart name
        $chart_name = $xdata_key;
        
        if ($count > 0)
        {
            $cdb_code .= ''
                .'else '
            ;
        }
        else
        {
            $default_chart_name = $xdata_key;
        }
        
        $cdb_code .= ''
            .'if (chart == "'.$chart_name.'")'
            .'{'
                .'js_'.$chart_name.' = build_'.$chart_name.'();'
                .'chart_data = js_'.$chart_name.';'
            .'}'
        ;
        $count++;
    }
    
    $cdb_code .= ''
            .'else'
            .'{'
                .'js_'.$default_chart_name.' = build_'.$default_chart_name.'();'
                .'chart_data = js_'.$default_chart_name.';'
            .'}'
            .'return chart_data;'
        .'}'
    ;
    }

    
    // return results
    return $cdb_code;
}

?>
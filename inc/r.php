<?php //*********************************************READ ?>

    
    <hr>
    
    <strong></strong><br>

<?php
    // get all the chart data needed
    $chart_data = get_chart_data($conn);
    
    // separate into two
    $all_charts = $chart_data['all_charts'];
    $x_data = $chart_data['x_data'];
    $y_trends = $chart_data['y_trends'];
?>

<section class="panel b-a table-responsive table-responsive">
    
    <div align="center">
    
    <!--HEADER-->
        
    <table width="700px" class="adminlist table table-striped m-b-none b-t b-light">
        <tbody>
            <tr class="row1">
                    <td>

    <div class="d70">
        
        <div class="f20">
        <i class="fa fa-th-large"></i><span class="fw_b">Chart: </span>
            
        <a href="#" id="div_mini_chart" name="div_mini_chart"
           class="col-md-12 fw_b dark"
           data-toggle="tooltip"
           onclick="select_chart();" style="display:;" title="click to select a chart..." data-original-title="click to select a chart..."
        >
            <span id="div_chart_title">Country Aviation Data</span>
        </a>
                            
        <?php 
        // The Chart Changer Select Box
            // each of the options in this select box would have a switch function to determine the trend (PHP and Javascript)
        
        // the select box would be built with the all_charts() array
        
        ?>
        <select class="form-control f14 c_w200x" id="sel_mini_chart" name="sel_mini_chart" onchange="change_chart();" style="display:none">
            
            <?php for($i=0; $i<count($all_charts['chart_name']); $i++) { ?>
            
            <option value="<?php echo $all_charts['chart_name'][$i]; ?>"><?php echo $all_charts['chart_title']{$i}; ?></option>
            
            <?php } ?>
            
        </select>
        <input type="hidden" id="hd_chart_mode" value="bar">
        </div>
        <div class="f14">
            <span class="fw_b">Presentation: </span>
            <a href="#" name="div_visual" id="div_chart_tag" onclick="
                show_hide_chart_table('table');
            " style="display:none;">
                table
            </a>
            <span name="div_visual" style="display:none;">&nbsp;|&nbsp;</span>
            <a href="#" name="div_visual" id="div_table_tag" onclick="
                show_hide_chart_table('chart');
            " style="display:;">
                chart
            </a>
            &nbsp;|&nbsp;
            <a href="#" onclick="
                multi_show_hide('div_visual', 'show');
            ">
                both
            </a>
        </div>
    </div>
    <div class="d30" align="right">
        
        <span class="f14">
        <?php // toggle chart modes ?>
        <span class="fw_b">Mode: </span>
        <span id="chmode_btn_line" class="">
            <a href="#" onclick="
                chart_mode_click('line');
            ">line</a>
        </span>
        <span id="chmode_btn_bar" class=" hidex" style="display:none;">
            <a href="#" onclick="
                chart_mode_click('bar');
            ">bar</a>
        </span>
        <?php // toggle chart modes ?>
        </span>
        
        <span class="nullx">
            &nbsp;&nbsp;
        </span>
            
                            
    </div>
    <div class="dclear"></div>
            
                    </td>
                </tr>
        </tbody>
    </table>
        
    <!--HEADER-->
        
        
    <!--CANVAS-->
        
    <table class="adminlist table table-striped m-b-none b-t b-light">
            <tbody>
    
                <tr class="row0">
                    <td>
                        <div name="div_visual" id="div_chart" align="center">
                            
    <canvas id="canvas" class="canvas" height="300" width="700" style="width: 700px; height: 300px;"></canvas>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        
    <!--CANVAS-->
                   
    <br>
        
    <!--TABLE-->
        
    <table name="div_visual" id="div_table" class="f12 adminlist table table-striped m-b-none b-t b-light" style="width:700px;">
        
        
        <thead>
        <?php foreach($x_data as $xdata_key => $xdata_val) {
            
            // the chart name
            $tbl_chart_name = $xdata_key;
    
            $tbody_class = 'none';
            if ($tbl_chart_name == 'state_aviation_data')
            $tbody_class = '';
        ?>
            <tr name="chart_tables" id="chart_table_header_<?php echo $tbl_chart_name; ?>"  class="row1 fw_b" style="display:<?php echo $tbody_class; ?>;">
                
                <?php // the chart key header cell ?>
                <td align="center" width="5%">
                    <span class="fw_b f25 grey" title="Keys">•</span>
                </td>
                <?php // the chart key header cell ?>
                
                <td class="fw_b" width="30%">Trends</td>
                
                <?php
                // the other cells in the row containing x values
                {
                    $cell_starter = '<td align="center" class="fw_b" width="5%">';
                    $cell_closer = '</td>';
                    $cell_divider = $cell_closer . $cell_starter;
                    
                    echo ''
                        
                        // start the first cell
                        .$cell_starter
                            
                            // implode array with the cell divider
                            .implode($cell_divider, $x_data[$tbl_chart_name][$x_data[$tbl_chart_name]['x_title']])
                        
                        // close out the cells in the row
                        .$cell_closer
                    ;
                }
                // the other cells in the row containing x values
                ?>
            
            </tr>
        <?php } ?>
        </thead>
        
        
        
        
        <?php foreach($x_data as $xdata_key => $xdata_val) {
            
            // the chart name
            $tbl_chart_name = $xdata_key;
            
            $tbody_class = 'none';
            if ($tbl_chart_name == 'state_aviation_data')
            $tbody_class = '';
                
        ?>
        
        <tbody name="chart_tables" id="chart_table_<?php echo $tbl_chart_name; ?>" class="<?php echo $tbody_class; ?>" style="display:<?php echo $tbody_class; ?>;">
            
        <?php for ($y=0; $y<(get_state_input('name', 'count', $tbl_chart_name)); $y++) { ?>
        
            <tr>
                <td align="center" class="fw_b f25" style="color:<?php echo $y_trends[$tbl_chart_name]['color'][$y]; ?>;">•</td>
                
                <td class="fw_b">
                    <!--
                        id="y<?php echo $y; ?>_2"
                        name="<?php // echo $tbl_chart_name; //$y_trends['dom_id'][$y]; ?>"
                    -->
                    <a href="#"
                       id="<?php echo $tbl_chart_name.'_'.$y; ?>_2"
                       name="<?php echo $tbl_chart_name; ?>_trend_links"
                       class="" onclick="set_y_trends(this);"
                    >
                        <?php echo $y_trends[$tbl_chart_name]['name'][$y]; ?>
                    </a>
                </td>
                
                <?php
                    
                    $cell_starter = '<td class="" align="center">';
                    $cell_closer = '</td>';
                    $cell_divider = $cell_closer . $cell_starter;
                    
                    echo ''
                        
                        // start the first cell
                        .$cell_starter
                            
                            // implode array with the cell divider
                            .implode($cell_divider, $y_trends[$tbl_chart_name]['values'][$y])
                        
                        // close out the cells in the row
                        .$cell_closer
                    ;
                ?>
            </tr>
        
        <?php } ?>
            
        </tbody>
        
        <?php } ?>
        
        
        
        
    </table>
        
    <!--TABLE-->
        
<script>

// BUILD CHART DATA

<?php
    // get the JS functions that build chart data
    echo build_chart_data_js($x_data, $y_trends);
?>

// BUILD CHART DATA


{
		
    trend_link_start_i = 0;
    trend_link_id = 0;
    
    var lineOpt = 
    {
			
        bezierCurve : false,
        datasetFill : false,
        
        // Add scale functions and values : PHP function should be able to determine the scale values using the maxmimun and minimun values across all trends
        /*
            scaleOverride: true,
			scaleSteps: 25,
			scaleStepWidth: 10,
			scaleStartValue: 0,
        */
			
    };
            
}
    
// build the data and save in a global variable
var js_state_data = build_state_aviation_data(); 
    
// draw the chart using the global variables
draw_graph();

</script>
	
        <br><span class="null_bs"></span>&nbsp;

    </div>
    
</section>


<?php //*********************************************READ ?>